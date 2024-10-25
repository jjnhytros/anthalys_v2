@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Pagamenti ai Fornitori Locali</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Fornitore</th>
                    <th>Prodotto</th>
                    <th>Importo</th>
                    <th>Data del Pagamento</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->supplier->name }}</td>
                        <td>{{ $payment->product->name }}</td>
                        <td>{{ $payment->amount }}</td>
                        <td>{{ $payment->payment_date->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $payments->links() }}
    </div>
@endsection
