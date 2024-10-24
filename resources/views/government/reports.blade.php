@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Report Annuali del Bilancio Governativo</h2>
        @if ($reports->isEmpty())
            <p>Non ci sono report disponibili.</p>
        @else
            <ul>
                @foreach ($reports as $report)
                    <li>
                        <a href="{{ route('government.report.view', $report->year) }}">
                            Report Anno {{ $report->year }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
