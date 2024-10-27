@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Mappa Interattiva di Anthalys</h1>

        <!-- Mappa SVG caricata inline -->
        <div id="svg-map" style="width: 100%; height: auto;">
            {!! file_get_contents(public_path('svg/anthalys.svg')) !!}
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var svgMap = document.getElementById('svg-map').querySelector('svg');

            // Nasconde tutti gli elementi di testo per rimuovere le etichette
            svgMap.querySelectorAll('text').forEach(function(label) {
                label.style.display = 'none';
            });

            // Caricamento del file JSON con le coordinate dei distretti
            fetch('{{ asset('json/anthalys.json') }}')
                .then(response => response.json())
                .then(data => {
                    // Analizza il JSON per ottenere gli ID dei confini dei distretti
                    data.features.forEach(function(feature) {
                        if (feature.geometry.type === "LineString") {
                            // ID del confine del distretto
                            var districtId = feature.properties
                            .id; // Modifica questa chiave in base alla struttura

                            // Seleziona il confine del distretto nell'SVG
                            var districtBorder = svgMap.getElementById(districtId);
                            if (districtBorder) {
                                // Genera un colore casuale per il distretto
                                var color = '#' + Math.floor(Math.random() * 16777215).toString(16);
                                districtBorder.style.fill = 'none';
                                districtBorder.style.stroke = color;
                                districtBorder.style.strokeWidth = '2px';
                                districtBorder.style.cursor = 'pointer';

                                // Aggiungi interattività, come il clic sul distretto
                                districtBorder.addEventListener('click', function() {
                                    alert('Hai cliccato sul confine del distretto ' +
                                        districtId);
                                });
                            } else {
                                console.warn('Confine del distretto non trovato per ID:', districtId);
                            }
                        }
                    });
                })
                .catch(error => console.error('Errore nel caricamento del JSON:', error));
        });
    </script>
@endsection
