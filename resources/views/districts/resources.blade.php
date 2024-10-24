@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Monitoraggio delle Risorse per i Distretti</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome del Distretto</th>
                    <th>Popolazione</th>
                    <th>Energia Disponibile</th>
                    <th>Acqua Disponibile</th>
                    <th>Cibo Disponibile</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($districts as $district)
                    <tr>
                        <td>{{ $district->name }}</td>
                        <td>{{ $district->population }}</td>
                        <td>{{ $district->energy }}</td>
                        <td>{{ $district->water }}</td>
                        <td>{{ $district->food }}</td>
                        <td>
                            <a href="{{ route('districts.resources.transfer', $district->id) }}" class="btn btn-primary">
                                Trasferisci Risorse
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
