@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Prodotti a basso stock</h1>
        <ul>
            @foreach ($lowStockItems as $item)
                <li>
                    Prodotto: {{ $item->product_type }} - Quantità: {{ $item->quantity }}
                    <form action="{{ route('warehouse.initiateManualRestock', $item) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Rifornisci manualmente</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
