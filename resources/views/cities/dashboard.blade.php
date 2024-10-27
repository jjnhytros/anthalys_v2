@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Dashboard di Anthalys</h1>

        <!-- Sezione Grafici -->
        <div class="row">
            <div class="col-md-6">
                <h3>Produzione Energetica per Distretto</h3>
                <canvas id="energyProductionChart"></canvas>
            </div>
            <div class="col-md-6">
                <h3>Tasso di Riciclo nel Tempo</h3>
                <canvas id="recyclingRateChart"></canvas>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <h3>Distribuzione delle Strutture Culturali</h3>
                <canvas id="culturalDistributionChart"></canvas>
            </div>
            <div class="col-md-6">
                <h3>Livello di Sicurezza per Distretto</h3>
                <canvas id="safetyLevelChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Esempio di grafico a barre per Produzione Energetica
        var energyProductionChartCtx = document.getElementById('energyProductionChart').getContext('2d');
        var energyProductionChart = new Chart(energyProductionChartCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($districtNames) !!},
                datasets: [{
                    label: 'Produzione Energetica',
                    data: {!! json_encode($energyProductionData) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                }]
            },
            options: {
                responsive: true
            }
        });

        // Aggiungere gli altri grafici allo stesso modo
    </script>
@endsection
