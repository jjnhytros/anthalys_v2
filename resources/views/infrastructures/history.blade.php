@extends('layouts.app')

@section('content')
    <h1>Storico Manutenzioni per {{ $infrastructure->name }}</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Data Manutenzione</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($maintenanceHistory as $maintenance)
                <tr>
                    <td>{{ $maintenance->maintained_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('districts.index') }}" class="btn btn-outline-dark">Torna ai Distretti</a>
@endsection
