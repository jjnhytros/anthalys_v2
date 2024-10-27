<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use App\Models\City\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::where('is_message', true)->get();

        // Registra l'attività di visualizzazione dei messaggi
        CLAIR::logActivity(
            'C',
            'index',
            'Visualizzazione della lista dei messaggi per il cittadino',
            ['user_id' => Auth::user()->id]
        );

        return view('citizen.messages.index', compact('messages'));
    }

    public function show($id)
    {
        $message = Message::findOrFail($id);

        // Registra l'attività di visualizzazione del singolo messaggio
        CLAIR::logActivity(
            'C',
            'show',
            'Visualizzazione del dettaglio di un messaggio',
            ['message_id' => $id]
        );

        return view('citizen.messages.show', compact('message'));
    }

    public function create()
    {
        // Registra l'attività di accesso alla pagina di creazione di un messaggio
        CLAIR::logActivity(
            'C',
            'create',
            'Accesso alla pagina di creazione di un nuovo messaggio',
            ['user_id' => Auth::user()->id]
        );

        return view('citizen.messages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:citizens,id',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        // Creazione del messaggio
        Message::create([
            'sender_id' => Auth::user()->citizen->id,
            'recipient_id' => $validated['recipient_id'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'is_message' => true,
        ]);

        // Registra l'attività di invio del messaggio
        CLAIR::logActivity(
            'I',
            'store',
            'Invio di un nuovo messaggio',
            [
                'sender_id' => Auth::user()->citizen->id,
                'recipient_id' => $validated['recipient_id'],
                'subject' => $validated['subject']
            ]
        );

        return redirect()->route('messages.index')->with('success', 'Messaggio inviato con successo!');
    }
}
