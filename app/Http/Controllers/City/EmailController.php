<?php

namespace App\Http\Controllers\City;

use App\Models\CLAIR;
use App\Models\City\Citizen;
use App\Models\City\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    public function inbox()
    {
        $emails = Message::where('is_email', true)
            ->where('recipient_id', Auth::user()->citizen->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Conta le email non lette
        $unreadEmailsCount = Message::where('is_email', true)
            ->where('recipient_id', Auth::user()->citizen->id)
            ->where('status', 'unread')
            ->count();

        $draftsCount = Message::where('is_email', true)
            ->where('recipient_id', Auth::user()->citizen->id)
            ->where('status', 'draft')
            ->count();

        $totalEmails = Message::where('is_email', true)
            ->where('recipient_id', Auth::user()->citizen->id)
            ->count();

        // Log dell'attività per accesso alla inbox
        CLAIR::logActivity(
            'C',
            'inbox',
            'Accesso alla inbox delle email',
            ['user_id' => Auth::user()->citizen->id]
        );

        return view('citizens.emails.index', compact('emails', 'unreadEmailsCount', 'draftsCount', 'totalEmails'));
    }

    public function compose()
    {
        // Log dell'attività per l'accesso alla composizione di email
        CLAIR::logActivity(
            'C',
            'compose',
            'Accesso alla composizione di email',
            ['user_id' => Auth::user()->citizen->id]
        );

        return view('citizens.emails.compose');
    }

    public function sendEmail(Request $request)
    {
        $validatedData = $request->validate([
            'recipient_id' => 'required|exists:citizens,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments' => 'nullable|array',
        ]);

        $email = Message::create([
            'sender_id' => Auth::user()->citizen->id,
            'recipient_id' => $validatedData['recipient_id'],
            'subject' => $validatedData['subject'],
            'message' => $validatedData['message'],
            'attachments' => json_encode($validatedData['attachments']),
            'is_email' => true,
            'status' => 'sent',
        ]);

        // Log dell'attività per invio di email
        CLAIR::logActivity(
            'I',
            'sendEmail',
            'Invio email',
            ['email_id' => $email->id, 'recipient_id' => $validatedData['recipient_id']]
        );

        return redirect()->route('email.inbox')->with('success', 'Email inviata con successo!');
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $emails = Message::where('is_email', true)
            ->where('recipient_id', Auth::user()->citizen->id)
            ->where(function ($query) use ($searchTerm) {
                $query->where('subject', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('message', 'LIKE', "%{$searchTerm}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Log dell'attività di ricerca
        CLAIR::logActivity(
            'C',
            'search',
            'Ricerca di email',
            ['searchTerm' => $searchTerm, 'user_id' => Auth::user()->citizen->id]
        );

        return view('citizens.emails.index', compact('emails'));
    }

    public function show($id)
    {
        $email = Message::findOrFail($id);
        if ($email->recipient_id !== Auth::user()->citizen->id) {
            abort(403);
        }

        // Imposta lo stato dell'email come "letta"
        $email->update(['status' => 'read']);

        // Log dell'attività per visualizzazione di email
        CLAIR::logActivity(
            'C',
            'show',
            'Visualizzazione di una email',
            ['email_id' => $email->id, 'user_id' => Auth::user()->citizen->id]
        );

        return view('citizens.emails.show', compact('email'));
    }

    public function create()
    {
        // Log dell'attività per l'accesso alla creazione di una email
        CLAIR::logActivity(
            'C',
            'create',
            'Accesso alla creazione di email',
            ['user_id' => Auth::user()->citizen->id]
        );

        return view('citizens.emails.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'attachments.*' => 'file|max:2048',
        ]);

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = $file->store('attachments', 'public');
                $attachments[] = $filename;
            }
        }

        $email = Message::create([
            'sender_id' => Auth::user()->citizen->id,
            'recipient_id' => $this->findUserByEmail($request->recipient)->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'attachments' => json_encode($attachments),
            'is_email' => true,
            'status' => 'sent',
        ]);

        // Log dell'attività per invio email
        CLAIR::logActivity(
            'I',
            'store',
            'Invio email',
            ['email_id' => $email->id, 'recipient_email' => $request->recipient]
        );

        return redirect()->route('emails.inbox')->with('success', 'Email inviata con successo!');
    }

    protected function findUserByEmail($email)
    {
        return Citizen::where('email', $email)->firstOrFail();
    }
}
