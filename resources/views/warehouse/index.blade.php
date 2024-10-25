@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>MegaStore Sotterraneo</h1>
        <p>Piano 0: Uffici (gestiti da personale umano).</p>
        <p>Piani da -1 a -144: Stoccaggio di prodotti.</p>

        <h2>Prodotti disponibili</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Piano</th>
                    <th>Tipo di Prodotto</th>
                    <th>Quantità Disponibile</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->floor }}</td>
                        <td>{{ $product->type }}</td>
                        <td>{{ $product->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
