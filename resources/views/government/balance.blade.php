@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Bilancio del Governo</h1>
        <h2>Saldo attuale: <span id="government-cash">{{ number_format($government->cash, 2) }} AA</span></h2>
    </div>
@endsection

@section('scripts')
    <script>
        // Funzione per aggiornare dinamicamente il bilancio del governo
        function updateGovernmentCash() {
            $.ajax({
                url: "{{ route('api.government.balance') }}",
                method: 'GET',
                success: function(data) {
                    $('#government-cash').text(data.balance.toFixed(2) + ' AA');
                }
            });
        }

        // Impostiamo un intervallo per aggiornare il bilancio ogni 5 secondi
        setInterval(updateGovernmentCash, 5000);
    </script>
@endsection
