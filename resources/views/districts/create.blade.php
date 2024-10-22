@extends('layouts.app')

@section('content')
    <h1>Aggiungi un nuovo quartiere a {{ $city->name }}</h1>

    <form action="{{ route('cities.districts.store', $city) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="resource_requirement">Risorse Richieste (JSON formato: {"Energia": 1000, "Acqua": 500})</label>
            <input type="text" class="form-control" id="resource_requirement" name="resource_requirement" required>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Nome:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="population" class="form-label">Popolazione:</label>
            <input type="number" name="population" id="population" class="form-control">
        </div>

        <div class="mb-3">
            <label for="area" class="form-label">Area (in km²):</label>
            <input type="text" name="area" id="area" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Descrizione:</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Salva</button>
    </form>
@endsection
