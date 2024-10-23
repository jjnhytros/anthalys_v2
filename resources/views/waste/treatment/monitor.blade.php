@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Monitoraggio Risorse Generate dai Rifiuti Trattati</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Risorsa</th>
                    <th>Quantità Totale Generata</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resourcesGenerated as $resource)
                    <tr>
                        <td>{{ $resource->resource }}</td>
                        <td>{{ number_format($resource->total_generated, 2) }} kg</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
