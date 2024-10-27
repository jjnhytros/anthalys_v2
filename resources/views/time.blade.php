<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tempo Anthaliano</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            transition: background 1s ease-in-out;
            background-attachment: fixed;
            background-size: cover;
        }

        #time-display {
            text-align: center;
        }

        canvas {
            border: 1px solid #333;
            background-color: #0b1d3d;
        }
    </style>
</head>

<body>
    <h1>Anthalian Time</h1>
    <div id="time-display">
        <h2>Data: Anno <span id="year">{{ $years }}</span>, <span
                id="day">{{ str_pad($days, 2, '0', STR_PAD_LEFT) }}</span>/<span
                id="month">{{ str_pad($months, 2, '0', STR_PAD_LEFT) }}</span></h2>
        <h3>Ora: <span id="hour">{{ str_pad($hours, 2, '0', STR_PAD_LEFT) }}</span>:<span
                id="minute">{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}</span></h3>
        <span id="season">...</span>
        <div id="lunar-phases">
            <p>Fase di Leea: <span id="leea-phase">...</span></p>
            <p>Fase di Myrhan: <span id="myrhan-phase">...</span></p>
        </div>
        <div id="event-display">
            <p><span id="astronomical-event">Nessun evento</span></p>
        </div>
        <canvas id="orbitaCanvas" width="300" height="300"></canvas>
        <div id="orbit"
            style="position: relative; width: 300px; height: 300px; border: 2px solid #ccc; border-radius: 50%; margin: auto;">
            <div id="nijel"
                style="position: absolute; width: 40px; height: 40px; border-radius: 50%; background: yellow;"></div>
            <div id="anthal"
                style="position: absolute; width: 60px; height: 60px; border-radius: 50%; background: blue; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <div id="leea"
                    style="position: absolute; width: 15px; height: 15px; border-radius: 50%; background: red;"></div>
                <div id="myrhan"
                    style="position: absolute; width: 25px; height: 25px; border-radius: 50%; background: green;"></div>
            </div>
        </div>
    </div>
    <div class="section">
        <h2>Costanti e Parametri di Nijel</h2>
        <ul>
            <li>Massa di Nijel (M<sub>Nijel</sub>): <span id="massa_nijel">1.9564 × 10<sup>30</sup> kg</span></li>
            <li>Unità di Massa Primaria: <span id="unita_massa_primaria">0.983863955878</span></li>
            <li>Raggio della Stella Nijel: <span id="raggio_nijel"></span> m</li>
            <li>Volume di Nijel: <span id="volume_nijel"></span> m<sup>3</sup></li>
            <li>Densità di Nijel: <span id="densita_nijel"></span> kg/m<sup>3</sup></li>
            <li>Luminosità di Nijel (L<sub>Nijel</sub>): <span id="luminosita_nijel"></span> watt</li>
            <li>Temperatura Superficiale di Nijel (T<sub>Nijel</sub>): <span id="temp_nijel"></span> K</li>
            <li>Composizione di Nijel:
                <ul>
                    <li>Idrogeno: 70.3%</li>
                    <li>Elio: 27.7%</li>
                    <li>Metalli: 2%</li>
                </ul>
            </li>
        </ul>
    </div>

    <div class="section">
        <h2>Parametri Orbitali di Anthal</h2>
        <ul>
            <li>Raggio di Anthal: 6,544,770 m</li>
            <li>Massa di Anthal: 5.906030 × 10<sup>24</sup> kg</li>
            <li>Longitudine del Nodo Ascendente (Ω): 130.832565°</li>
            <li>Argomento del Perielio (ω): 133.2447175°</li>
            <li>Densità di Anthal: <span id="densita_anthal">5029.5 kg/m³</span></li>
            <li>Inclinazione Orbitale: <span id="inclinazione_orbitale">5.8225°</span></li>
            <li>Inclinazione dell’Asse: <span id="inclinazione_asse">28.2648°</span></li>
            <li>Albedo: <span id="albedo_anthal">0.1643</span></li>
            <li>Effetto Serra: <span id="effetto_serra">56.43 K</span></li>
            <li>Temperatura Media (Senza Effetto Serra): <span id="temp_anthal_nullo"></span></li>
            <li>Temperatura Media (Con Effetto Serra): <span id="temp_anthal_effettivo"></span></li>
            <li>Temperatura Effettiva: <span id="temperatura-display"></span></li>

            <li>Semiasse Maggiore (a): <span id="semiasse_maggiore"></span> m</li>
            <li>Perielio: <span id="perielio_anthal"></span> m</li>
            <li>Afelio: <span id="afelio_anthal"></span> m</li>
            <li>Distanza Attuale da Nijel: <span id="distanza_anthal_nijel"></span> m</li>

        </ul>
    </div>

    <div class="section">
        <h2>Parametri Orbitali delle Lune</h2>
        <h3>Leea</h3>
        <ul>
            <li>Periodo Orbitale: 12.13483 giorni</li>
            <li>Eccentricità Orbitale: 0.075879454763</li>
            <li>Inclinazione Orbitale: 6.872700594237°</li>
            <li>Distanza Media da Anthal: <span id="r_leea"></span> AU</li>

        </ul>
        <h3>Myrhan</h3>
        <ul>
            <li>Periodo Orbitale: 24.20637 giorni</li>
            <li>Eccentricità Orbitale: 0.063879263578</li>
            <li>Inclinazione Orbitale: 19.507143064099°</li>
            <li>Distanza Media da Anthal: <span id="r_myrhan"></span> AU</li>
        </ul>



    </div>

    <script>
        // Elementi per Nijel e Anthal
        const nijel = document.getElementById("nijel");
        const anthal = document.getElementById("anthal");
        const leea = document.getElementById("leea");
        const myrhan = document.getElementById("myrhan");

        // Variabili iniziali per il tempo
        let year = parseInt(document.getElementById('year').innerText);
        let month = parseInt(document.getElementById('month').innerText);
        let day = parseInt(document.getElementById('day').innerText);
        let hour = parseInt(document.getElementById('hour').innerText);
        let minute = parseInt(document.getElementById('minute').innerText);

        // Costanti e Parametri
        const G = 6.67430e-11; // Gravitational constant
        const sigma = 5.670374419e-8; // Stefan-Boltzmann constant
        const R_sole = 6.963e8; // Radius of the Sun in meters
        const L_sole = 3.828e26; // Luminosity of the Sun in watts
        const AU = 1.495978707e11;
        const canvas = document.getElementById('orbitaCanvas');
        const ctx = canvas.getContext('2d');
        const scala = 108 / AU; // Fattore di scala per visualizzare l'orbita
        const orbitaLeea = 80; // distanza di Leea da Anthal
        const orbitaMyrhan = 140; // distanza di Myrhan da Anthal

        const seasons = ["Luminara", "Marea Bianca", "Crepuscolo Dorato", "Ombra Fredda", "Risveglio delle Maree",
            "Fertilità Oscura"
        ];
        const seasonalColors = {
            Luminara: {
                alba: ["#ffb347", "#ffd700"],
                giorno: ["#ffe135", "#ffeb3b"],
                tramonto: ["#ff8c00", "#ff4500"],
                notte: ["#2c3e50", "#1b2631"]
            },
            "Marea Bianca": {
                alba: ["#ffdfba", "#ffd700"],
                giorno: ["#ffec99", "#ffd500"],
                tramonto: ["#ffa07a", "#dc143c"],
                notte: ["#1e2f4f", "#14243e"]
            },
            "Crepuscolo Dorato": {
                alba: ["#ffcc80", "#ff9800"],
                giorno: ["#ffc107", "#ffeb3b"],
                tramonto: ["#ff5722", "#e65100"],
                notte: ["#2e4053", "#1b2631"]
            },
            "Ombra Fredda": {
                alba: ["#b0bec5", "#90caf9"],
                giorno: ["#64b5f6", "#2196f3"],
                tramonto: ["#37474f", "#263238"],
                notte: ["#1b2631", "#0f1626"]
            },
            "Risveglio delle Maree": {
                alba: ["#ffab91", "#ff7043"],
                giorno: ["#ffcc80", "#ffa726"],
                tramonto: ["#ff5722", "#d84315"],
                notte: ["#263238", "#1b2631"]
            },
            "Fertilità Oscura": {
                alba: ["#f48fb1", "#d81b60"],
                giorno: ["#ce93d8", "#ab47bc"],
                tramonto: ["#8e24aa", "#6a1b9a"],
                notte: ["#311b92", "#1a237e"]
            }
        };
        const colorTransitions = [{
                label: "Notte fonda",
                colors: ["#0b1d3d", "#0b1d3d"]
            },
            {
                label: "Alba Astronomica",
                colors: ["#0b1d3d", "#243856", "#506482"]
            },
            {
                label: "Alba Nautica",
                colors: ["#243856", "#506482", "#90caf9"]
            },
            {
                label: "Alba Blu (8.67°)",
                colors: ["#506482", "#607d8b", "#90caf9"]
            },
            {
                label: "Alba Civile",
                colors: ["#607d8b", "#90caf9", "#b0bec5"]
            },
            {
                label: "Alba Blu (4.33°)",
                colors: ["#90caf9", "#ffcc80", "#ffd54f"]
            },
            {
                label: "Alba Effettiva",
                colors: ["#ffcc80", "#ffd54f", "#ffeb3b"]
            },
            {
                label: "Alba d'Oro",
                colors: ["#ffd54f", "#ffeb3b", "#ffd54f"]
            },
            {
                label: "Mezzogiorno Solare",
                colors: ["#ffeb3b", "#ffeb3b", "#ffd54f"]
            },
            {
                label: "Tramonto d'Oro",
                colors: ["#ffd54f", "#ffcc80", "#ff8a65"]
            },
            {
                label: "Tramonto Effettivo",
                colors: ["#ff8a65", "#ff5722", "#f44336"]
            },
            {
                label: "Tramonto Blu (4.33°)",
                colors: ["#f44336", "#3b5998", "#243856"]
            },
            {
                label: "Tramonto Civile",
                colors: ["#3b5998", "#243856", "#1e3a5f"]
            },
            {
                label: "Tramonto Blu (8.67°)",
                colors: ["#243856", "#1e3a5f", "#0b1d3d"]
            },
            {
                label: "Tramonto Nautico",
                colors: ["#1e3a5f", "#0b1d3d", "#0b1d3d"]
            },
            {
                label: "Notte Astronomica",
                colors: ["#0b1d3d", "#0b1d3d"]
            }
        ];
        const seasonalTextColors = {
            Luminara: "#333333",
            "Marea Bianca": "#2e2e2e",
            "Crepuscolo Dorato": "#1a1a1a",
            "Ombra Fredda": "#e0e0e0",
            "Risveglio delle Maree": "#444444",
            "Fertilità Oscura": "#e6e6e6"
        };

        // Parametri di Nijel (stella)
        const nijelParams = {
            massa: 1.9564134762636803e30,
            raggio: Math.pow(0.983863955878, 67 / 90) * R_sole,
            luminosita: Math.pow(0.983863955878, 4) * L_sole
        };
        nijelParams.temperatura = Math.pow(nijelParams.luminosita / (4 * Math.PI * Math.pow(nijelParams.raggio, 2) * sigma),
            1 / 4);

        // Parametri orbitali di Anthal e delle lune
        const anthalParams = {
            raggio: 6.54477e6,
            massa: 5.906030e24,
            periodoOrbitale: 504 * 86400,
            ecc: 0.051748746387,
            inclinazioneAsse: 28.2648491209,
            albedo: 0.1643,
            effettoSerra: 56.4286,
        };
        const anthalOrbita = {
            semiasseMaggiore: 1.844159146168e11, // metri
            eccentricita: 0.051748746387, // eccentricità dell'orbita
            periodo: 504 * 86400 // periodo orbitale di Anthal in secondi
        };

        const luneParams = {
            leea: {
                periodo: 12.13483 * 86400,
                distanzaMedia: 223779235
            },
            myrhan: {
                periodo: 24.20637 * 86400,
                distanzaMedia: 354609358
            }
        };
        const centroX = canvas.width / 2;
        const centroY = canvas.height / 2;

        function updateRotationAndLighting() {
            // Calcola l'ora come frazione
            const currentHourFraction = hour + (minute / 60);

            // Calcola l'angolo di rotazione di Anthal rispetto a Nijel (simulazione del giorno di 28 ore)
            const rotationAngle = (currentHourFraction / 28) * 360;
            anthal.style.transform = `translate(-50%, -50%) rotate(${rotationAngle}deg)`;

            // Posizione di Nijel rispetto ad Anthal
            const nijelAngleRad = (currentHourFraction / 28) * 2 * Math.PI;
            nijel.style.left = `${150 + 100 * Math.cos(nijelAngleRad)}px`;
            nijel.style.top = `${150 + 100 * Math.sin(nijelAngleRad)}px`;

            // Simulazione dell'illuminazione: variazione diurna/notturna
            const lightIntensity = Math.cos(nijelAngleRad);
            anthal.style.background = `rgba(0, 0, 128, ${0.5 * (1 - lightIntensity) + 0.5})`;

            // Calcolo delle posizioni delle lune in base ai periodi orbitali
            const leeaAngleRad = (2 * Math.PI * currentHourFraction) / (PERIODO_LEEA); // Angolo per Leea
            const myrhanAngleRad = (2 * Math.PI * currentHourFraction) / (PERIODO_MYRHAN); // Angolo per Myrhan

            // Posizione di Leea attorno ad Anthal
            leea.style.left = `${50 + orbitaLeea * Math.cos(leeaAngleRad)}px`;
            leea.style.top = `${50 + orbitaLeea * Math.sin(leeaAngleRad)}px`;

            // Posizione di Myrhan attorno ad Anthal
            myrhan.style.left = `${50 + orbitaMyrhan * Math.cos(myrhanAngleRad)}px`;
            myrhan.style.top = `${50 + orbitaMyrhan * Math.sin(myrhanAngleRad)}px`;
        }

        // Disegna l'orbita ellittica di Anthal
        function disegnaOrbita() {
            const semiMinorAxis = anthalOrbita.semiasseMaggiore * Math.sqrt(1 - Math.pow(anthalOrbita.eccentricita, 2)) *
                scala;
            const semiMajorAxis = anthalOrbita.semiasseMaggiore * scala;

            ctx.beginPath();
            ctx.ellipse(centroX, centroY, semiMajorAxis, semiMinorAxis, 0, 0, 2 * Math.PI);
            ctx.strokeStyle = "#607d8b";
            ctx.lineWidth = 2;
            ctx.stroke();
        }

        // Disegna Nijel al centro dell'orbita
        function disegnaNijel() {
            ctx.beginPath();
            ctx.arc(centroX, centroY, 10, 0, 2 * Math.PI);
            ctx.fillStyle = "#ffd700";
            ctx.fill();
        }

        // Calcola il tempo trascorso in secondi dall'inizio dell'anno su Anthal
        function calcolaTempoAnno() {
            const giorniAnno = (month - 1) * 24 + day - 1; // Giorni trascorsi dall'inizio dell'anno Anthaliano
            const oreGiornaliere = hour + (minute / 60);
            const tempoInSecondi = (giorniAnno * 28 + oreGiornaliere) * 3600;
            return tempoInSecondi;
        }

        // Calcola la posizione di Anthal lungo l'orbita ellittica
        function calcolaPosizioneOrbita(tempo) {
            const M = (2 * Math.PI * tempo) / anthalOrbita.periodo; // Anomalia media
            const E = solveKepler(M, anthalOrbita.eccentricita); // Anomalia eccentrica

            // Calcola la distanza e le coordinate (x, y) in AU
            const distanza = anthalOrbita.semiasseMaggiore * (1 - anthalOrbita.eccentricita * Math.cos(E));
            const x = distanza * Math.cos(E) * scala;
            const y = distanza * Math.sin(E) * scala;

            return {
                x: centroX + x,
                y: centroY - y
            };
        }

        function solveKepler(M, e) {
            let E = M;
            const tolleranza = 1e-6;
            let delta;

            do {
                delta = E - e * Math.sin(E) - M;
                E -= delta / (1 - e * Math.cos(E));
            } while (Math.abs(delta) > tolleranza);

            return E;
        }

        // Disegna Anthal nella posizione calcolata
        function disegnaAnthal() {
            const tempo = calcolaTempoAnno();
            const posizione = calcolaPosizioneOrbita(tempo);

            // Cancella il canvas e ridisegna
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            disegnaOrbita();
            disegnaNijel();

            ctx.beginPath();
            ctx.arc(posizione.x, posizione.y, 8, 0, 2 * Math.PI);
            ctx.fillStyle = "#90caf9";
            ctx.fill();
        }

        // Funzione per calcolare la temperatura diurna e notturna base
        function calcolaTemperaturaBase() {
            const temperaturaBase = Math.pow(((1 - anthalParams.albedo) * nijelParams.luminosita) / (16 * Math.PI *
                sigma * Math.pow(anthalParams.semiasse, 2)), 1 / 4);
            return temperaturaBase + anthalParams.effettoSerra;
        }

        // Calcolo della variazione stagionale della temperatura
        function getSeasonalTemperatureModifier(season) {
            const seasonalModifiers = {
                "Luminara": 5, // Stagione calda
                "Marea Bianca": 2, // Inizio di riscaldamento
                "Crepuscolo Dorato": 0,
                "Ombra Fredda": -3, // Stagione fresca
                "Risveglio delle Maree": -5,
                "Fertilità Oscura": -8 // Stagione più fredda
            };
            return seasonalModifiers[season] || 0;
        }

        // Funzione per variare la temperatura durante il giorno e la notte
        function calcolaTemperaturaGiornaliera(hour, season) {
            const temperaturaBase = calcolaTemperaturaBase();
            const seasonalModifier = getSeasonalTemperatureModifier(season);

            // Escursione termica giornaliera in base all’ora del giorno
            let variazioneDiurna = Math.sin((Math.PI * hour) / 28); // Da 0 (notte) a 1 (mezzogiorno) a 0 (notte)
            const escursioneDiurna = 8; // Variabilità giornaliera +/- 8 K intorno alla temperatura base

            // Calcolo finale della temperatura in base alla base, stagione e ora del giorno
            const temperaturaAttuale = temperaturaBase + seasonalModifier + (variazioneDiurna * escursioneDiurna);
            return temperaturaAttuale.toFixed(2);
        }

        // Funzione per aggiornare la visualizzazione della temperatura in base all'ora e alla stagione
        function aggiornaTemperatura() {
            const dayOfYear = ((month - 1) * 24) + day;
            const season = getSeason(dayOfYear);
            const temperatura = calcolaTemperaturaGiornaliera(hour, season);
            document.getElementById('temperatura-display').innerText = `${temperatura} K`;
        }


        // Funzioni per il calcolo delle posizioni e delle fasi
        function calcolaPosizioneOrbitale(periodo, tempoAttuale) {
            const angolo = (2 * Math.PI * (tempoAttuale % periodo)) / periodo;
            return {
                x: Math.cos(angolo),
                y: Math.sin(angolo)
            };
        }

        function calcolaFaseLunare(tempo, periodo) {
            const frazioneOrbita = (tempo % periodo) / periodo;
            if (frazioneOrbita < 0.25) return "Nuova";
            if (frazioneOrbita < 0.5) return "Crescente";
            if (frazioneOrbita < 0.75) return "Piena";
            return "Calante";
        }

        function aggiornaFasiLunari() {
            const tempoAttuale = Date.now() / 1000;
            document.getElementById("leea-phase").innerText = calcolaFaseLunare(tempoAttuale, luneParams.leea.periodo);
            document.getElementById("myrhan-phase").innerText = calcolaFaseLunare(tempoAttuale, luneParams.myrhan.periodo);
        }

        function verificaCongiunzioneEclissi(posAnthal, posLuna, distanzaLuna) {
            const distanza = Math.sqrt((posLuna.x - posAnthal.x) ** 2 + (posLuna.y - posAnthal.y) ** 2) * AU;
            const limiteEclissi = 0.0001 * AU;
            if (distanza < limiteEclissi) return "Eclissi";
            if (Math.abs(distanza - distanzaLuna) < limiteEclissi) return "Congiunzione";
            return null;
        }

        function aggiornaEventiAstronomici() {
            const tempoAttuale = Date.now() / 1000;
            const posAnthal = calcolaPosizioneOrbitale(anthalParams.periodoOrbitale, tempoAttuale);
            const eventoLeea = verificaCongiunzioneEclissi(posAnthal, calcolaPosizioneOrbitale(luneParams.leea.periodo,
                tempoAttuale), luneParams.leea.distanzaMedia);
            const eventoMyrhan = verificaCongiunzioneEclissi(posAnthal, calcolaPosizioneOrbitale(luneParams.myrhan.periodo,
                tempoAttuale), luneParams.myrhan.distanzaMedia);
            document.getElementById("astronomical-event").innerText =
                `Leea: ${eventoLeea || "Nessun evento"} | Myrhan: ${eventoMyrhan || "Nessun evento"}`;
        }

        // Funzioni per il calcolo delle stagioni e dei colori
        function getSeason(dayOfYear) {
            return seasons[Math.floor((dayOfYear - 1) / 72)];
        }

        function getSeasonalColorTransition(dayOfYear, hour, minute) {
            const season = getSeason(dayOfYear);
            const colors = seasonalColors[season];
            const timeFactor = (hour + minute / 60) / 28;
            let startColor, endColor;
            if (timeFactor < 0.25) {
                startColor = colors.notte[1];
                endColor = colors.alba[0];
            } else if (timeFactor < 0.5) {
                startColor = colors.alba[1];
                endColor = colors.giorno[0];
            } else if (timeFactor < 0.75) {
                startColor = colors.giorno[1];
                endColor = colors.tramonto[0];
            } else {
                startColor = colors.tramonto[1];
                endColor = colors.notte[0];
            }
            return interpolatedColor(startColor, endColor, (timeFactor % 0.25) * 4);
        }

        function updateSeasonalStyles() {
            const dayOfYear = ((month - 1) * 24) + day;
            const season = getSeason(dayOfYear);
            document.body.style.color = seasonalTextColors[season];
            updateBackground(hour, minute);
        }

        // Funzione principale per l'aggiornamento del tempo e del display
        function updateTimeDisplay() {
            minute++;
            if (minute >= 60) {
                minute = 0;
                hour++;
            }
            if (hour >= 28) {
                hour = 0;
                day++;
            }
            if (day > 24) {
                day = 1;
                month++;
            }
            if (month > 18) {
                month = 1;
                year++;
            }
            const dayOfYear = ((month - 1) * 24) + day;
            document.getElementById('year').innerText = year;
            document.getElementById('month').innerText = month.toString().padStart(2, '0');
            document.getElementById('day').innerText = day.toString().padStart(2, '0');
            document.getElementById('hour').innerText = hour.toString().padStart(2, '0');
            document.getElementById('minute').innerText = minute.toString().padStart(2, '0');
            document.getElementById('season').innerText = getSeason(dayOfYear);
            aggiornaFasiLunari();
            aggiornaEventiAstronomici();
            updateSeasonalStyles();
        }

        setInterval(updateTimeDisplay, 1000);
        setInterval(aggiornaTemperatura);
        // Funzione per aggiornare la simulazione a intervalli
        function aggiornaSimulazione() {
            disegnaAnthal();
            requestAnimationFrame(aggiornaSimulazione);
        }

        // Avvia la simulazione
        aggiornaSimulazione();
        setInterval(updateRotationAndLighting, 1000);
    </script>


    {{-- <script>
        let year = parseInt(document.getElementById('year').innerText);
        let month = parseInt(document.getElementById('month').innerText);
        let day = parseInt(document.getElementById('day').innerText);
        let hour = parseInt(document.getElementById('hour').innerText);
        let minute = parseInt(document.getElementById('minute').innerText);
        const seasons = ["Luminara", "Marea Bianca", "Crepuscolo Dorato", "Ombra Fredda", "Risveglio delle Maree",
            "Fertilità Oscura"
        ];
        // Costanti universali
        const G = 6.67430e-11; // Gravitational constant
        const sigma = 5.670374419e-8; // Stefan-Boltzmann constant
        const R_sole = 6.963e8; // Radius of the Sun in meters
        const L_sole = 3.828e26; // Luminosity of the Sun in watts

        // Parametri di Nijel
        const unita_massa_primaria = 0.983863955878;
        const raggio_stella = Math.pow(unita_massa_primaria, 67 / 90);
        const raggio_nijel_m = raggio_stella * R_sole;
        const volume_nijel_m3 = (4 / 3) * Math.PI * Math.pow(raggio_nijel_m, 3);
        const M_nijel = 1.9564134762636803e30;
        const densita_nijel = M_nijel / volume_nijel_m3;
        const unita_luminosita_nijel = Math.pow(unita_massa_primaria, 4);
        const L_nijel_watt = unita_luminosita_nijel * L_sole;
        const T_nijel_precisa = Math.pow(L_nijel_watt / (4 * Math.PI * Math.pow(raggio_nijel_m, 2) * sigma), 1 / 4);
        const latitudine = 0; // Latitudine di riferimento

        // Parametri orbitali di Anthal
        const raggio_anthal = 6.54477e6; // meters
        const massa_anthal = 5.906030e24; // kg
        const densita_anthal = 5029.5; // kg/m³
        const inclinazione_orbitale = 5.8225; // in degrees
        const inclinazione_asse = 28.2648491209; // in degrees
        const albedo_anthal = 0.1643;
        const effetto_serra = 56.4286; // in Kelvin
        const atmosfera_composizione = {
            N2: 77.90, // Azoto
            O2: 21.15, // Ossigeno
            CO2: 0.0417, // Anidride Carbonica
            CH4: 0.00016, // Metano
            Ar: 0.91 // Argon
        };

        const ecc_anthal = 0.051748746387;
        const periodo_orbitale_anthal_sec = 504 * 86400; // 504 giorni in secondi

        const a_anthal = Math.pow((G * M_nijel * Math.pow(periodo_orbitale_anthal_sec, 2)) / (4 * Math.PI ** 2), 1 / 3);
        const perielio_anthal = a_anthal * (1 - ecc_anthal);
        const afelio_anthal = a_anthal * (1 + ecc_anthal);

        // Parametri orbitali delle lune
        const AU = 1.495978707e11;
        const r_leea = 223779235;
        const r_myrhan = 354609358;

        // Periodi orbitali
        const PERIODO_LEEA = 12.13483; // Periodo orbitale di Leea in giorni
        const PERIODO_MYRHAN = 24.20637; // Periodo orbitale di Myrhan in giorni
        const PERIODO_ANTHAL = 432; // Periodo orbitale di Anthal in giorni

        // Eccentricità orbitali
        const ECC_LEEA = 0.075879454763; // Eccentricità di Leea
        const ECC_MYRHAN = 0.063879263578; // Eccentricità di Myrhan

        // Parametri orbitali
        const periodoOrbitaAnthal = 504 * 86400;
        const periodoOrbitaLeea = 12.13483 * 86400;
        const periodoOrbitaMyrhan = 24.20637 * 86400;
        const AU = 1.495978707e11;
        const distanzaMediaLeea = r_leea;
        const distanzaMediaMyrhan = r_myrhan;

        function calcolaPosizioneOrbitale(periodo, tempoAttuale) {
            const angolo = (2 * Math.PI * (tempoAttuale % periodo)) / periodo;
            return {
                x: Math.cos(angolo),
                y: Math.sin(angolo)
            };
        }

        function calcolaFaseLunare(tempo, periodoOrbita) {
            const frazioneOrbita = (tempo % periodoOrbita) / periodoOrbita;
            if (frazioneOrbita < 0.25) return "Nuova";
            else if (frazioneOrbita < 0.5) return "Crescente";
            else if (frazioneOrbita < 0.75) return "Piena";
            else return "Calante";
        }

        function aggiornaFasiLunari() {
            const tempoAttuale = Date.now() / 1000;
            const faseLeea = calcolaFaseLunare(tempoAttuale, periodoOrbitaLeea);
            const faseMyrhan = calcolaFaseLunare(tempoAttuale, periodoOrbitaMyrhan);

            document.getElementById("leea-phase").innerText = faseLeea;
            document.getElementById("myrhan-phase").innerText = faseMyrhan;
        }

        function verificaCongiunzioneEclissi(posAnthal, posLuna, distanzaLuna) {
            const distanza = Math.sqrt((posLuna.x - posAnthal.x) ** 2 + (posLuna.y - posAnthal.y) ** 2) * AU;
            const limiteEclissi = 0.0001 * AU;

            if (distanza < limiteEclissi) return "Eclissi";
            if (Math.abs(distanza - distanzaLuna) < limiteEclissi) return "Congiunzione";
            return null;
        }

        function aggiornaEventiAstronomici() {
            const tempoAttuale = Date.now() / 1000;
            const posizioneAnthal = calcolaPosizioneOrbitale(periodoOrbitaAnthal, tempoAttuale);
            const posizioneLeea = calcolaPosizioneOrbitale(periodoOrbitaLeea, tempoAttuale);
            const posizioneMyrhan = calcolaPosizioneOrbitale(periodoOrbitaMyrhan, tempoAttuale);

            const eventoLeea = verificaCongiunzioneEclissi(posizioneAnthal, posizioneLeea, distanzaMediaLeea);
            const eventoMyrhan = verificaCongiunzioneEclissi(posizioneAnthal, posizioneMyrhan, distanzaMediaMyrhan);

            let eventoAstronomico = "Nessun evento";
            if (eventoLeea || eventoMyrhan) {
                eventoAstronomico = `Leea: ${eventoLeea || "Nessun evento"} | Myrhan: ${eventoMyrhan || "Nessun evento"}`;
            }

            document.getElementById("astronomical-event").innerText = eventoAstronomico;
        }

        function calcolaTemperaturaSuperficiale() {
            const T_anthal_nullo = Math.pow(((1 - albedo_anthal) * L_nijel_watt) / (16 * Math.PI * sigma * Math.pow(
                a_anthal, 2)), 1 / 4);
            const T_anthal_effettivo = T_anthal_nullo + effetto_serra;
            return {
                T_nullo: T_anthal_nullo.toFixed(2),
                T_effettivo: T_anthal_effettivo.toFixed(2)
            };
        }

        function getSeason(dayOfYear) {
            const seasonIndex = Math.floor((dayOfYear - 1) / 72);
            return seasons[seasonIndex];
        }

        function getSeasonalColorTransition(dayOfYear, hour, minute) {
            const season = getSeason(dayOfYear);
            const colors = seasonalColors[season];
            const timeFactor = (hour + minute / 60) / 28;

            let startColor, endColor;
            if (timeFactor < 0.25) { // Alba
                startColor = colors.notte[1];
                endColor = colors.alba[0];
            } else if (timeFactor < 0.5) { // Giorno
                startColor = colors.alba[1];
                endColor = colors.giorno[0];
            } else if (timeFactor < 0.75) { // Tramonto
                startColor = colors.giorno[1];
                endColor = colors.tramonto[0];
            } else { // Notte
                startColor = colors.tramonto[1];
                endColor = colors.notte[0];
            }

            const localFactor = (timeFactor % 0.25) * 4;
            return interpolatedColor(startColor, endColor, localFactor);
        }

        function updateSeasonalBackground() {
            const dayOfYear = ((month - 1) * 24) + day;
            const bgColor = getSeasonalColorTransition(dayOfYear, hour, minute);
            document.body.style.background = `linear-gradient(180deg, ${bgColor}, ${bgColor})`;
            document.body.style.transition = "background 1s ease-in-out";
        }

        // Calcola la fase lunare in base al giorno dell'anno e al periodo orbitale
        function getLunarPhase(dayOfYear, period) {
            const phaseDay = dayOfYear % period;
            if (phaseDay < period / 4) return "Luna Nuova";
            else if (phaseDay < period / 2) return "Primo Quarto";
            else if (phaseDay < (3 * period) / 4) return "Luna Piena";
            else return "Ultimo Quarto";
        }

        const colorTransitions = [{
                label: "Notte fonda",
                colors: ["#0b1d3d", "#0b1d3d"]
            },
            {
                label: "Alba Astronomica",
                colors: ["#0b1d3d", "#243856", "#506482"]
            },
            {
                label: "Alba Nautica",
                colors: ["#243856", "#506482", "#90caf9"]
            },
            {
                label: "Alba Blu (8.67°)",
                colors: ["#506482", "#607d8b", "#90caf9"]
            },
            {
                label: "Alba Civile",
                colors: ["#607d8b", "#90caf9", "#b0bec5"]
            },
            {
                label: "Alba Blu (4.33°)",
                colors: ["#90caf9", "#ffcc80", "#ffd54f"]
            },
            {
                label: "Alba Effettiva",
                colors: ["#ffcc80", "#ffd54f", "#ffeb3b"]
            },
            {
                label: "Alba d'Oro",
                colors: ["#ffd54f", "#ffeb3b", "#ffd54f"]
            },
            {
                label: "Mezzogiorno Solare",
                colors: ["#ffeb3b", "#ffeb3b", "#ffd54f"]
            },
            {
                label: "Tramonto d'Oro",
                colors: ["#ffd54f", "#ffcc80", "#ff8a65"]
            },
            {
                label: "Tramonto Effettivo",
                colors: ["#ff8a65", "#ff5722", "#f44336"]
            },
            {
                label: "Tramonto Blu (4.33°)",
                colors: ["#f44336", "#3b5998", "#243856"]
            },
            {
                label: "Tramonto Civile",
                colors: ["#3b5998", "#243856", "#1e3a5f"]
            },
            {
                label: "Tramonto Blu (8.67°)",
                colors: ["#243856", "#1e3a5f", "#0b1d3d"]
            },
            {
                label: "Tramonto Nautico",
                colors: ["#1e3a5f", "#0b1d3d", "#0b1d3d"]
            },
            {
                label: "Notte Astronomica",
                colors: ["#0b1d3d", "#0b1d3d"]
            }
        ];
        const seasonalColors = {
            Luminara: {
                alba: ["#ffb347", "#ffd700"],
                giorno: ["#ffe135", "#ffeb3b"],
                tramonto: ["#ff8c00", "#ff4500"],
                notte: ["#2c3e50", "#1b2631"]
            },
            "Marea Bianca": {
                alba: ["#ffdfba", "#ffd700"],
                giorno: ["#ffec99", "#ffd500"],
                tramonto: ["#ffa07a", "#dc143c"],
                notte: ["#1e2f4f", "#14243e"]
            },
            "Crepuscolo Dorato": {
                alba: ["#ffcc80", "#ff9800"],
                giorno: ["#ffc107", "#ffeb3b"],
                tramonto: ["#ff5722", "#e65100"],
                notte: ["#2e4053", "#1b2631"]
            },
            "Ombra Fredda": {
                alba: ["#b0bec5", "#90caf9"],
                giorno: ["#64b5f6", "#2196f3"],
                tramonto: ["#37474f", "#263238"],
                notte: ["#1b2631", "#0f1626"]
            },
            "Risveglio delle Maree": {
                alba: ["#ffab91", "#ff7043"],
                giorno: ["#ffcc80", "#ffa726"],
                tramonto: ["#ff5722", "#d84315"],
                notte: ["#263238", "#1b2631"]
            },
            "Fertilità Oscura": {
                alba: ["#f48fb1", "#d81b60"],
                giorno: ["#ce93d8", "#ab47bc"],
                tramonto: ["#8e24aa", "#6a1b9a"],
                notte: ["#311b92", "#1a237e"]
            }
        };
        const seasonalTextColors = {
            Luminara: "#333333", // Contrasto elevato per il giorno luminoso
            "Marea Bianca": "#2e2e2e", // Tono più scuro per contrastare i colori chiari
            "Crepuscolo Dorato": "#1a1a1a",
            "Ombra Fredda": "#e0e0e0", // Tono chiaro per il contrasto con i toni freddi
            "Risveglio delle Maree": "#444444",
            "Fertilità Oscura": "#e6e6e6" // Tono chiaro per contrastare i colori intensi
        };

        function updateSeasonalTextColor() {
            const dayOfYear = ((month - 1) * 24) + day;
            const season = getSeason(dayOfYear);
            const textColor = seasonalTextColors[season];

            // Aggiorna il colore del testo principale e degli elementi UI
            document.body.style.color = textColor;
            document.querySelectorAll("h1, h2, h3, #time-display, #lunar-phases, .section").forEach(element => {
                element.style.color = textColor;
            });
        }

        function updateSeasonalStyles() {
            updateSeasonalBackground(); // Aggiorna il background stagionale
            updateSeasonalTextColor(); // Aggiorna il colore del testo
        }

        function updateTimeDisplay() {
            minute++;
            if (minute >= 60) {
                minute = 0;
                hour++;
            }
            if (hour >= 28) {
                hour = 0;
                day++;
            }
            if (day > 24) {
                day = 1;
                month++;
            }
            if (month > 18) {
                month = 1;
                year++;
            }
            const dayOfYear = ((month - 1) * 24) + day;
            const season = getSeason(dayOfYear);
            // Calcola le fasi lunari per Leea e Myrhan
            const leeaPhase = getLunarPhase(dayOfYear, PERIODO_LEEA);
            const myrhanPhase = getLunarPhase(dayOfYear, PERIODO_MYRHAN);
            day = day < 1 ? 1 : day;
            month = month < 1 ? 1 : month;

            document.getElementById('year').innerText = year;
            document.getElementById('month').innerText = month.toString().padStart(2, '0');
            document.getElementById('day').innerText = day.toString().padStart(2, '0');
            document.getElementById('hour').innerText = hour.toString().padStart(2, '0');
            document.getElementById('minute').innerText = minute.toString().padStart(2, '0');
            document.getElementById('season').innerText = season;
            document.getElementById('leea-phase').innerText = leeaPhase;
            document.getElementById('myrhan-phase').innerText = myrhanPhase;
            // Visualizzazione risultati
            document.getElementById("raggio_nijel").innerText = `${raggio_nijel_m.toFixed(0)} m`;
            document.getElementById("volume_nijel").innerText = `${volume_nijel_m3.toExponential(2)} m³`;
            document.getElementById("densita_nijel").innerText = `${densita_nijel.toFixed(2)} kg/m³`;
            document.getElementById("luminosita_nijel").innerText = `${L_nijel_watt.toExponential(2)} W`;
            document.getElementById("temp_nijel").innerText = `${T_nijel_precisa.toFixed(2)} K`;

            document.getElementById("semiasse_maggiore").innerText = `${a_anthal.toFixed(0)} m`;
            document.getElementById("perielio_anthal").innerText = `${perielio_anthal.toFixed(0)} m`;
            document.getElementById("afelio_anthal").innerText = `${afelio_anthal.toFixed(0)} m`;

            const temperature = calcolaTemperaturaSuperficiale();
            document.getElementById("temp_anthal_nullo").innerText = `${temperature.T_nullo} K (senza effetto serra)`;
            document.getElementById("temp_anthal_effettivo").innerText = `${temperature.T_effettivo} K (con effetto serra)`;

            aggiornaFasiLunari();
            aggiornaEventiAstronomici();
            updateBackground(hour, minute);
            aggiornaColoriStagione(dayOfYear);
            updateDistance();
            aggiornaOraConInclinazione();
            updateSeasonalBackground();
            updateSeasonalStyles();

        }

        function calcolaDeclinazione(giorno_anno) {
            const rad = Math.PI / 180;
            return inclinazione_asse * Math.sin((2 * Math.PI * giorno_anno) / PERIODO_ANTHAL) * rad;
        }

        function calcolaDurataGiorno(declinazione) {
            const rad = Math.PI / 180;
            const latitudine = 0; // latitudine di riferimento (equatore)
            const angolo = Math.cos(declinazione) * Math.cos(latitudine * rad);

            // Durata approssimativa del giorno (minima variazione diurna)
            const durataGiorno = 14 + angolo * 7; // giorno tra 14-21 ore
            return durataGiorno;
        }

        function aggiornaColoriStagione(giornoAnno) {
            const declinazione = calcolaDeclinazione(giornoAnno);
            const durataGiorno = calcolaDurataGiorno(declinazione);

            let coloreDiurno, coloreNotturno;

            if (durataGiorno >= 18) { // Estate (giorni lunghi, colori caldi)
                coloreDiurno = ["#ffeb3b", "#ffd54f"]; // Colori estivi
                coloreNotturno = ["#243856", "#0b1d3d"];
            } else if (durataGiorno <= 14) { // Inverno (giorni corti, colori freddi)
                coloreDiurno = ["#90caf9", "#607d8b"]; // Colori invernali
                coloreNotturno = ["#0b1d3d", "#243856"];
            } else { // Stagioni intermedie
                coloreDiurno = ["#ffcc80", "#ffd54f"];
                coloreNotturno = ["#243856", "#0b1d3d"];
            }

            const ora = (hour % durataGiorno); // Calcola ora locale
            document.body.style.background = ora < durataGiorno ? `linear-gradient(180deg, ${coloreDiurno.join(", ")})` :
                `linear-gradient(180deg, ${coloreNotturno.join(", ")})`;
        }

        function calcolaAngoloSolare(ora, minuto, declinazione) {
            const rad = Math.PI / 180;
            const ora_solare = (15 * (ora + minuto / 60 - 12)) * rad;
            const latitudine_rad = latitudine * rad;

            return Math.asin(
                Math.sin(latitudine_rad) * Math.sin(declinazione) +
                Math.cos(latitudine_rad) * Math.cos(declinazione) * Math.cos(ora_solare)
            ) / rad;
        }

        function aggiornaOraConInclinazione() {
            const giorno_anno = ((month - 1) * 24) + day; // Calcola il giorno dell'anno Anthaliano
            const declinazione = calcolaDeclinazione(giorno_anno);
            const angoloSolare = calcolaAngoloSolare(hour, minute, declinazione);

            // Seleziona il colore in base all'angolo solare usando i colori di colorTransitions
            let selectedColors;
            if (angoloSolare > 45) {
                selectedColors = colorTransitions.find(c => c.label === "Mezzogiorno Solare").colors;
            } else if (angoloSolare > 10) {
                selectedColors = colorTransitions.find(c => c.label === "Alba Effettiva").colors;
            } else if (angoloSolare > 0) {
                selectedColors = colorTransitions.find(c => c.label === "Tramonto Effettivo").colors;
            } else {
                selectedColors = colorTransitions.find(c => c.label === "Notte Astronomica").colors;
            }

            // Applica i colori al background
            document.body.style.background = `linear-gradient(180deg, ${selectedColors[0]}, ${selectedColors[1]})`;
            document.body.style.color = angoloSolare < 10 ? "#f5f5f5" : "#333";
        }


        function calculateDistanceByMinute(day, month, year, hour, minute) {
            const dayOfYear = ((month - 1) * 24) + day;
            const totalMinutesInDay = hour * 60 + minute;
            const dayFraction = totalMinutesInDay / (28 * 60); // Frazione del giorno Anthaliano

            // Calcola l'anomalia media in radianti aggiungendo la frazione di giorno
            const M = (2 * Math.PI * (dayOfYear + dayFraction)) / (periodo_orbitale_anthal_sec / 86400);

            const E = solveKepler(M, ecc_anthal);
            const distance = a_anthal * (1 - ecc_anthal * Math.cos(E));
            document.getElementById("distanza_anthal_nijel").innerText = `${distance.toFixed(0).toLocaleString()} m`;
        }


        function solveKepler(M, e) {
            let E = M;
            const tolerance = 1e-6;
            let delta;

            do {
                delta = E - e * Math.sin(E) - M;
                E -= delta / (1 - e * Math.cos(E));
            } while (Math.abs(delta) > tolerance);

            return E;
        }

        function hexToRgb(hex) {
            const bigint = parseInt(hex.slice(1), 16);
            return [(bigint >> 16) & 255, (bigint >> 8) & 255, bigint & 255];
        }

        function rgbToHex([r, g, b]) {
            return `#${((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1)}`;
        }

        function interpolateColors(color1, color2, factor) {
            const [r1, g1, b1] = hexToRgb(color1);
            const [r2, g2, b2] = hexToRgb(color2);
            const r = Math.round(r1 + (r2 - r1) * factor);
            const g = Math.round(g1 + (g2 - g1) * factor);
            const b = Math.round(b1 + (b2 - b1) * factor);
            return rgbToHex([r, g, b]);
        }

        function getPhaseColors(hour, minute) {
            const phaseIndex = Math.floor(hour / 1.75) % colorTransitions.length; // 1.75 ore per fase
            const nextPhaseIndex = (phaseIndex + 1) % colorTransitions.length;

            const currentColors = colorTransitions[phaseIndex].colors;
            const nextColors = colorTransitions[nextPhaseIndex].colors;

            const factor = minute / 60;

            const interpolatedStart = interpolateColors(currentColors[0], nextColors[0], factor);
            const interpolatedEnd = interpolateColors(currentColors[1], nextColors[1], factor);

            return [interpolatedStart, interpolatedEnd];
        }


        function updateDistance() {
            calculateDistanceByMinute(day, month, year, hour, minute);
        }

        function updateBackground(hour, minute) {
            const [startColor, endColor] = getPhaseColors(hour, minute);
            document.body.style.background = `linear-gradient(180deg, ${startColor}, ${endColor})`;
            document.body.style.color = hour >= 22 || hour < 6 ? "#f5f5f5" : "#333";
        }

        setInterval(updateTimeDisplay, 1000);
    </script> --}}
</body>

</html>
