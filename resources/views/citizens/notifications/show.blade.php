@extends('layouts.app')

@section('content')
    <h1>{{ $notification->subject }}</h1>
    <p>{{ $notification->message }}</p>
    <p>Da: {{ $notification->sender->name }} A: {{ $notification->recipient->name }}</p>
@endsection
