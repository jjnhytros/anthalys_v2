@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard MegaWarehouse</h1>
        <div class="row">
            <div class="col-md-6">
                <h3>Consumo Energetico</h3>
                <p>Media Mensile: {{ $averageEnergyUsage }} kWh</p>
            </div>
            <div class="col-md-6">
                <h3>Ordini Riciclabili</h3>
                <p>Percentuale di ordini riciclabili: {{ $recyclableOrderPercentage }}%</p>
            </div>
        </div>
        <div class="mt-4 row">
            <div class="col-md-6">
                <h3>Ricavi Totali</h3>
                <p>Totale delle vendite: {{ $totalRevenue }} AA</p>
            </div>
            <div class="col-md-6">
                <h3>Statistiche dei Rifiuti</h3>
                <p>Rifiuti Generati (kg): {{ $totalWasteGenerated }}</p>
            </div>
        </div>
    </div>

    <h2>Statistiche di Sostenibilità</h2>
    <ul>
        <li>Ordini con Imballaggi Riciclabili: {{ $recyclableOrders }}</li>
        <li>Consumo Energetico Totale: {{ $totalEnergyUsage }} kWh</li>
    </ul>

    <h2>Statistiche di Inventario</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Piano</th>
                <th>Tipo di Prodotto</th>
                <th>Quantità Disponibile</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->floor }}</td>
                    <td>{{ $product->product_type }}</td>
                    <td>{{ $product->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
@endsection
