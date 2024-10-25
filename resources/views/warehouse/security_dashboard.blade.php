@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dashboard di Sicurezza del MegaWarehouse</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Tipo di Operazione</th>
                    <th>Prodotto</th>
                    <th>Quantità</th>
                    <th>Data e Ora</th>
                    <th>Dettagli</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($operationLogs as $log)
                    <tr>
                        <td>{{ $log->operation_type }}</td>
                        <td>{{ optional($log->product)->name ?? 'N/A' }}</td>
                        <td>{{ $log->quantity ?? 'N/A' }}</td>
                        <td>{{ $log->operation_time->format('d-m-Y H:i') }}</td>
                        <td>{{ $log->details }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $operationLogs->links() }}
    </div>
@endsection
