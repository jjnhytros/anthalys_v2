@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Benvenuti ad Anthalys</h1>

        <h2>Informazioni sulla città</h2>
        <ul>
            <li><strong>Nome:</strong> {{ $city->name }}</li>
            <li><strong>Popolazione totale:</strong> {{ number_format($city->population) }} abitanti</li>
            <li><strong>Numero di distretti:</strong> {{ $city->districts->count() }}</li>
        </ul>

        <h2>Consumo totale delle risorse</h2>
        <ul>
            <li><strong>Energia totale:</strong> {{ number_format($totalEnergyConsumption) }} kWh</li>
            <li><strong>Acqua totale:</strong> {{ number_format($totalWaterConsumption) }} litri</li>
            <li><strong>Cibo totale:</strong> {{ number_format($totalFoodConsumption) }} tonnellate</li>
        </ul>

        <h2>Stato attuale delle risorse nei distretti</h2>
        @foreach ($city->districts as $district)
            <h3>{{ $district->name }}</h3>

            <ul>
                @foreach ($district->resources as $resource)
                    <li><strong>{{ $resource->name }}:</strong> {{ number_format($resource->quantity) }}
                        {{ $resource->unit }} disponibili, Produzione giornaliera:
                        {{ number_format($resource->daily_production) }} {{ $resource->unit }}</li>
                @endforeach
            </ul>
        @endforeach
    </div>

    <h2>Distretto e infrastrutture</h2>
    @foreach ($city->districts as $district)
        <h3>{{ $district->name }}</h3>
        <p>Popolazione: {{ number_format($district->population) }} abitanti</p>

        <h4>Infrastrutture</h4>
        <ul>
            @foreach ($district->infrastructures as $infrastructure)
                <li>
                    {{ $infrastructure->name }}
                    (Condizione: {{ $infrastructure->condition * 100 }}%, Efficienza:
                    {{ $infrastructure->efficiency * 100 }}%)
                    @if ($infrastructure->condition < 1)
                        <!-- Mostra il pulsante solo se la condizione è inferiore al 100% -->
                        @php
                            // Determina il colore del pulsante in base alla condizione
                            $buttonClass = '';
                            if ($infrastructure->condition > 0.75) {
                                $buttonClass = 'btn-success'; // Verde per condizione buona
                            } elseif ($infrastructure->condition > 0.5) {
                                $buttonClass = 'btn-warning'; // Giallo per condizione media
                            } else {
                                $buttonClass = 'btn-danger'; // Rosso per condizione critica
                            }
                        @endphp
                        <form action="{{ route('infrastructures.maintain', $infrastructure) }}" method="POST">
                            @csrf
                            <button class="btn {{ $buttonClass }}">
                                Esegui Manutenzione
                            </button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>

        <h4>Edifici</h4>
        <ul>
            @foreach ($district->buildings as $building)
                <li>{{ $building->name }} (Tipo: {{ $building->type }}, Piani: {{ $building->floors }}, Consumo
                    energetico: {{ $building->energy_consumption }} kWh, Consumo idrico:
                    {{ $building->water_consumption }} litri, Consumo alimentare: {{ $building->food_consumption }}
                    tonnellate)</li>
            @endforeach
        </ul>
    @endforeach
    </div>
@endsection
