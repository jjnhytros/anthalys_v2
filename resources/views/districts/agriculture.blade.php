@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tecniche Agricole nel Distretto di {{ $district->name }}</h1>

        <ul>
            @foreach ($district->agriculturalTechniques as $technique)
                <li>
                    <strong>{{ $technique->name }}</strong> - {{ $technique->description }}
                    (Efficienza: {{ $technique->efficiency_boost }}, Sostenibilità: {{ $technique->sustainability_level }})
                </li>
            @endforeach
        </ul>
    </div>
@endsection
