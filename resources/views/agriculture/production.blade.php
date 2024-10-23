@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Monitoraggio della Produzione Agricola</h1>

        <h2>Stagione attuale</h2>
        <p>Stagione: <strong>{{ $currentSeason->name }}</strong></p>
        <p>Impatto sulla produzione: <strong>{{ $currentSeason->impact_factor * 100 }}%</strong></p>

        <h2>Risorse Agricole nei Distretti</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Distretto</th>
                    <th>Cibo Disponibile</th>
                    <th>Acqua Consumo</th>
                    <th>Energia Consumo</th>
                    <th>Produzione Giornaliera di Cibo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($districts as $district)
                    <tr>
                        <td>{{ $district->name }}</td>
                        <td>{{ $district->resources->where('name', 'Cibo')->first()->quantity }} Tonnes</td>
                        <td>{{ $district->resources->where('name', 'Acqua')->first()->consumed }} Litri</td>
                        <td>{{ $district->resources->where('name', 'Energia')->first()->consumed }} kWh</td>
                        <td>{{ $district->resources->where('name', 'Cibo')->first()->daily_production }} Tonnes</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
