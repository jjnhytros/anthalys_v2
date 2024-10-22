@extends('layouts.app')

@section('content')
    <h1>Città</h1>
    <ul>
        @foreach ($cities as $city)
            <li>{{ $city->name }} (Popolazione: {{ $city->population }})</li>
        @endforeach
    </ul>
    <a href="{{ route('cities.create') }}">Aggiungi una nuova città</a>
@endsection
