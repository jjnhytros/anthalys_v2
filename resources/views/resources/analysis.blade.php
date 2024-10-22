@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Analisi delle Risorse</h1>

        <h2>Totali Cittadini</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Risorsa</th>
                    <th>Quantità Totale</th>
                    <th>Produzione Totale (Giornaliera)</th>
                    <th>Consumo Totale (Giornaliero)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($aggregatedResources as $resourceName => $resourceData)
                    <tr>
                        <td>{{ $resourceName }}</td>
                        <td>{{ number_format($resourceData['total_quantity']) }}</td>
                        <td>{{ number_format($resourceData['total_produced']) }}</td>
                        <td>{{ number_format($resourceData['total_consumed']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Surplus o Deficit nei Distretti</h2>
        @foreach ($districtAnalysis as $district)
            <h3>{{ $district['district_name'] }}</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Risorsa</th>
                        <th>Surplus/Deficit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($district['resources'] as $resource)
                        <tr>
                            <td>{{ $resource['name'] }}</td>
                            <td>{{ $resource['surplus_or_deficit'] >= 0 ? 'Surplus: ' : 'Deficit: ' }}
                                {{ number_format(abs($resource['surplus_or_deficit'])) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
@endsection
