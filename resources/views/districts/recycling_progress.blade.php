@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Progresso del Riciclo nel Distretto: {{ $district->name }}</h1>

        @if ($recyclingGoals && $recyclingGoals->isNotEmpty())
            <table class="table">
                <thead>
                    <tr>
                        <th>Risorsa</th>
                        <th>Obiettivo</th>
                        <th>Riciclato</th>
                        <th>Progresso (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recyclingGoals as $goal)
                        <tr>
                            <td>{{ $goal->resource_type }}</td>
                            <td>{{ $goal->target_quantity }}</td>
                            <td>{{ $goal->current_quantity }}</td>
                            <td>{{ number_format(($goal->current_quantity / $goal->target_quantity) * 100, 2) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Nessun obiettivo di riciclo disponibile per questo distretto.</p>
        @endif
    </div>
@endsection
