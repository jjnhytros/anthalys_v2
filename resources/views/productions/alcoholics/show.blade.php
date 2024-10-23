@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Dettagli della Produzione: {{ $alcoholic->name }}</h1>

        <table class="table">
            <tr>
                <th>Tipo di Prodotto:</th>
                <td>{{ $alcoholic->type }}</td>
            </tr>
            <tr>
                <th>Batch Size:</th>
                <td>{{ $alcoholic->batch_size }}</td>
            </tr>
            <tr>
                <th>Malto:</th>
                <td>{{ $alcoholic->malt_type }}</td>
            </tr>
            <tr>
                <th>Luppolo:</th>
                <td>{{ $alcoholic->hop_type }}</td>
            </tr>
            <tr>
                <th>Lievito:</th>
                <td>{{ $alcoholic->yeast_type }}</td>
            </tr>
            <tr>
                <th>Fonte d'Acqua:</th>
                <td>{{ $alcoholic->water_source }}</td>
            </tr>
            <tr>
                <th>Fase di Produzione:</th>
                <td>{{ $alcoholic->production_phase }}</td>
            </tr>
            <tr>
                <th>Tempo di Fermentazione:</th>
                <td>{{ $alcoholic->fermentation_time }} giorni</td>
            </tr>
            <tr>
                <th>Tempo di Maturazione:</th>
                <td>{{ $alcoholic->maturation_time }} giorni</td>
            </tr>
            <tr>
                <th>Quantità Prodotta:</th>
                <td>{{ $alcoholic->quantity }}</td>
            </tr>
            <tr>
                <th>Qualità:</th>
                <td>{{ $alcoholic->quality }}</td>
            </tr>
            <tr>
                <th>Impatto Ambientale:</th>
                <td>{{ $alcoholic->environmental_impact }}</td>
            </tr>
        </table>
    </div>
@endsection
