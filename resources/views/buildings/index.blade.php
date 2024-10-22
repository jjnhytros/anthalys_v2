@extends('layouts.app')

@section('content')
    <h1>Edifici nel distretto {{ $district->name }}</h1>
    <ul class="list-group">
        @foreach ($buildings as $building)
            <li class="list-group-item">
                <strong>{{ $building->name }}</strong> (Tipo: {{ $building->type }}, Altezza: {{ $building->height }} m,
                Piani: {{ $building->floors }})
            </li>
        @endforeach
    </ul>
    <a href="{{ route('districts.buildings.create', $district) }}" class="btn btn-primary mt-4">Aggiungi un nuovo edificio</a>
@endsection
