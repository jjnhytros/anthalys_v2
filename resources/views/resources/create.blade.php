@extends('layouts.app')

@section('content')
    <h1>Aggiungi una nuova risorsa nel distretto {{ $district->name }}</h1>

    <form action="{{ route('districts.resources.store', $district) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome della risorsa:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantità:</label>
            <input type="text" name="quantity" id="quantity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="unit" class="form-label">Unità di misura:</label>
            <input type="text" name="unit" id="unit" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Salva</button>
    </form>
@endsection
