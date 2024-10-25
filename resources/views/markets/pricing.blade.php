@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Andamento dei Prezzi del Mercato Locale</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Prodotto</th>
                    <th>Quantità Disponibile</th>
                    <th>Prezzo di Acquisto</th>
                    <th>Prezzo di Vendita</th>
                    <th>Domanda</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->purchase_price }} AA</td>
                        <td>{{ $product->price }} AA</td>
                        <td>{{ $product->demand }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
