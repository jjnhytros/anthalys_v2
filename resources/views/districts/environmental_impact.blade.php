@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Impatto Ambientale del Distretto: {{ $district->name }}</h1>

        <ul class="list-group">
            <li class="list-group-item">
                <strong>Emissioni di CO₂ Totali:</strong> {{ number_format($totalCO2Emissions, 2) }} tonnellate
            </li>
            <li class="list-group-item">
                <strong>Consumo Energetico Totale:</strong> {{ number_format($totalEnergyConsumption, 2) }} kWh
            </li>
            <li class="list-group-item">
                <strong>Consumo Idrico Totale:</strong> {{ number_format($totalWaterConsumption, 2) }} litri
            </li>
            <li class="list-group-item">
                <strong>Impatto sulla Biodiversità:</strong> {{ number_format($totalBiodiversityImpact * 100, 2) }}%
            </li>
        </ul>
    </div>
@endsection
