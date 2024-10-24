@extends('layouts.app')

@section('content')
    <h1>Cittadini</h1>
    <ul>
        @foreach ($citizens as $citizen)
            <li>
                {{ $citizen->name }} - Punti Bonus: {{ $citizen->bonus_points }}
            </li>
        @endforeach
    </ul>
@endsection
