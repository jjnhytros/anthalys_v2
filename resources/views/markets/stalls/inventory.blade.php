@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Inventario della Bancarella {{ $stall->name }}</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Prodotto</th>
                    <th>Quantità Disponibile</th>
                    <th>Prezzo di Acquisto</th>
                    <th>Prezzo di Vendita</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr class="{{ $product->quantity < 10 ? 'table-danger' : '' }}">
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ athel($product->purchase_price) }}</td>
                        <td>{{ athel($product->price) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
