<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use App\Models\City\Citizen;
use App\Models\City\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Visualizza la lista delle chat recenti
    public function index()
    {
        // Recupera il cittadino corrente associato all'utente loggato
        $citizen = Auth::user()->citizen;

        // Recupera le chat recenti dell'utente (sia come mittente che come destinatario)
        $recentChats = Message::where('recipient_id', $citizen->id)
            ->orWhere('sender_id', $citizen->id)
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($message) use ($citizen) {
                return $message->sender_id === $citizen->id ? $message->recipient_id : $message->sender_id;
            });

        // Registra l'attività di visualizzazione delle chat
        CLAIR::logActivity('C', 'index', 'Visualizzazione delle chat recenti', [
            'citizen_id' => $citizen->id,
            'chat_count' => $recentChats->count()
        ]);

        return view('citizens.chat.index', compact('recentChats'));
    }

    // Mostra la conversazione tra l'utente loggato e un altro cittadino
    public function show($id)
    {
        // Recupera il cittadino corrente
        $citizen = Auth::user()->citizen;

        // Recupera la cronologia dei messaggi tra il cittadino corrente e l'utente con ID specificato
        $messages = Message::where(function ($query) use ($citizen, $id) {
            $query->where('sender_id', $citizen->id)
                ->where('recipient_id', $id);
        })->orWhere(function ($query) use ($citizen, $id) {
            $query->where('sender_id', $id)
                ->where('recipient_id', $citizen->id);
        })->orderBy('created_at', 'asc')->get();

        // Recupera i dettagli del destinatario
        $recipient = Citizen::findOrFail($id);

        // Registra l'attività di visualizzazione della conversazione
        CLAIR::logActivity('I', 'show', 'Visualizzazione dei messaggi tra il cittadino e un altro utente', [
            'citizen_id' => $citizen->id,
            'recipient_id' => $id,
            'message_count' => $messages->count()
        ]);

        return view('citizens.chat.show', compact('messages', 'recipient'));
    }

    // Invia un nuovo messaggio
    public function sendMessage(Request $request)
    {
        // Validazione della richiesta
        $request->validate([
            'recipient_id' => 'required|exists:citizens,id',
            'message' => 'required|string|max:255',
        ]);

        // Recupera il cittadino corrente
        $citizen = Auth::user()->citizen;

        // Crea e salva un nuovo messaggio
        $message = Message::create([
            'sender_id' => $citizen->id,
            'recipient_id' => $request->recipient_id,
            'message' => $request->message,
            'is_message' => true, // Indica che è un messaggio di chat
        ]);

        // Registra l'attività di invio del messaggio
        CLAIR::logActivity('I', 'sendMessage', 'Invio di un nuovo messaggio', [
            'sender_id' => $citizen->id,
            'recipient_id' => $request->recipient_id,
            'message_id' => $message->id
        ]);

        // Reindirizza alla pagina della chat con l'utente specificato
        return redirect()->route('chat.show', $request->recipient_id)->with('success', 'Message sent successfully');
    }
}
