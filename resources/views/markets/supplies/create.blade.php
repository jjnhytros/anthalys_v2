@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Rifornisci i Prodotti per la Bancarella {{ $stall->name }}</h1>
        <form action="{{ route('supplies.store', $stall) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="product_id">Prodotto:</label>
                <select name="product_id" id="product_id" class="form-control" required>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} (Disponibili: {{ $product->quantity }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="purchase_price">Prezzo di Acquisto:</label>
                <input type="number" step="0.01" name="purchase_price" id="purchase_price" class="form-control"
                    required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantità da Rifornire:</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required min="1">
            </div>
            <button type="submit" class="btn btn-primary">Rifornisci</button>
        </form>
    </div>
@endsection
