@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Storico Ordini</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Prodotto</th>
                    <th>Quantità</th>
                    <th>Status</th>
                    <th>Confermato il</th>
                    <th>Annullato il</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->product->name }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->confirmed_at ?? 'Non confermato' }}</td>
                        <td>{{ $order->canceled_at ?? 'Non annullato' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
