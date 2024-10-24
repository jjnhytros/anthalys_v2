@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Recent Messages</h3>
        <div class="inbox_chat">
            @foreach ($recentChats as $chat)
                @php
                    $chatParticipant =
                        $chat->first()->sender_id === Auth::user()->citizen->id
                            ? $chat->first()->recipient
                            : $chat->first()->sender;
                @endphp
                <div class="chat_list">
                    <div class="chat_people">
                        <div class="chat_img">
                            <img src="{{ asset('path_to_default_avatar') }}" alt="avatar">
                        </div>
                        <div class="chat_ib">
                            <h5>
                                <a href="{{ route('chat.show', $chatParticipant->id) }}">{{ $chatParticipant->name }}</a>
                                <span class="chat_date">{{ $chat->first()->created_at->format('M d') }}</span>
                            </h5>
                            <p>{{ Str::limit($chat->first()->message, 50) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
