<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Divide una stringa utilizzando più delimitatori forniti.
 *
 * @param array  $delimiters  Un array contenente i delimitatori da usare per dividere la stringa.
 * @param string $input       La stringa da esplodere secondo i delimitatori.
 *
 * @return array Ritorna un array di stringhe risultanti dalla suddivisione dell'input.
 *               Se l'input è vuoto, ritorna un array vuoto. Se non ci sono delimitatori,
 *               ritorna un array contenente l'intera stringa come singolo elemento.
 */
function multiexplode(array $delimiters = [], string $input = ''): array
{
    // Se l'input è vuoto, ritorna un array vuoto
    if (empty($input)) {
        return [];
    }

    // Se i delimitatori sono vuoti, ritorna l'input come un singolo elemento
    if (empty($delimiters)) {
        return [$input];
    }

    // Creazione di un pattern regex dai delimitatori per effettuare la divisione
    $pattern = '/[' . preg_quote(implode('', $delimiters), '/') . ']/';

    // Divisione dell'input usando il pattern e ritorno dei risultati
    return preg_split($pattern, $input) ?: [$input];
}

/**
 * Genera o recupera un'immagine da Picsum in base alle dimensioni specificate
 * e la salva localmente per evitare download ripetuti.
 *
 * @param int $width  La larghezza desiderata dell'immagine.
 * @param int $height L'altezza desiderata dell'immagine.
 *
 * @return string Il percorso dell'immagine salvata localmente o un messaggio d'errore in caso di fallimento.
 */
function genImage(int $width, int $height): string
{
    try {
        return Cache::remember("images/{$width}x{$height}", 60, function () use ($width, $height) {
            do {
                $id = rand(1, 1000);
                $url = "https://picsum.photos/id/{$id}/{$width}/{$height}.webp";
                $response = Http::head($url);
            } while (!$response->ok());

            $imageContent = Http::get($url)->body();
            Storage::disk('public')->put("images/{$id}_{$width}x{$height}.webp", $imageContent);

            return "images/{$id}_{$width}x{$height}.webp";
        });
    } catch (Exception $e) {
        dd('Error generating image: ' . $e->getMessage());
        return 'Error generating image';
    }
}


/**
 * Ritorna lo stato di un campo specifico e il relativo colore in base al valore.
 * Il colore può essere invertito tramite l'argomento `$reversed`.
 *
 * @param string $field    Il campo del personaggio da valutare (es. 'salute', 'energia').
 * @param bool   $reversed Se true, inverte la logica dei colori (opzionale).
 *
 * @return array Un array associativo contenente 'status' e 'color':
 *               - 'status' è il valore del campo per il personaggio attuale.
 *               - 'color' è il colore associato allo stato (es. 'success', 'danger').
 */
function getStatus(string $field, bool $reversed = false): array
{
    // Recupera lo stato dal campo del personaggio autenticato
    $status = Auth::user()->character->$field ?? 0;

    // Usa la struttura match per assegnare il colore in base al valore dello status
    $color = match (true) {
        $status >= 80 => $reversed ? 'secondary' : 'success',
        $status >= 60 => $reversed ? 'danger' : 'primary',
        $status >= 40 => 'warning',
        $status >= 20 => $reversed ? 'primary' : 'danger',
        default => $reversed ? 'success' : 'secondary',
    };

    // Ritorna lo stato e il colore associato
    return ['status' => $status, 'color' => $color];
}

/**
 * Ritorna le impostazioni di configurazione della valuta.
 *
 * @return array Un array contenente i parametri di formattazione (decimali, separatori).
 */
function getCurrencyConfig(): array
{
    return [
        'decimals' => config('ath.currency.decimals', 2),
        'decimal_separator' => config('ath.currency.decimal_separator', ';'),
        'thousand_separator' => config('ath.currency.thousand_separator', ' ')
    ];
}

/**
 * Formatta un importo in Athel con il simbolo della valuta.
 *
 * @param float|null $amount L'importo da formattare. Se nullo, ritorna solo il simbolo della valuta.
 *
 * @return string L'importo formattato con il simbolo della valuta.
 */
function athel(?float $amount = null): string
{
    $symbol = config('ath.currency.symbol', 'Ꜳ');
    $decimals = config('ath.currency.decimals', 2);
    $decimalSeparator = config('ath.currency.decimal_separator', ';');
    $thousandSeparator = config('ath.currency.thousand_separator', ' ');

    if ($amount === null) {
        return $symbol;
    }

    return $symbol . ' ' . number_format($amount, $decimals, $decimalSeparator, $thousandSeparator);
}

/**
 * duni: Formatta gli importi in sotto-unità Duni.
 *
 * @param float|null $amount Importo da formattare
 * @return string Ritorna la stringa formattata in Duni
 */
function duni($amount = null): string
{
    return $amount !== null
        ? config('ath.currency.subunit_symbol') . ' ' . $amount
        : config('ath.currency.subunit_symbol');
}

