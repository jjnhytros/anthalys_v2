@extends('layouts.app')

@section('content')
    <h1>Migrazioni tra Distretti</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Distretto di Origine</th>
                <th>Distretto di Destinazione</th>
                <th>Numero di Migranti</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($migrations as $migration)
                <tr>
                    <td>{{ $migration->fromDistrict->name }}</td>
                    <td>{{ $migration->toDistrict->name }}</td>
                    <td>{{ $migration->migrants_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Distretti con Problemi</h2>
    <ul>
        @foreach ($problematicDistricts as $district)
            <li>{{ $district->name }}: Scarso livello di risorse o infrastrutture</li>
        @endforeach
    </ul>
@endsection
