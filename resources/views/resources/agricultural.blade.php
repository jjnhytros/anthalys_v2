@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Monitoraggio delle Risorse Agricole</h1>

        @foreach ($districts as $district)
            <h3>{{ $district->name }}</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Risorsa</th>
                        <th>Produzione Giornaliera</th>
                        <th>Consumo Giornaliero</th>
                        <th>Quantità Disponibile</th>
                        <th>Tecniche Agricole Attive</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($district->resources as $resource)
                        <tr>
                            <td>{{ $resource->name }}</td>
                            <td>{{ $resource->daily_production }} {{ $resource->unit }}</td>
                            <td>{{ $resource->daily_consumption }} {{ $resource->unit }}</td>
                            <td>{{ $resource->quantity }} {{ $resource->unit }}</td>
                            <td>
                                @foreach ($resource->agriculturalTechniques as $technique)
                                    <span>{{ $technique->name }} (Efficienza:
                                        +{{ $technique->efficiency_boost * 100 }}%)</span><br>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
@endsection
