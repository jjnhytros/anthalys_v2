@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tipi di Rifiuti e Contenitori</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Nome del Rifiuto</th>
                    <th>Colore del Contenitore</th>
                    <th>Descrizione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($wasteTypes as $wasteType)
                    <tr>
                        <td>{{ $wasteType->name }}</td>
                        <td>{{ $wasteType->container_color }}</td>
                        <td>{{ $wasteType->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
