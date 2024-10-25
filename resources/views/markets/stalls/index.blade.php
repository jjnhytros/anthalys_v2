@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Mercato: {{ $market->name }}</h1>
        <div class="row">
            @foreach ($stalls as $stall)
                <div class="col-md-4">
                    <div class="mb-4 card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $stall->name }}</h5>
                            <p class="card-text">{{ $stall->description }}</p>
                            <p class="card-text"><strong>Proprietario:</strong> {{ $stall->owner->name }}</p>
                            <p class="card-text"><strong>Prodotti in vendita:</strong></p>
                            <ul>
                                @foreach ($stall->products as $product)
                                    <li>
                                        {{ $product->name }} - {{ $product->price }} AA (Quantità: {{ $product->quantity }})
                                        <p><strong>Recensioni:</strong></p>
                                        <ul>
                                            @foreach ($product->reviews as $review)
                                                <li>
                                                    Valutazione: {{ $review->rating }} stelle<br>
                                                    Feedback: {{ $review->feedback }}
                                                </li>
                                            @endforeach
                                        </ul>
                                        <form action="{{ route('orders.store') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="number" name="quantity" min="1"
                                                max="{{ $product->quantity }}" required style="width: 60px;">
                                            <button type="submit" class="btn btn-sm btn-primary">Acquista</button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
