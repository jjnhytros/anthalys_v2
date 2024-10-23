@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Risorse Generate dal Trattamento dei Rifiuti</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Risorsa</th>
                    <th>Quantità Totale Generata</th>
                    <th>Produzione Ottimizzata</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resourcesGenerated as $resource)
                    <tr>
                        <td>{{ $resource['resource'] }}</td>
                        <td>{{ number_format($resource['quantity'], 2) }} kg</td>
                        <td>{{ number_format($resource['optimized_production'], 2) }} kg</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
