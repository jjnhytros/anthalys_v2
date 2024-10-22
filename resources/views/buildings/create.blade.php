@extends('layouts.app')

@section('content')
    <h1>Aggiungi un nuovo edificio nel distretto {{ $district->name }}</h1>

    <form action="{{ route('districts.buildings.store', $district) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nome:</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Tipo:</label>
            <input type="text" name="type" id="type" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="floors" class="form-label">Numero di piani:</label>
            <input type="number" name="floors" id="floors" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="height" class="form-label">Altezza (in metri):</label>
            <input type="text" name="height" id="height" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Salva</button>
    </form>
@endsection
