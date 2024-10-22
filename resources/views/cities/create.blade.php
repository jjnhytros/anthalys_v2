@extends('layouts.app')

@section('content')
    <h1>Aggiungi una nuova città</h1>

    <form action="{{ route('cities.store') }}" method="POST">
        @csrf
        <label for="name">Nome:</label>
        <input type="text" name="name" id="name" required><br>

        <label for="latitude">Latitudine:</label>
        <input type="text" name="latitude" id="latitude" required><br>

        <label for="longitude">Longitudine:</label>
        <input type="text" name="longitude" id="longitude" required><br>

        <label for="population">Popolazione:</label>
        <input type="number" name="population" id="population"><br>

        <label for="climate">Clima:</label>
        <input type="text" name="climate" id="climate"><br>

        <button type="submit">Salva</button>
    </form>
@endsection
