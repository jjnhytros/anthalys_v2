<?php

namespace App\Http\Controllers\City;

use App\Models\City\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::where('is_message', true)->get();
        return view('citizen.messages.index', compact('messages'));
    }

    public function show($id)
    {
        $message = Message::findOrFail($id);
        return view('citizen.messages.show', compact('message'));
    }

    public function create()
    {
        return view('citizen.messages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:citizens,id',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        Message::create([
            'sender_id' => Auth::user()->citizen->id(),
            'recipient_id' => $validated['recipient_id'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'is_message' => true,
        ]);

        return redirect()->route('messages.index')->with('success', 'Messaggio inviato con successo!');
    }
}
