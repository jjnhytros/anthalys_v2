<!-- resources/views/lessons/index.blade.php -->
@extends('layouts.main')

@section('content')
    <h1>Lezioni di Anthaliano</h1>
    <a href="{{ route('lessons.create') }}" class="btn btn-primary">Crea Nuova Lezione</a>

    @foreach ($lessons as $lesson)
        <div>
            <h2>{{ $lesson->title }}</h2>
            <p>{{ $lesson->description }}</p>
            <a href="{{ route('lessons.show', $lesson->id) }}">Leggi di più</a>
        </div>
    @endforeach
@endsection
