@extends('layouts.app')

@section('content')
    <h1>Infrastrutture nel distretto {{ $district->name }}</h1>
    <ul class="list-group">
        @foreach ($infrastructures as $infrastructure)
            <li class="list-group-item">
                <strong>{{ $infrastructure->name }}</strong> (Tipo: {{ $infrastructure->type }}, Lunghezza:
                {{ $infrastructure->length }} km)
            </li>
        @endforeach
    </ul>
    <a href="{{ route('districts.infrastructures.create', $district) }}" class="btn btn-primary mt-4">Aggiungi una nuova
        infrastruttura</a>
@endsection
