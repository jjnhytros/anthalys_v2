@extends('layouts.app')

@section('content')
    <h1>Storico degli Scambi di Risorse</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Distretto di Origine</th>
                <th>Distretto di Destinazione</th>
                <th>Risorsa</th>
                <th>Quantità</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transfers as $transfer)
                <tr>
                    <td>{{ $transfer->sourceDistrict->name }}</td>
                    <td>{{ $transfer->targetDistrict->name }}</td>
                    <td>{{ $transfer->resource_name }}</td>
                    <td>{{ $transfer->quantity }}</td>
                    <td>{{ $transfer->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
