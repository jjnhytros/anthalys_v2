@extends('layouts.app')

@section('content')
    <h1>Opportunità di Lavoro</h1>
    <ul>
        @foreach ($occupations as $occupation)
            <li>
                <a href="{{ route('employment.show', $occupation->id) }}">{{ $occupation->title }}</a> - Salario:
                {{ $occupation->salary }} AA
            </li>
        @endforeach
    </ul>
@endsection
