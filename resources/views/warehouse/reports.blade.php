@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Report Mensili del Mega-Magazzino</h1>
        <!-- Form di ricerca per intervallo di date -->
        <form action="{{ route('warehouse.reports') }}" method="GET" class="mb-3 form-inline">
            <label for="start_date">Da:</label>
            <input type="date" name="start_date" class="mx-2 form-control" value="{{ request('start_date') }}">
            <label for="end_date">A:</label>
            <input type="date" name="end_date" class="mx-2 form-control" value="{{ request('end_date') }}">
            <button type="submit" class="btn btn-primary">Filtra</button>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mese del Report</th>
                    <th>Consumo Energetico Totale</th>
                    <th>Ordini Riciclabili</th>
                    <th>Ordini Non Riciclabili</th>
                    <th>Rifiuti Generati (kg)</th>
                    <th>Quantità Venduta</th>
                    <th>Quantità Rifornita</th>
                    <th>Guadagno Mensile (AA)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td>{{ $report->report_month->format('F Y') }}</td>
                        <td>{{ $report->total_energy_used }} kWh</td>
                        <td>{{ $report->recyclable_orders }}</td>
                        <td>{{ $report->non_recyclable_orders }}</td>
                        <td>{{ $report->waste_generated }} kg</td>
                        <td>{{ $report->sold_quantity }}</td>
                        <td>{{ $report->restocked_quantity }}</td>
                        <td>{{ $report->revenue }} AA</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $reports->links() }}
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mese</th>
                <th>Consumo Energetico (kWh)</th>
                <th>Ordini Riciclabili</th>
                <th>Ordini Non Riciclabili</th>
                <th>Rifiuti Generati (kg)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reports as $report)
                <tr>
                    <td>{{ $report->report_month->format('F Y') }}</td>
                    <td>{{ number_format($report->total_energy_used, 2) }}</td>
                    <td>{{ $report->recyclable_orders }}</td>
                    <td>{{ $report->non_recyclable_orders }}</td>
                    <td>{{ number_format($report->waste_generated, 2) }}</td>
                </tr>
            @endforeach
            <!-- Riepilogo Totale -->
            <tr class="font-weight-bold">
                <td>Totale</td>
                <td>{{ number_format($reports->sum('total_energy_used'), 2) }}</td>
                <td>{{ $reports->sum('recyclable_orders') }}</td>
                <td>{{ $reports->sum('non_recyclable_orders') }}</td>
                <td>{{ number_format($reports->sum('waste_generated'), 2) }}</td>
            </tr>
        </tbody>
    </table>
@endsection
