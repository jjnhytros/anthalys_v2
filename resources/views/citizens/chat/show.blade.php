@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="msg_history">
            @foreach ($messages as $message)
                @if ($message->sender_id == Auth::user()->citizen->id)
                    <!-- Messaggi inviati -->
                    <div class="outgoing_msg">
                        <div class="sent_msg">
                            <p>{{ $message->message }}</p>
                            <span class="time_date">{{ $message->created_at->format('H:i | M d') }}</span>
                        </div>
                    </div>
                @else
                    <!-- Messaggi ricevuti -->
                    <div class="incoming_msg">
                        <div class="incoming_msg_img">
                            <img src="{{ asset('path_to_avatar') }}" alt="avatar">
                        </div>
                        <div class="received_msg">
                            <div class="received_withd_msg">
                                <p>{{ $message->message }}</p>
                                <span class="time_date">{{ $message->created_at->format('H:i | M d') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Modulo per inviare un nuovo messaggio -->
        <div class="type_msg">
            <form action="{{ route('chat.send') }}" method="POST">
                @csrf
                <div class="input_msg_write">
                    <input type="hidden" name="recipient_id" value="{{ $recipient->id }}">
                    <input type="text" class="write_msg form-control" name="message" placeholder="Type a message"
                        required>
                    <button class="msg_send_btn btn btn-primary" type="submit"><i class="bi bi-send"></i></button>
                </div>
            </form>
        </div>
    </div>
@endsection