/**
 * Converte un importo in Athel e lo formatta con suffissi (es. M per milioni).
 *
 * @param float $amount L'importo da convertire.
 *
 * @return string L'importo formattato con il suffisso appropriato.
 */
function toAthel(float $amount): string
{
    // Definisce i valori e i suffissi per la conversione
    $data = [
        1e30 => 'Q', // Quetta
        1e27 => 'R', // Ronna
        1e24 => 'Y', // Yotta
        1e21 => 'Z', // Zetta
        1e18 => 'E', // Exa
        1e15 => 'P', // Peta
        1e12 => 'T', // Tera
        1e9 => 'G', // Giga
        1e6 => 'M', // Mega
        1e3 => 'k', // kilo
        1e0 => ''
    ];

    // Ottiene i parametri di formattazione configurati
    $config = getCurrencyConfig();

    // Trova il suffisso corretto in base all'importo
    foreach ($data as $val => $suffix) {
        if ($amount >= $val) {
            return athel($amount / $val) . $suffix;
        }
    }

    // Se l'importo è troppo piccolo, ritorna la versione standard senza suffisso
    return athel($amount);
}

/**
 * Formatta un importo totale in Athel (parte intera) e Duni (sotto-unità).
 *
 * @param float $amount L'importo totale da formattare.
 *
 * @return string Ritorna l'importo formattato con Athel e Duni.
 *                La parte intera rappresenta gli Athel, mentre la parte decimale rappresenta i Duni.
 */
function fullAthel(float $amount): string
{
    // Estrae la parte intera dell'importo come Athel
    $athels = intval($amount);

    // Calcola la parte decimale (Duni) moltiplicando per 100 e arrotondando
    $duni = round(($amount - $athels) * 100);

    // Ritorna l'importo formattato con entrambe le unità (Athel e Duni)
    return athel() . ' ' . number_format(
        $athels,
        0,
        config('ath.currency.decimal_separator'),
        config('ath.currency.thousand_separator')
    ) . ' ' . duni() . ' ' . str_pad($duni, 2, '0', STR_PAD_LEFT);
}

/**
 * Genera l'URL di un Gravatar basato sull'indirizzo email fornito.
 * Ritorna l'HTML per l'immagine gravatar.
 *
 * @param string $email L'indirizzo email dell'utente.
 * @param int    $size  La dimensione desiderata dell'immagine (predefinita 80px).
 * @param string $set   Il set di gravatar da utilizzare (default 'robohash').
 * @param string $class Le classi CSS aggiuntive per l'elemento img (opzionale).
 * @param string $alt   Il testo alternativo dell'immagine (predefinito 'Gravatar').
 *
 * @return string L'HTML dell'immagine gravatar.
 */
function gravatar(string $email, int $size = 80, string $set = 'robohash', string $class = '', string $alt = 'Gravatar'): string
{
    // Calcola l'hash MD5 dell'indirizzo email per generare l'URL del gravatar
    $hash = md5(strtolower(trim($email)));
    $url = "https://www.gravatar.com/avatar/{$hash}?r=g&d={$set}&s={$size}";

    // Ritorna l'elemento <img> HTML con attributi sanitizzati
    return sprintf('<img src="%s" alt="%s" class="%s" />', htmlspecialchars($url), htmlspecialchars($alt), htmlspecialchars($class));
}

/**
 * Genera un'icona utilizzando gruppi di Bootstrap o FontAwesome.
 * Permette anche di visualizzare del testo accanto all'icona con formattazione personalizzata.
 *
 * @param string $icon    Il nome dell'icona da visualizzare.
 * @param string $group   Il gruppo a cui appartiene l'icona (es. 'bi' per Bootstrap, 'fas' per FontAwesome).
 * @param string $text    Il testo opzionale da mostrare accanto all'icona.
 * @param array  $options Le opzioni aggiuntive come 'class' per le classi CSS o 'format' per la formattazione.
 *
 * @return string L'HTML completo per l'icona con eventuale testo aggiuntivo.
 */
function getIcon(string $icon, string $group = 'bi', ?string $text = null, array $options = []): string
{
    // Definisce i gruppi validi
    $validGroups = ['bi', 'fas', 'far', 'fab'];

    // Verifica se il gruppo è valido, altrimenti imposta il gruppo di default
    $group = in_array($group, $validGroups) ? $group : 'bi';

    // Sanifica ulteriormente l'input dell'icona
    $icon = htmlspecialchars(trim($icon));

    // Costruisce la classe dell'icona in base al gruppo (Bootstrap o FontAwesome)
    $iconClass = $group === 'bi' ? 'bi bi-' . $icon : "{$group} fa-{$icon}";

    // Aggiunge eventuali classi CSS personalizzate
    if (isset($options['class'])) {
        $iconClass .= ' ' . htmlspecialchars($options['class']);
    }

    // Costruisce l'elemento HTML dell'icona
    $output = '<i class="' . htmlspecialchars($iconClass) . '"></i>';

    // Gestisce il testo opzionale da visualizzare accanto all'icona
    if ($text !== null) {
        $format = $options['format'] ?? ''; // Formato opzionale
        $position = $options['position'] ?? 'after'; // Posizione predefinita del testo

        // Sanitizza il testo opzionale
        $text = htmlspecialchars($text);

        // Aggiunge il testo in base alla posizione (prima o dopo l'icona)
        $output = $position === 'before'
            ? $text . ' <span class="pe-2"></span>' . $output . $format
            : $output . ' <span class="pe-2"></span>' . $text . $format;
    }

    return $output;
}

