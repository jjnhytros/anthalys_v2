@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Transazioni del Magazzino</h1>
        <a href="{{ route('warehouse.transactions.create') }}" class="btn btn-primary">Aggiungi Transazione</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Tipo di Transazione</th>
                    <th>Quantità</th>
                    <th>ID Fornitore</th>
                    <th>ID Prodotto</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->transaction_type }}</td>
                        <td>{{ $transaction->quantity }}</td>
                        <td>{{ $transaction->supplier_id }}</td>
                        <td>{{ $transaction->product_id }}</td>
                        <td>
                            <a href="{{ route('warehouse.transactions.show', $transaction->id) }}"
                                class="btn btn-info">Visualizza</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
