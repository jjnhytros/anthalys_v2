@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Monitoraggio delle Emergenze</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Risorsa</th>
                    <th>Soglia</th>
                    <th>Quantità di Riserva</th>
                    <th>Limitazione Attiva</th>
                    <th>Stato</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($emergencyPlans as $plan)
                    <tr>
                        <td>{{ $plan->resource_name }}</td>
                        <td>{{ $plan->threshold }}</td>
                        <td>{{ $plan->reserve_quantity }}</td>
                        <td>{{ $plan->limit_usage ? 'Sì' : 'No' }}</td>
                        <td>{{ $plan->is_active ? 'Attivo' : 'Inattivo' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="row">
            <div class="col-md-6">
                <h1>Monitoraggio in Tempo Reale di Prezzi e Disponibilità</h1>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Risorsa</th>
                            <th>Prezzo Attuale</th>
                            <th>Disponibilità</th>
                            <th>Ultimo Aggiornamento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resources as $resource)
                            <tr>
                                <td>{{ $resource->name }}</td>
                                <td>{{ $resource->price }}</td>
                                <td>{{ $resource->availability }}</td>
                                <td>{{ $resource->updated_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <h1>Monitoraggio delle Risorse per {{ $district->name }}</h1>

                <table class="table table-bordered" id="resources-table">
                    <thead>
                        <tr>
                            <th>Risorsa</th>
                            <th>Quantità Disponibile</th>
                            <th>Produzione Giornaliera</th>
                            <th>Consumo Giornaliero</th>
                            <th>Risorse Risparmiate (Riciclo)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resources as $resource)
                            <tr id="resource-{{ $resource->id }}">
                                <td>{{ $resource->name }}</td>
                                <td class="quantity">{{ $resource->quantity }} {{ $resource->unit }}</td>
                                <td class="produced">{{ $resource->produced }} {{ $resource->unit }}</td>
                                <td class="consumed">{{ $resource->consumed }} {{ $resource->unit }}</td>
                                <td class="saved">{{ $resource->saved ?? 0 }} {{ $resource->unit }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Funzione per aggiornare le risorse
        function updateResources() {
            const districtId = {{ $district->id }};

            $.ajax({
                url: `/api/districts/${districtId}/resources`,
                method: 'GET',
                success: function(data) {
                    data.forEach(resource => {
                        // Aggiorna le righe della tabella
                        const resourceRow = $(`#resource-${resource.id}`);
                        resourceRow.find('.quantity').text(resource.quantity + ' ' + resource.unit);
                        resourceRow.find('.produced').text(resource.produced + ' ' + resource.unit);
                        resourceRow.find('.consumed').text(resource.consumed + ' ' + resource.unit);
                        resourceRow.find('.saved').text((resource.saved || 0) + ' ' + resource.unit);
                    });
                }
            });
        }

        // Imposta un timer per aggiornare i dati ogni 5 secondi
        setInterval(updateResources, 5000);
    </script>
@endsection
