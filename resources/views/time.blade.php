<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anthalian Time</title>
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
        <h2>Date: Year <span id="year">{{ $years }}</span>, <span
                id="day">{{ str_pad($days, 2, '0', STR_PAD_LEFT) }}</span>/<span
                id="month">{{ str_pad($months, 2, '0', STR_PAD_LEFT) }}</span></h2>
        <h3>Time: <span id="hour">{{ str_pad($hours, 2, '0', STR_PAD_LEFT) }}</span>:<span
                id="minute">{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}</span></h3>
        <span id="season">...</span>
        <div id="lunar-phases">
            <p>Leea Phase: <span id="leea-phase">...</span></p>
            <p>Myrhan Phase: <span id="myrhan-phase">...</span></p>
        </div>
        <div id="event-display">
            <p><span id="astronomical-event">Nessun evento</span></p>
            <p>Leea eclisse: <span id="leea-eclipse">Nessun evento</span></p>
            <p>Myrhan eclisse: <span id="myrhan-eclipse">Nessun evento</span></p>
            <p>Congiunzione Leea-Myrhan: <span id="conjunction-leea-myrhan">Nessun evento</span></p>
            <p>Leea e Nijel elongazione: <span id="elongation-leea">Nessun evento</span></p>
            <p>Myrhan e Nijel elongazione: <span id="elongation-myrhan">Nessun evento</span></p>
            <p>Fasi sincrone: <span id="sync-phases">Nessun evento</span></p>
            <p>Perihelion Date: <span id="perihelion-date">Calculating...</span></p>
            <p>Aphelion Date: <span id="aphelion-date">Calculating...</span></p>

            <h3>Calculated Events</h3>
            <ul>
                <li>Next Conjunction of Leea and Myrhan: <span id="conjunction-date">Calculating...</span></li>
                <li>Next Super Eclipse: <span id="super-eclipse-date">Calculating...</span></li>
                <li>Next Opposition of Leea and Myrhan: <span id="opposition-date">Calculating...</span></li>
                <li>Next Equinox: <span id="equinox-date">Calculating...</span></li>
                <li>Next Solstice: <span id="solstice-date">Calculating...</span></li>
            </ul>
            <span id="full-alignment"></span>

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
        <h2>Nijel Constants and Parameters</h2>
        <ul>
            <li>Mass of Nijel (M<sub>Nijel</sub>): <span id="massa_nijel">1.9564 × 10<sup>30</sup> kg</span></li>
            <li>Primary Mass Unit: <span id="unita_massa_primaria">0.983863955878</span></li>
            <li>Nijel Star Radius: <span id="raggio_nijel"></span> m</li>
            <li>Nijel Volume: <span id="volume_nijel"></span> m<sup>3</sup></li>
            <li>Nijel Density: <span id="densita_nijel"></span> kg/m<sup>3</sup></li>
            <li>Nijel Luminosity (L<sub>Nijel</sub>): <span id="luminosita_nijel"></span> watts</li>
            <li>Nijel Surface Temperature (T<sub>Nijel</sub>): <span id="temp_nijel"></span> K</li>
            <li>Nijel Composition:
                <ul>
                    <li>Hydrogen: 70.3%</li>
                    <li>Helium: 27.7%</li>
                    <li>Metals: 2%</li>
                </ul>
            </li>
        </ul>
    </div>

    <div class="section">
        <h2>Orbital Parameters of Anthal</h2>
        <ul>
            <li>Anthal Radius: 6,544,770 m</li>
            <li>Anthal Mass: 5.906030 × 10<sup>24</sup> kg</li>
            <li>Longitude of the Ascending Node (Ω): 130.832565°</li>
            <li>Argument of Perihelion (ω): 133.2447175°</li>
            <li>Anthal Density: <span id="densita_anthal">5029.5 kg/m³</span></li>
            <li>Orbital Inclination: <span id="inclinazione_orbitale">5.8225°</span></li>
            <li>Axial Tilt: <span id="inclinazione_asse">28.2648°</span></li>
            <li>Albedo: <span id="albedo_anthal">0.1643</span></li>
            <li>Greenhouse Effect: <span id="effetto_serra">56.43 K</span></li>
            <li>Average Temperature (Without Greenhouse Effect): <span id="temp_anthal_nullo"></span></li>
            <li>Average Temperature (With Greenhouse Effect): <span id="temp_anthal_effettivo"></span></li>
            <li>Effective Temperature: <span id="temperature-display"></span></li>

            <li>Semimajor Axis (a): <span id="semiasse_maggiore"></span> m</li>
            <li>Perihelion: <span id="perielio_anthal"></span> m</li>
            <li>Aphelion: <span id="afelio_anthal"></span> m</li>
            <li>Current Distance from Nijel: <span id="distanza_anthal_nijel"></span> m</li>

        </ul>
    </div>

    <div class="section">
        <h2>Orbital Parameters of the Moons</h2>
        <h3>Leea</h3>
        <ul>
            <li>Orbital Period: 12.13483 days</li>
            <li>Orbital Eccentricity: 0.075879454763</li>
            <li>Orbital Inclination: 6.872700594237°</li>
            <li>Average Distance from Anthal: <span id="r_leea"></span> AU</li>

        </ul>
        <h3>Myrhan</h3>
        <ul>
            <li>Orbital Period: 24.20637 days</li>
            <li>Orbital Eccentricity: 0.063879263578</li>
            <li>Orbital Inclination: 19.507143064099°</li>
            <li>Average Distance from Anthal: <span id="r_myrhan"></span> AU</li>
        </ul>

    </div>
    <script>
        // Constants and Parameters
        // ----------------------------------------
        // Gravitational constant and Stefan-Boltzmann constant
        const G = 6.67430e-11;
        const sigma = 5.670374419e-8;

        // Constants for Nijel (Star) and Anthal (Planet)
        const R_sun = 6.963e8; // Sun radius in meters
        const L_sun = 3.828e26; // Sun luminosity in watts
        const AU = 1.495978707e11; // Astronomical Unit

        const nijelParams = {
            mass: 1.9564134762636803e30,
            radius: Math.pow(0.983863955878, 67 / 90) * R_sun,
            luminosity: Math.pow(0.983863955878, 4) * L_sun,
            temperature: Math.pow(Math.pow(0.983863955878, 4) * L_sun / (4 * Math.PI * Math.pow(Math.pow(0.983863955878,
                67 / 90) * R_sun, 2) * sigma), 1 / 4),
        };

        const anthalParams = {
            mass: 5.906030e24,
            radius: 6.54477e6,
            orbitalPeriod: 504 * 86400,
            eccentricity: 0.051748746387,
            axialTilt: 28.2648491209,
            albedo: 0.1643,
            greenhouse: 56.4286,
            semimajorAxis: 1.844159146168e11, // meters
        };

        const moonsParams = {
            leea: {
                orbitalPeriod: 12.13483 * 86400,
                semimajorAxis: 223779235,
            },
            myrhan: {
                orbitalPeriod: 24.20637 * 86400,
                semimajorAxis: 354609358,
            }
        };

        // Elements for Nijel and Anthal
        const nijel = document.getElementById("nijel");
        const anthal = document.getElementById("anthal");
        const leea = document.getElementById("leea");
        const myrhan = document.getElementById("myrhan");

        // Initial variables for time
        let year = parseInt(document.getElementById('year').innerText);
        let month = parseInt(document.getElementById('month').innerText);
        let day = parseInt(document.getElementById('day').innerText);
        let hour = parseInt(document.getElementById('hour').innerText);
        let minute = parseInt(document.getElementById('minute').innerText);

        // Constants and Parameters for canvas and orbits
        const canvas = document.getElementById('orbitaCanvas');
        const ctx = canvas.getContext('2d');
        const scale = 108 / AU;
        const orbitLeea = 80;
        const orbitMyrhan = 140;
        // Perihelion day as start of year and Aphelion as mid-year
        const perihelionDay = 0;
        const aphelionDay = Math.floor(anthalParams.orbitalPeriod / 2);

        // Display function for converting day to Anthalian date
        function displayDate(dayOfYear) {
            const month = Math.floor(dayOfYear / 24) + 1;
            const day = dayOfYear % 24 + 1;
            return `Day ${day}/${month}`;
        }

        // Display Perihelion and Aphelion dates
        document.getElementById("perihelion-date").innerText = displayDate(perihelionDay);
        document.getElementById("aphelion-date").innerText = displayDate(aphelionDay);

        // Display function for converting day to Anthalian date

        const seasons = ["Luminara", "White Tide", "Golden Twilight", "Cold Shadow", "Tide Awakening", "Dark Fertility"];
        const seasonalColors = {
            Luminara: {
                dawn: ["#ffb347", "#ffd700"],
                day: ["#ffe135", "#ffeb3b"],
                sunset: ["#ff8c00", "#ff4500"],
                night: ["#2c3e50", "#1b2631"]
            },
            "White Tide": {
                dawn: ["#ffdfba", "#ffd700"],
                day: ["#ffec99", "#ffd500"],
                sunset: ["#ffa07a", "#dc143c"],
                night: ["#1e2f4f", "#14243e"]
            },
            "Golden Twilight": {
                dawn: ["#ffcc80", "#ff9800"],
                day: ["#ffc107", "#ffeb3b"],
                sunset: ["#ff5722", "#e65100"],
                night: ["#2e4053", "#1b2631"]
            },
            "Cold Shadow": {
                dawn: ["#b0bec5", "#90caf9"],
                day: ["#64b5f6", "#2196f3"],
                sunset: ["#37474f", "#263238"],
                night: ["#1b2631", "#0f1626"]
            },
            "Tide Awakening": {
                dawn: ["#ffab91", "#ff7043"],
                day: ["#ffcc80", "#ffa726"],
                sunset: ["#ff5722", "#d84315"],
                night: ["#263238", "#1b2631"]
            },
            "Dark Fertility": {
                dawn: ["#f48fb1", "#d81b60"],
                day: ["#ce93d8", "#ab47bc"],
                sunset: ["#8e24aa", "#6a1b9a"],
                night: ["#311b92", "#1a237e"]
            }
        };

        const colorTransitions = [{
                label: "Deep Night",
                colors: ["#0b1d3d", "#0b1d3d"]
            },
            {
                label: "Astronomical Dawn",
                colors: ["#0b1d3d", "#243856", "#506482"]
            },
            {
                label: "Nautical Dawn",
                colors: ["#243856", "#506482", "#90caf9"]
            },
            {
                label: "Blue Dawn (8.67°)",
                colors: ["#506482", "#607d8b", "#90caf9"]
            },
            {
                label: "Civil Dawn",
                colors: ["#607d8b", "#90caf9", "#b0bec5"]
            },
            {
                label: "Blue Dawn (4.33°)",
                colors: ["#90caf9", "#ffcc80", "#ffd54f"]
            },
            {
                label: "True Dawn",
                colors: ["#ffcc80", "#ffd54f", "#ffeb3b"]
            },
            {
                label: "Golden Dawn",
                colors: ["#ffd54f", "#ffeb3b", "#ffd54f"]
            },
            {
                label: "Solar Noon",
                colors: ["#ffeb3b", "#ffeb3b", "#ffd54f"]
            },
            {
                label: "Golden Sunset",
                colors: ["#ffd54f", "#ffcc80", "#ff8a65"]
            },
            {
                label: "True Sunset",
                colors: ["#ff8a65", "#ff5722", "#f44336"]
            },
            {
                label: "Blue Sunset (4.33°)",
                colors: ["#f44336", "#3b5998", "#243856"]
            },
            {
                label: "Civil Sunset",
                colors: ["#3b5998", "#243856", "#1e3a5f"]
            },
            {
                label: "Blue Sunset (8.67°)",
                colors: ["#243856", "#1e3a5f", "#0b1d3d"]
            },
            {
                label: "Nautical Sunset",
                colors: ["#1e3a5f", "#0b1d3d", "#0b1d3d"]
            },
            {
                label: "Astronomical Night",
                colors: ["#0b1d3d", "#0b1d3d"]
            }
        ];

        const seasonalTextColors = {
            Luminara: "#333333",
            "White Tide": "#2e2e2e",
            "Golden Twilight": "#1a1a1a",
            "Cold Shadow": "#e0e0e0",
            "Tide Awakening": "#444444",
            "Dark Fertility": "#e6e6e6"
        };

        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;

        // Update rotation and lighting for day-night simulation
        function updateRotationAndLighting() {
            const currentHourFraction = hour + (minute / 60);
            const rotationAngle = (currentHourFraction / 28) * 360;
            anthal.style.transform = `translate(-50%, -50%) rotate(${rotationAngle}deg)`;

            const nijelAngleRad = (currentHourFraction / 28) * 2 * Math.PI;
            nijel.style.left = `${150 + 100 * Math.cos(nijelAngleRad)}px`;
            nijel.style.top = `${150 + 100 * Math.sin(nijelAngleRad)}px`;

            const lightIntensity = Math.cos(nijelAngleRad);
            anthal.style.background = `rgba(0, 0, 128, ${0.5 * (1 - lightIntensity) + 0.5})`;

            const leeaAngleRad = (2 * Math.PI * currentHourFraction) / moonsParams.leea.orbitalPeriod;
            const myrhanAngleRad = (2 * Math.PI * currentHourFraction) / moonsParams.myrhan.orbitalPeriod;

            leea.style.left = `${50 + orbitLeea * Math.cos(leeaAngleRad)}px`;
            leea.style.top = `${50 + orbitLeea * Math.sin(leeaAngleRad)}px`;

            myrhan.style.left = `${50 + orbitMyrhan * Math.cos(myrhanAngleRad)}px`;
            myrhan.style.top = `${50 + orbitMyrhan * Math.sin(myrhanAngleRad)}px`;
        }

        // Draws Anthal's elliptical orbit
        function drawOrbit() {
            const semiMinorAxis = anthalParams.semimajorAxis * Math.sqrt(1 - Math.pow(anthalParams.eccentricity, 2)) *
                scale;
            const semiMajorAxis = anthalParams.semimajorAxis * scale;

            ctx.beginPath();
            ctx.ellipse(centerX, centerY, semiMajorAxis, semiMinorAxis, 0, 0, 2 * Math.PI);
            ctx.strokeStyle = "#607d8b";
            ctx.lineWidth = 2;
            ctx.stroke();
        }

        // Draw Nijel at the center of the orbit
        function drawNijel() {
            ctx.beginPath();
            ctx.arc(centerX, centerY, 10, 0, 2 * Math.PI);
            ctx.fillStyle = "#ffd700";
            ctx.fill();
        }

        // Calculate the elapsed time in seconds from the start of the Anthalian year
        function calculateYearTime() {
            const daysInYear = (month - 1) * 24 + day - 1;
            const hoursInDay = hour + (minute / 60);
            const timeInSeconds = (daysInYear * 28 + hoursInDay) * 3600;
            return timeInSeconds;
        }

        // Calculate Anthal's position along its elliptical orbit
        function calculateOrbitPosition(time) {
            const M = (2 * Math.PI * time) / anthalParams.orbitalPeriod;
            const E = solveKepler(M, anthalParams.eccentricity);

            const distance = anthalParams.semimajorAxis * (1 - anthalParams.eccentricity * Math.cos(E));
            const x = distance * Math.cos(E) * scale;
            const y = distance * Math.sin(E) * scale;

            return {
                x: centerX + x,
                y: centerY - y
            };
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

        // Draw Anthal in its calculated position
        function drawAnthal() {
            const time = calculateYearTime();
            const position = calculateOrbitPosition(time);

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            drawOrbit();
            drawNijel();

            ctx.beginPath();
            ctx.arc(position.x, position.y, 8, 0, 2 * Math.PI);
            ctx.fillStyle = "#90caf9";
            ctx.fill();
        }

        // Calculate base day and night temperature
        function calculateBaseTemperature() {
            const baseTemperature = Math.pow(((1 - anthalParams.albedo) * nijelParams.luminosity) / (16 * Math.PI * sigma *
                Math.pow(anthalParams.semimajorAxis, 2)), 1 / 4);
            return baseTemperature + anthalParams.greenhouse;
        }

        function getSeasonalTemperatureModifier(season) {
            const seasonalModifiers = {
                "Luminara": 5,
                "White Tide": 2,
                "Golden Twilight": 0,
                "Cold Shadow": -3,
                "Tide Awakening": -5,
                "Dark Fertility": -8
            };
            return seasonalModifiers[season] || 0;
        }

        function calculateDailyTemperature(hour, season) {
            const baseTemperature = calculateBaseTemperature();
            const seasonalModifier = getSeasonalTemperatureModifier(season);

            const diurnalVariation = Math.sin((Math.PI * hour) / 28);
            const dailyRange = 8;

            const currentTemperature = baseTemperature + seasonalModifier + (diurnalVariation * dailyRange);
            return currentTemperature.toFixed(2);
        }

        function updateTemperature() {
            const dayOfYear = ((month - 1) * 24) + day;
            const season = getSeason(dayOfYear);
            const temperature = calculateDailyTemperature(hour, season);
            document.getElementById('temperature-display').innerText = `${temperature} K`;
        }

        function calculateOrbitalPosition(period, currentTime) {
            const angle = (2 * Math.PI * (currentTime % period)) / period;
            return {
                x: Math.cos(angle),
                y: Math.sin(angle)
            };
        }

        function calculateLunarPhase(time, period) {
            const orbitFraction = (time % period) / period;
            if (orbitFraction < 0.125) return "New";
            else if (orbitFraction < 0.25) return "Waxing Crescent";
            else if (orbitFraction < 0.375) return "First Quarter";
            else if (orbitFraction < 0.5) return "Waxing Gibbous";
            else if (orbitFraction < 0.625) return "Full";
            else if (orbitFraction < 0.75) return "Waning Gibbous";
            else if (orbitFraction < 0.875) return "Last Quarter";
            else return "Waning Crescent";
        }

        const SECONDS_IN_ANTHAL_DAY = 28 * 60 * 60;
        const SECONDS_IN_EARTH_DAY = 24 * 60 * 60;

        function updateLunarPhases() {
            const earthTime = Date.now() / 1000;
            const anthalTime = earthTime * (SECONDS_IN_ANTHAL_DAY / SECONDS_IN_EARTH_DAY);

            const phaseLeea = calculateLunarPhase(anthalTime, moonsParams.leea.orbitalPeriod);
            const illuminationLeea = calculateLunarIlluminationPercentage(anthalTime, moonsParams.leea.orbitalPeriod);

            const phaseMyrhan = calculateLunarPhase(anthalTime, moonsParams.myrhan.orbitalPeriod);
            const illuminationMyrhan = calculateLunarIlluminationPercentage(anthalTime, moonsParams.myrhan.orbitalPeriod);

            document.getElementById("leea-phase").innerText = phaseLeea;
            document.getElementById("myrhan-phase").innerText = phaseMyrhan;
        }

        function checkEclipseOrConjunction(posAnthal, posMoon, moonDistance) {
            const distance = Math.sqrt((posMoon.x - posAnthal.x) ** 2 + (posMoon.y - posAnthal.y) ** 2) * AU;
            const eclipseLimit = 0.0001 * AU;
            if (distance < eclipseLimit) return "Eclipse";
            if (Math.abs(distance - moonDistance) < eclipseLimit) return "Conjunction";
            return null;
        }

        function updateAstronomicalEvents() {
            const earthTime = Date.now() / 1000;
            const anthalTime = earthTime * (SECONDS_IN_ANTHAL_DAY / SECONDS_IN_EARTH_DAY);

            const posAnthal = calculateOrbitalPosition(anthalParams.orbitalPeriod, anthalTime);
            const posLeea = calculateOrbitalPosition(moonsParams.leea.orbitalPeriod, anthalTime);
            const posMyrhan = calculateOrbitalPosition(moonsParams.myrhan.orbitalPeriod, anthalTime);

            const eventLeea = checkEclipseOrConjunction(posAnthal, posLeea, moonsParams.leea.semimajorAxis);
            const eventMyrhan = checkEclipseOrConjunction(posAnthal, posMyrhan, moonsParams.myrhan.semimajorAxis);

            document.getElementById("astronomical-event").innerText =
                `Leea: ${eventLeea || "No event"} | Myrhan: ${eventMyrhan || "No event"}`;
        }

        function getSeason(dayOfYear) {
            return seasons[Math.floor((dayOfYear - 1) / 72)];
        }

        function getSeasonalColorTransition(dayOfYear, hour, minute) {
            const season = getSeason(dayOfYear);
            const colors = seasonalColors[season];
            const timeFactor = (hour + minute / 60) / 28;
            let startColor, endColor;
            if (timeFactor < 0.25) {
                startColor = colors.night[1];
                endColor = colors.dawn[0];
            } else if (timeFactor < 0.5) {
                startColor = colors.dawn[1];
                endColor = colors.day[0];
            } else if (timeFactor < 0.75) {
                startColor = colors.day[1];
                endColor = colors.sunset[0];
            } else {
                startColor = colors.sunset[1];
                endColor = colors.night[0];
            }
            return interpolatedColor(startColor, endColor, (timeFactor % 0.25) * 4);
        }

        function updateSeasonalStyles() {
            const dayOfYear = ((month - 1) * 24) + day;
            const season = getSeason(dayOfYear);
            document.body.style.color = seasonalTextColors[season];
            updateSmoothBackground(hour, minute);
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
            document.getElementById('year').innerText = year;
            document.getElementById('month').innerText = month.toString().padStart(2, '0');
            document.getElementById('day').innerText = day.toString().padStart(2, '0');
            document.getElementById('hour').innerText = hour.toString().padStart(2, '0');
            document.getElementById('minute').innerText = minute.toString().padStart(2, '0');
            document.getElementById('season').innerText = getSeason(dayOfYear);
            updateLunarPhases();
            updateAstronomicalEvents();
            updateSeasonalStyles();
        }

        function findNextConjunction() {
            let currentDay = 1;
            while (true) {
                const leeaPhase = (currentDay % moonsParams.leea.period) / moonsParams.leea.period;
                const myrhanPhase = (currentDay % moonsParams.myrhan.period) / moonsParams.myrhan.period;

                if (Math.abs(leeaPhase - myrhanPhase) < 0.01) {
                    displayEventDate("conjunction-date", currentDay, "Conjunction");
                    break;
                }
                currentDay++;
                if (currentDay > anthalParams.yearLength) break;
            }
        }

        function findNextSuperEclipse() {
            let currentDay = 1;
            while (true) {
                const leeaPhase = (currentDay % moonsParams.leea.period) / moonsParams.leea.period;
                const myrhanPhase = (currentDay % moonsParams.myrhan.period) / moonsParams.myrhan.period;

                const areBothNewOrFull = (leeaPhase < 0.01 || leeaPhase > 0.99) &&
                    (myrhanPhase < 0.01 || myrhanPhase > 0.99);

                if (Math.abs(leeaPhase - myrhanPhase) < 0.01 && areBothNewOrFull) {
                    displayEventDate("super-eclipse-date", currentDay, "Super Eclipse");
                    break;
                }
                currentDay++;
                if (currentDay > anthalParams.yearLength) break;
            }
        }

        function findNextOpposition() {
            let currentDay = 1;
            while (true) {
                const leeaPhase = (currentDay % moonsParams.leea.period) / moonsParams.leea.period;
                const myrhanPhase = (currentDay % moonsParams.myrhan.period) / moonsParams.myrhan.period;

                if (Math.abs(leeaPhase - (1 - myrhanPhase)) < 0.01) {
                    displayEventDate("opposition-date", currentDay, "Opposition");
                    break;
                }
                currentDay++;
                if (currentDay > anthalParams.yearLength) break;
            }
        }

        function findNextEquinox() {
            const equinoxes = [128, 376]; // Hypothetical equinox days in the year
            const currentDay = getCurrentDay();
            const nextEquinox = equinoxes.find(day => day > currentDay) || equinoxes[0];
            displayEventDate("equinox-date", nextEquinox, "Equinox");
        }

        function findNextSolstice() {
            const solstices = [0, 252]; // Hypothetical solstice days in the year
            const currentDay = getCurrentDay();
            const nextSolstice = solstices.find(day => day > currentDay) || solstices[0];
            displayEventDate("solstice-date", nextSolstice, "Solstice");
        }

        function displayEventDate(elementId, dayOfYear, eventType) {
            const month = Math.floor(dayOfYear / 24) + 1;
            const day = dayOfYear % 24 + 1;
            document.getElementById(elementId).innerText = `Year 0, Day ${day}/${month}`;
        }

        function getCurrentDay() {
            // Ottieni il tempo terrestre in secondi
            const earthTime = Date.now() / 1000;

            // Conversione da secondi terrestri a giorni terrestri
            const earthDays = earthTime / (24 * 3600);

            // Conversione da giorni terrestri a giorni anthaliani
            const anthalDays = earthDays * (24 / ANTHAL_HOURS_IN_DAY);

            // Calcola il giorno corrente di Anthal
            const currentDay = Math.floor(anthalDays % ANTHAL_DAYS_IN_YEAR) + 1;

            return currentDay;
        }

        function calculateEclipse(orbitalPosition, distance, threshold) {
            return (distance < threshold) ? "Eclissi" : "Nessun evento";
        }

        function updateEclipses() {
            const earthTime = Date.now() / 1000;
            const anthalTime = earthTime * (SECONDS_IN_ANTHAL_DAY / SECONDS_IN_EARTH_DAY);

            const posAnthal = calculateOrbitalPosition(anthalParams.orbitalPeriod, anthalTime);
            const posLeea = calculateOrbitalPosition(moonsParams.leea.period, anthalTime);
            const posMyrhan = calculateOrbitalPosition(moonsParams.myrhan.period, anthalTime);

            const leeaEclipse = calculateEclipse(posLeea, anthalParams.semimajorAxis, 0.0001 * AU);
            const myrhanEclipse = calculateEclipse(posMyrhan, anthalParams.semimajorAxis, 0.0001 * AU);

            document.getElementById("leea-eclipse").innerText = leeaEclipse;
            document.getElementById("myrhan-eclipse").innerText = myrhanEclipse;
        }

        function calculateConjunction(pos1, pos2, threshold) {
            return (Math.abs(pos1 - pos2) < threshold) ? "Congiunzione" : "Nessun evento";
        }

        function updateConjunctions() {
            const earthTime = Date.now() / 1000;
            const anthalTime = earthTime * (SECONDS_IN_ANTHAL_DAY / SECONDS_IN_EARTH_DAY);

            const posLeea = calculateOrbitalPosition(moonsParams.leea.period, anthalTime);
            const posMyrhan = calculateOrbitalPosition(moonsParams.myrhan.period, anthalTime);
            const leeaMyrhanConjunction = calculateConjunction(posLeea, posMyrhan, 0.001);

            document.getElementById("conjunction-leea-myrhan").innerText = leeaMyrhanConjunction;
        }

        function calculateElongation(angle, threshold) {
            return (angle > threshold) ? "Elongazione" : "Nessun evento";
        }

        function updateElongations() {
            const earthTime = Date.now() / 1000;
            const anthalTime = earthTime * (SECONDS_IN_ANTHAL_DAY / SECONDS_IN_EARTH_DAY);

            const leeaAngle = calculateOrbitalPosition(moonsParams.leea.period, anthalTime).angle;
            const myrhanAngle = calculateOrbitalPosition(moonsParams.myrhan.period, anthalTime).angle;
            const leeaElongation = calculateElongation(leeaAngle, Math.PI / 2);
            const myrhanElongation = calculateElongation(myrhanAngle, Math.PI / 2);

            document.getElementById("elongation-leea").innerText = leeaElongation;
            document.getElementById("elongation-myrhan").innerText = myrhanElongation;
        }

        function calculateAlignment(angle1, angle2, angle3, threshold) {
            return (Math.abs(angle1 - angle2) < threshold && Math.abs(angle2 - angle3) < threshold) ? "Allineamento" :
                "Nessun evento";
        }

        function updateAlignments() {
            const earthTime = Date.now() / 1000;
            const anthalTime = earthTime * (SECONDS_IN_ANTHAL_DAY / SECONDS_IN_EARTH_DAY);

            const anthalAngle = calculateOrbitalPosition(anthalParams.orbitalPeriod, anthalTime).angle;
            const leeaAngle = calculateOrbitalPosition(moonsParams.leea.period, anthalTime).angle;
            const myrhanAngle = calculateOrbitalPosition(moonsParams.myrhan.period, anthalTime).angle;

            const fullAlignment = calculateAlignment(anthalAngle, leeaAngle, myrhanAngle, 0.01);

            document.getElementById("full-alignment").innerText = fullAlignment;
        }

        function calculateSyncedPhases(phase1, phase2) {
            return (phase1 === "Full" && phase2 === "New") || (phase1 === "New" && phase2 === "Full") ? "Fasi sincrone" :
                "Nessun evento";
        }

        function updateSyncedPhases() {
            const earthTime = Date.now() / 1000;
            const anthalTime = earthTime * (SECONDS_IN_ANTHAL_DAY / SECONDS_IN_EARTH_DAY);

            const leeaPhase = calculateLunarPhase(anthalTime, moonsParams.leea.period);
            const myrhanPhase = calculateLunarPhase(anthalTime, moonsParams.myrhan.period);
            const syncedPhases = calculateSyncedPhases(leeaPhase, myrhanPhase);

            document.getElementById("sync-phases").innerText = syncedPhases;
        }


        function calculateLunarIlluminationPercentage(anthalTime, period) {
            const orbitFraction = (anthalTime % period) / period;
            if (orbitFraction < 0.5) {
                return (orbitFraction * 2 * 100).toFixed(1) + "%";
            } else {
                return ((1 - orbitFraction) * 2 * 100).toFixed(1) + "%";
            }
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

        function getSmoothPhaseColors(hour, minute) {
            const totalMinutes = (hour * 60 + minute) / 28; // Normalize to Anthalian day length
            const phase = Math.floor(totalMinutes / 60) % colorTransitions.length; // Phase within colorTransitions
            const nextPhase = (phase + 1) % colorTransitions.length;

            const currentColors = colorTransitions[phase].colors;
            const nextColors = colorTransitions[nextPhase].colors;

            // Factor for more precise, gradual blending between colors
            const factor = (totalMinutes % 60) / 60;

            // Interpolate start and end colors for a smoother transition
            const interpolatedStart = interpolateColors(currentColors[0], nextColors[0], factor);
            const interpolatedEnd = interpolateColors(currentColors[1], nextColors[1], factor);

            return [interpolatedStart, interpolatedEnd];
        }

        // Function to update the background gradient smoothly based on time
        function updateSmoothBackground(hour, minute) {
            const [startColor, endColor] = getSmoothPhaseColors(hour, minute);
            document.body.style.background = `linear-gradient(180deg, ${startColor}, ${endColor})`;
        }

        setInterval(updateTimeDisplay, 1000);
        setInterval(updateTemperature);

        function updateSimulation() {
            drawAnthal();
            requestAnimationFrame(updateSimulation);
        }

        function calculateEvents() {
            // findNextConjunction();
            // findNextSuperEclipse();
            // findNextOpposition();
            findNextEquinox();
            findNextSolstice();
        }

        calculateEvents();
        updateSimulation();
        updateEclipses();
        updateConjunctions();
        updateElongations();
        updateAlignments();
        updateSyncedPhases();


        setInterval(updateRotationAndLighting, 1000);
        setInterval(updateSmoothBackground(hour, minute), 1000);
    </script>


</body>

</html>