function getFileTypeIcon($extension)
{
    $iconMap = [
        'pdf' => 'filetype-pdf',
        'odt' => 'filetype-doc',
        'doc' => 'filetype-doc',
        'docx' => 'filetype-doc',
        'txt' => 'filetype-txt',
        'rtf' => 'filetype-txt',
        'xls' => 'filetype-xls',
        'xlsx' => 'filetype-xls',
        'ods' => 'filetype-xls',
        'csv' => 'filetype-txt',
        'ppt' => 'filetype-ppt',
        'pptx' => 'filetype-ppt',
        'odp' => 'filetype-ppt',
        'jpg' => 'filetype-image',
        'jpeg' => 'filetype-image',
        'png' => 'filetype-image',
        'gif' => 'filetype-image',
        'mp4' => 'filetype-video',
        'mov' => 'filetype-video',
        'ogg' => 'filetype-video',
        'webm' => 'filetype-video',
        'zip' => 'filetype-earmark-zip',
        'rar' => 'filetype-earmark-zip',
        // Icona generica per file non riconosciuti
        'default' => 'filetype-earmark'
    ];

    return $iconMap[strtolower($extension)] ?? $iconMap['default'];
}


/**
 * Verifica se la pagina corrente è attiva, confrontandola con l'URL fornito.
 * Se la pagina è attiva, viene aggiunta una classe CSS specifica (es. 'active').
 *
 * @param string|array $page        La pagina (o array di pagine) da confrontare con l'URL corrente.
 * @param string       $activeClass La classe CSS da aggiungere se la pagina è attiva (default: 'active').
 *
 * @return string Ritorna la classe CSS specificata se la pagina è attiva, altrimenti una stringa vuota.
 */
function getActivePage($page, string $activeClass = 'active'): string
{
    // Se $page è un array, controlla se uno degli elementi corrisponde alla pagina corrente
    if (is_array($page)) {
        // Usa array_map per ottenere tutti i match contemporaneamente
        $matches = array_map(fn($p) => Request::is($p), $page);
        if (in_array(true, $matches, true)) {
            return ' ' . $activeClass;
        }
    }

    // Se nessuna corrispondenza è trovata, ritorna una stringa vuota
    return '';
}

/**
 * Genera un colore casuale in formato esadecimale.
 *
 * @return string Ritorna un colore casuale nel formato '#RRGGBB'.
 */
function randomColor(): string
{
    $color = '#';

    // Genera tre componenti esadecimali (R, G, B) casuali
    foreach (range(1, 3) as $i) {
        $hex = dechex(rand(0, 255)); // Genera un numero esadecimale per ogni componente
        $color .= str_pad($hex, 2, '0', STR_PAD_LEFT); // Aggiunge lo zero iniziale se necessario
    }

    return $color;
}

/**
 * Ritorna l'icona associata a una professione specifica.
 * Utilizza classi CSS per indicare l'icona e un eventuale colore associato.
 *
 * @param string $professionName Il nome della professione (es. 'lumberjack', 'miner').
 *
 * @return string Ritorna l'HTML per l'icona della professione o un'icona predefinita se la professione non esiste.
 */
function getProfessionIcon(string $professionName): string
{
    // Mappa delle icone associate alle diverse professioni
    $icons = [
        'lumberjack' => 'tree text-success',
        'miner' => 'helmet-safety text-dark',
        'blacksmith' => 'hammer text-secondary',
        'farmer' => 'tractor text-warning',
        'fisherman' => 'fish text-primary',
        'hunter' => 'wheat-awn text-danger',
        'carpenter' => 'screwdriver text-info',
        'tailor' => 'scissors text-muted',
        'cook' => 'kitchen-set text-danger',
        'alchemist' => 'flask text-purple',
    ];

    // Converti il nome della professione in minuscolo per una corrispondenza corretta
    $professionName = strtolower($professionName);

    // Ritorna l'icona corrispondente o un'icona di default se la professione non è trovata
    return htmlspecialchars($icons[$professionName] ?? 'ban text-danger');
}


// Funzioni aggiuntive non utilizzate sono commentate
// Funzioni ottimizzate con gestione try...catch
