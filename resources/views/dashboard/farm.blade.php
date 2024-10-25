@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard della Fattoria</h1>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Produzione Totale</h5>
                        <p class="card-text" id="total-production">0</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Efficienza della Distribuzione</h5>
                        <p class="card-text" id="distribution-efficiency">0%</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Numero di Fattorie</h5>
                        <p class="card-text" id="farm-count">0</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Eventi Attivi</h5>
                    <ul id="active-events">
                        <!-- Eventi aggiornati in tempo reale -->
                    </ul>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        function fetchStats() {
            $.ajax({
                url: "{{ route('dashboard.farm.stats') }}",
                method: 'GET',
                success: function(data) {
                    $('#total-production').text(data.total_production);
                    $('#distribution-efficiency').text(data.distribution_efficiency + '%');
                    $('#farm-count').text(data.farm_count);

                    // Mostra gli eventi attivi
                    let eventsHtml = '';
                    data.active_events.forEach(event => {
                        eventsHtml +=
                            `<li>${event.type} - ${event.description} (Gravità: ${event.severity})</li>`;
                    });
                    $('#active-events').html(eventsHtml);
                }
            });
        }

        // Aggiorna le statistiche ogni 5 secondi
        setInterval(fetchStats, 5000);

        // Carica le statistiche inizialmente
        fetchStats();
    </script>
@endsection
