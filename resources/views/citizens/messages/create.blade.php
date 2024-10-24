@extends('layouts.app')

@section('content')
    <h1>Nuovo Messaggio</h1>

    <form action="{{ route('messages.store') }}" method="POST">
        @csrf
        <label for="recipient_id">Destinatario:</label>
        <select name="recipient_id" id="recipient_id">
            @foreach ($citizens as $citizen)
                <option value="{{ $citizen->id }}">{{ $citizen->name }}</option>
            @endforeach
        </select>

        <label for="subject">Oggetto:</label>
        <input type="text" name="subject" id="subject">

        <label for="message">Messaggio:</label>
        <textarea name="message" id="message"></textarea>

        <button type="submit">Invia Messaggio</button>
    </form>
@endsection
