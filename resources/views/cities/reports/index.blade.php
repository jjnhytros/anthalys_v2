@extends('layouts.app')

@section('content')
    <h1>Report di Produzione</h1>

    <table>
        <thead>
            <tr>
                <th>Fattoria</th>
                <th>Resa Totale delle Colture</th>
                <th>Resa Totale degli Animali</th>
                <th>Resa Coltivazione Verticale</th>
                <th>Periodo</th>
                <th>Tipo</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->farm->name }}</td>
                    <td>{{ $report->total_crop_yield }}</td>
                    <td>{{ $report->total_animal_yield }}</td>
                    <td>{{ $report->vertical_farming_yield }}</td>
                    <td>{{ $report->report_period }}</td>
                    <td>{{ $report->type }}</td>
                    <td><a href="{{ route('reports.show', $report->id) }}">Visualizza</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
