@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Progresso del Riciclo di {{ $citizen->name }}</h1>
        <p>Punti totali accumulati: {{ $citizen->recycling_points }}</p>

        <h2>Progresso del Riciclo</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo di Rifiuto</th>
                    <th>Quantità Riciclata (kg)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recyclingProgress as $progress)
                    <tr>
                        <td>{{ $progress->created_at->format('d/m/Y') }}</td>
                        <td>{{ $progress->wasteType->name }}</td>
                        <td>{{ $progress->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
