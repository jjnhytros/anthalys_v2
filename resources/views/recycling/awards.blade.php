@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Premi Annuali di Riciclo</h1>

        @if ($awards->isEmpty())
            <p>Non sono stati assegnati premi quest'anno.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Cittadino</th>
                        <th>Premio</th>
                        <th>Anno</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($awards as $award)
                        <tr>
                            <td>{{ $award->citizen->name }}</td>
                            <td>{{ $award->award_type }}</td>
                            <td>{{ $award->year }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
