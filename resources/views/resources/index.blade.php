@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Risorse nel distretto {{ $district->name }}</h1>

        <ul class="list-group mb-4">
            @foreach ($resources as $resource)
                <li class="list-group-item">
                    <h5><strong>{{ $resource->name }}</strong></h5>
                    <p>
                        <strong>Quantità disponibile:</strong> {{ $resource->quantity }} {{ $resource->unit }}<br>
                        <strong>Prodotta:</strong> {{ $resource->produced }} {{ $resource->unit }} al giorno<br>
                        <strong>Consumo:</strong> {{ $resource->consumed }} {{ $resource->unit }} al giorno
                    </p>
                </li>
            @endforeach
        </ul>
        <a href="{{ route('resources.analysis') }}" class="btn btn-info mt-4">Visualizza Analisi delle Risorse</a>

        <!-- Link per aggiungere una nuova risorsa -->
        <a href="{{ route('districts.resources.create', $district) }}" class="btn btn-primary">Aggiungi una nuova risorsa</a>

        <!-- Link per il trasferimento di risorse -->
        <a href="{{ route('resource.transfer') }}" class="btn btn-secondary ml-3">Trasferisci risorse</a>

        <!-- Link per tornare alla pagina dei distretti -->
        <a href="{{ route('districts.index') }}" class="btn btn-outline-dark mt-4">Torna ai distretti</a>
    </div>
@endsection
