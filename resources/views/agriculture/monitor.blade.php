@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Monitoraggio della Produzione Agricola per il Distretto {{ $district->name }}</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Risorsa</th>
                    <th>Quantità Disponibile</th>
                    <th>Produzione Giornaliera</th>
                    <th>Consumo Giornaliero</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resources as $resource)
                    <tr>
                        <td>{{ $resource->name }}</td>
                        <td>{{ $resource->quantity }}</td>
                        <td>{{ $resource->daily_production }}</td>
                        <td>{{ $resource->daily_consumption }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
