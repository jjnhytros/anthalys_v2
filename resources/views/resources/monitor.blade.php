@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Monitoraggio delle Risorse per {{ $district->name }}</h1>

        <table class="table table-bordered" id="resources-table">
            <thead>
                <tr>
                    <th>Risorsa</th>
                    <th>Quantità Disponibile</th>
                    <th>Produzione Giornaliera</th>
                    <th>Consumo Giornaliero</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resources as $resource)
                    <tr id="resource-{{ $resource->id }}">
                        <td>{{ $resource->name }}</td>
                        <td class="quantity">{{ $resource->quantity }} {{ $resource->unit }}</td>
                        <td class="produced">{{ $resource->produced }} {{ $resource->unit }}</td>
                        <td class="consumed">{{ $resource->consumed }} {{ $resource->unit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
                    });
                }
            });
        }

        // Imposta un timer per aggiornare i dati ogni 5 secondi
        setInterval(updateResources, 5000);
    </script>
@endsection
