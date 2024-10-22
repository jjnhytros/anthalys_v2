@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Benvenuti ad Anthalys</h1>

        <!-- Sezione di Bilancio del Governo -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h3>Gestione della Città</h3>
                <h1>Bilancio del Governo: {{ number_format($government->cash, 2) }} €</h1>
                <!-- Aumento della Produzione -->
                <form action="{{ route('city.increaseProduction', $city) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Aumenta Produzione Risorse</button>
                </form>

            </div>
        </div>
    </div>

    <!-- Informazioni sulla città -->
    <div class="row">
        <div class="col-md-6">
            <h3>Informazioni sulla città</h3>
            <ul>
                <li><strong>Nome:</strong> {{ $city->name }}</li>
                <li><strong>Popolazione totale:</strong> {{ number_format($city->population) }} abitanti</li>
                <li><strong>Numero di distretti:</strong> {{ $city->districts->count() }}</li>
            </ul>
            <h3>Stato attuale delle risorse nei distretti</h3>
            <div id="resource-container">
                @foreach ($city->districts as $district)
                    <h4>{{ $district->name }}</h4>
                    <ul id="district-{{ $district->id }}">
                        @foreach ($district->resources as $resource)
                            <li id="resource-{{ $resource->id }}">
                                <strong>{{ $resource->name }}:</strong><br />
                                <span class="quantity">{{ number_format($resource->quantity) }}</span>
                                {{ $resource->unit }} disponibili<br />
                                Produzione giornaliera:
                                <span class="daily-production">{{ number_format($resource->daily_production) }}</span>
                                {{ $resource->unit }}
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </div>

        </div>
        <div class="col-md-6">
            <h3>Consumo totale delle risorse</h3>
            <ul>
                <li><strong>Energia totale:</strong> {{ number_format($totalEnergyConsumption) }} kWh</li>
                <li><strong>Acqua totale:</strong> {{ number_format($totalWaterConsumption) }} litri</li>
                <li><strong>Cibo totale:</strong> {{ number_format($totalFoodConsumption) }} tonnellate</li>
            </ul>
            <h2>Storico delle Transazioni</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Importo</th>
                        <th>Descrizione</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td>{{ ucfirst($transaction->type) }}</td>
                            <td>{{ number_format($transaction->amount, 2) }} €</td>
                            <td>{{ $transaction->description }}</td>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Stato delle risorse nei distretti -->
    <div class="row mt-4">


        <!-- Sezione di Infrastrutture e Edifici -->
        <div class="row mt-4">
            @foreach ($city->districts as $district)
                <div class="col-md-6">
                    <h3>{{ $district->name }}</h3>
                    <p>Popolazione: {{ number_format($district->population) }} abitanti</p>

                    <!-- Infrastrutture -->
                    <h4>Infrastrutture</h4>
                    <ul>
                        @foreach ($district->infrastructures as $infrastructure)
                            <li>
                                {{ $infrastructure->name }} (Condizione: {{ $infrastructure->condition * 100 }}%,
                                Efficienza:
                                {{ $infrastructure->efficiency * 100 }}%)
                                @if ($infrastructure->condition < 1)
                                    <!-- Mostra il pulsante solo se la condizione è inferiore al 100% -->
                                    @php
                                        $buttonClass = '';
                                        if ($infrastructure->condition > 0.75) {
                                            $buttonClass = 'btn-success'; // Verde per condizione buona
                                        } elseif ($infrastructure->condition > 0.5) {
                                            $buttonClass = 'btn-warning'; // Giallo per condizione media
                                        } else {
                                            $buttonClass = 'btn-danger'; // Rosso per condizione critica
                                        }
                                    @endphp
                                    <button class="btn {{ $buttonClass }}"
                                        onclick="maintainInfrastructure({{ $infrastructure->id }})">
                                        Esegui Manutenzione
                                    </button>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <!-- Edifici -->
                    <h4>Edifici</h4>
                    <ul>
                        @foreach ($district->buildings as $building)
                            <li>{{ $building->name }} (Tipo: {{ $building->type }}, Piani: {{ $building->floors }},
                                Consumo energetico: {{ $building->energy_consumption }} kWh, Consumo idrico:
                                {{ $building->water_consumption }} litri, Consumo alimentare:
                                {{ $building->food_consumption }} tonnellate)</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // Funzione per aggiornare il bilancio del governo tramite AJAX
        function updateGovernmentBalance() {
            $.ajax({
                url: "{{ route('api.government.balance') }}",
                method: 'GET',
                success: function(data) {
                    $('#government-balance').text(data.balance.toFixed(2) + ' €');
                },
                error: function() {
                    $('#government-balance').text('Errore nel caricamento');
                    alert('Errore nel caricamento del bilancio');
                }
            });
        }

        function applyDeterioration() {
            $.ajax({
                url: "{{ route('infrastructures.apply-deterioration') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Deterioramento applicato.');
                        location.reload(); // Opzionale: puoi aggiornare la pagina o solo le infrastrutture.
                    }
                },
                error: function() {
                    alert('Errore durante l\'applicazione del deterioramento.');
                }
            });
        }

        // Funzione per eseguire la manutenzione delle infrastrutture tramite AJAX
        function maintainInfrastructure(infrastructureId) {
            $.ajax({
                url: `/infrastructures/${infrastructureId}/maintain`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // alert('Manutenzione eseguita con successo!');
                    location.reload(); // Ricarica la pagina per aggiornare i dati
                },
                error: function() {
                    alert('Errore durante la manutenzione dell\'infrastruttura.');
                }
            });
        }

        function updateResources() {
            $.ajax({
                url: "{{ route('api.resources') }}",
                method: 'GET',
                success: function(data) {
                    // Per ogni distretto, aggiorniamo le risorse
                    data.forEach(function(district) {
                        district.resources.forEach(function(resource) {
                            // Aggiorna la quantità e la produzione giornaliera di ciascuna risorsa
                            $('#district-' + district.district + ' #resource-' + resource.name +
                                ' .quantity').text(resource.quantity.toLocaleString());
                            $('#district-' + district.district + ' #resource-' + resource.name +
                                ' .daily-production').text(resource.daily_production
                                .toLocaleString());
                        });
                    });
                },
                error: function() {
                    alert('Errore durante l\'aggiornamento delle risorse.');
                }
            });
        }

        // Aggiorna il bilancio ogni 5 secondi
        setInterval(updateGovernmentBalance, 5000);
        updateGovernmentBalance();
        setInterval(updateResources, 5000);
        updateResources(); // Esegui subito il caricamento iniziale

        // Applica il deterioramento ogni 1 secondo
        setInterval(applyDeterioration, 1000);
    </script>
@endsection
