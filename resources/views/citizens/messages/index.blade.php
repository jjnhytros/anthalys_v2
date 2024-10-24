@extends('layouts.app')

@section('content')
    <h1>Messaggi</h1>
    <ul>
        @foreach ($messages as $message)
            <li>
                <a href="{{ route('messages.show', $message->id) }}">{{ $message->subject }}</a>
            </li>
        @endforeach
    </ul>
@endsection
