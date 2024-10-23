@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Produzione di Bevande Alcoliche</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Fase di Produzione</th>
                    <th>Quantità</th>
                    <th>Qualità</th>
                    <th>Impatto Ambientale</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($alcoholics as $alcoholic)
                    <tr>
                        <td>{{ $alcoholic->name }}</td>
                        <td>{{ $alcoholic->type }}</td>
                        <td>{{ $alcoholic->production_phase }}</td>
                        <td>{{ $alcoholic->quantity }}</td>
                        <td>{{ $alcoholic->quality }}</td>
                        <td>{{ $alcoholic->environmental_impact }}</td>
                        <td>
                            <a href="{{ route('productions.alcoholics.show', $alcoholic->id) }}"
                                class="btn btn-primary">Visualizza</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
