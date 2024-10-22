@extends('layouts.app')

@section('content')
    <h1>Quartieri di {{ $city->name }}</h1>
    <ul class="list-group">
        @foreach ($districts as $district)
            <li class="list-group-item">
                <strong>{{ $district->name }}</strong> (Popolazione: {{ $district->population }}), Area:
                {{ $district->area }} km²
            </li>
        @endforeach
    </ul>
    <a href="{{ route('cities.districts.create', $city) }}" class="btn btn-primary mt-4">Aggiungi un nuovo quartiere</a>
@endsection
