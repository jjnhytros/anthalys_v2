@extends('layouts.app')

@section('content')
    <h1>Monitoraggio delle risorse per il distretto {{ $district->name }}</h1>

    <h2>Consumo complessivo:</h2>
    <ul>
        <li>Energia: {{ $totalEnergyConsumption }} kWh</li>
        <li>Acqua: {{ $totalWaterConsumption }} litri</li>
        <li>Cibo: {{ $totalFoodConsumption }} tonnellate</li>
    </ul>

    <h2>Distribuzione delle risorse:</h2>
    <ul>
        <li>Energia distribuita: {{ $energyDistributed }} kWh</li>
        <li>Acqua distribuita: {{ $waterDistributed }} litri</li>
        <li>Cibo distribuito: {{ $foodDistributed }} tonnellate</li>
    </ul>

    <a href="{{ route('cities.districts.index') }}" class="btn btn-primary mt-4">Torna ai distretti</a>
@endsection
