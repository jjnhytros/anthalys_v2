@extends('layouts.app')

@section('content')
    <h1>{{ $occupation->title }}</h1>
    <p>{{ $occupation->description }}</p>
    <p>Salario: {{ $occupation->salary }} AA</p>
    <p>Livello di stress: {{ $occupation->stress_level }}</p>

    <form action="{{ route('employment.apply', $occupation->id) }}" method="POST">
        @csrf
        <input type="hidden" name="citizen_id" value="{{ auth()->user()->citizen->id }}">
        <button type="submit" class="btn btn-primary">Candidati per questa posizione</button>
    </form>
@endsection
