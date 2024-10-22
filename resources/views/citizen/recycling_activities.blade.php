@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Attività di Riciclo</h1>

        @if ($recyclingActivities->isEmpty())
            <p>Non hai registrato alcuna attività di riciclo.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Tipo di Risorsa</th>
                        <th>Quantità</th>
                        <th>Bonus Ricevuto</th>
                        <th>Data del Riciclo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recyclingActivities as $activity)
                        <tr>
                            <td>{{ $activity->resource_type }}</td>
                            <td>{{ $activity->quantity }}</td>
                            <td>{{ $activity->bonus }} €</td>
                            <td>{{ $activity->recycled_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
