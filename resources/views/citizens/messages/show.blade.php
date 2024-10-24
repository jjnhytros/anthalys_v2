@extends('layouts.app')

@section('content')
    <h1>{{ $message->subject }}</h1>
    <p>{{ $message->message }}</p>
    <p>Da: {{ $message->sender->name }} A: {{ $message->recipient->name }}</p>
@endsection
