@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Punti di Riciclo di {{ $citizen->name }}</h1>
        <p>Punti totali accumulati: {{ $citizen->recycling_points }}</p>

        <h2>Aggiungi Punti</h2>
        <form action="{{ route('recycling.addPoints', $citizen->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="waste_type_id">Tipo di Rifiuto:</label>
                <select name="waste_type_id" id="waste_type_id" class="form-control">
                    @foreach ($wasteTypes as $wasteType)
                        <option value="{{ $wasteType->id }}">{{ $wasteType->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantità (kg):</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi Punti</button>
        </form>
    </div>
@endsection
