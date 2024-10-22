document.addEventListener('DOMContentLoaded', function () {
    const seasonSelect = document.querySelector('select[name="season_number"]');
    const episodeList = document.getElementById('episode-list');

    // Funzione per ricaricare la lista degli episodi dopo una modifica (come "Visto" o "Non visto")
    function reloadEpisodeList(tvId, seasonNumber) {
        fetch(`/tv/${tvId}/episodes?season_number=${seasonNumber}`)
            .then(response => response.text())
            .then(html => {
                episodeList.innerHTML = html;
                addListeners(); // Riassegna i listener agli elementi appena caricati
            })
            .catch(error => {
                console.error('Errore nel caricamento degli episodi:', error);
                episodeList.innerHTML = '<p>Errore nel caricamento degli episodi.</p>';
            });
    }

    seasonSelect.addEventListener('change', function () {
        const seasonNumber = this.value;
        const tvId = "{{ $details['id'] }}"; // ID della serie TV

        // Effettua una chiamata AJAX per ottenere gli episodi della stagione selezionata
        reloadEpisodeList(tvId, seasonNumber);
    });

    // Funzione per aggiungere i listener
    function addListeners() {
        const checkIcons = document.querySelectorAll('.check-icon');

        checkIcons.forEach(icon => {
            icon.addEventListener('click', function () {
                const episodeId = this.getAttribute('data-episode-id');

                // Fai una chiamata AJAX per ottenere le date di visualizzazione
                fetch(`/episode/${episodeId}/watched-dates`)
                    .then(response => response.json())
                    .then(data => {
                        const watchedDatesList = document.getElementById('watched-dates-list');
                        watchedDatesList.innerHTML = '';

                        // Ordina le date in ordine crescente
                        const sortedDates = data.sort((a, b) => new Date(a.watched_at) - new Date(b.watched_at));

                        // Inserisci le date nel modal
                        sortedDates.forEach(date => {
                            const listItem = document.createElement('li');
                            listItem.classList.add('list-group-item');
                            listItem.textContent = `Visualizzato il: ${new Date(date.watched_at).toLocaleDateString()} - Durata: ${date.duration} minuti`;
                            watchedDatesList.appendChild(listItem);
                        });

                        // Apri il modal
                        const watchedDatesModal = new bootstrap.Modal(document.getElementById('watchedDatesModal'));
                        watchedDatesModal.show();
                    })
                    .catch(error => console.error('Errore nel caricamento delle date:', error));
            });
        });

        const unwatchButtons = document.querySelectorAll('.mark-unwatched');

        unwatchButtons.forEach(button => {
            button.addEventListener('click', function () {
                const episodeId = this.getAttribute('data-episode-id');
                const tvId = "{{ $details['id'] }}"; // ID della serie TV
                const seasonNumber = seasonSelect.value;

                // Chiedi conferma prima di eliminare l'episodio
                if (confirm('Sei sicuro di voler segnare questo episodio come non visto?')) {
                    // Fai una chiamata AJAX per eliminare l'episodio da 'watched_episodes'
                    fetch(`/unwatch-episode/${episodeId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Ricarica la lista degli episodi per aggiornare l'interfaccia
                                reloadEpisodeList(tvId, seasonNumber);
                            } else {
                                alert('Errore durante l\'eliminazione dell\'episodio.');
                            }
                        })
                        .catch(error => console.error('Errore:', error));
                }
            });
        });

        const watchButtons = document.querySelectorAll('.mark-watched');

        watchButtons.forEach(button => {
            button.addEventListener('click', function () {
                const episodeId = this.getAttribute('data-episode-id');
                const tvId = "{{ $details['id'] }}"; // ID della serie TV
                const seasonNumber = seasonSelect.value;

                fetch('/mark-as-watched', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        episode_id: episodeId,
                        watched_at: new Date().toISOString().split('T')[0] // Usa la data corrente
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Ricarica la lista degli episodi per aggiornare l'interfaccia
                            reloadEpisodeList(tvId, seasonNumber);
                        } else {
                            alert('Errore durante l\'aggiornamento dell\'episodio.');
                        }
                    })
                    .catch(error => console.error('Errore:', error));
            });
        });
    }
    const watchedDatesModalElement = document.getElementById('watchedDatesModal');

    if (watchedDatesModalElement) {
        const watchedDatesModal = new bootstrap.Modal(watchedDatesModalElement);
        // Ora puoi utilizzare watchedDatesModal.show() dove necessario
    } else {
        console.error('Il modal non è presente nella pagina.');
    }

    // Aggiungi i listener iniziali
    addListeners();
});
