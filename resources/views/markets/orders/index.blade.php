@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>I miei ordini</h1>
        <ul>
            @foreach ($orders as $order)
                <li>
                    Prodotto: {{ $order->product->name }} - Quantità: {{ $order->quantity }} - Stato: {{ $order->status }}

                    <!-- Pulsanti per confermare o annullare l'ordine -->
                    @if (!$order->isConfirmed() && !$order->isCanceled())
                        <form action="{{ route('orders.confirm', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Conferma</button>
                        </form>
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Annulla</button>
                        </form>
                    @endif

                    <!-- Pulsante per lasciare una recensione -->
                    @if ($order->isConfirmed())
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                            data-target="#reviewModal-{{ $order->id }}">
                            Lascia una recensione
                        </button>

                        <!-- Modal per la recensione -->
                        <div class="modal fade" id="reviewModal-{{ $order->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Lascia una recensione</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('reviews.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $order->product->id }}">
                                            <input type="hidden" name="citizen_id" value="{{ auth()->id() }}">
                                            <div class="form-group">
                                                <label for="rating">Valutazione (1-6):</label>
                                                <select name="rating" class="form-control" required>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                    <option value="6">6</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="feedback">Feedback:</label>
                                                <textarea name="feedback" class="form-control" rows="3"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Invia recensione</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endsection
