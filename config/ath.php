<?php

return [
    'currency' => [
        'name' => 'Athel',
        'symbol' => mb_chr(42802, 'UTF-8'), // Simbolo per Athel (U+A732)
        'decimals' => 2,
        'subunit' => 'Duni',
        'subunit_symbol' => chr(273), // Simbolo per Duni (U+0111 -> 273)
        'decimal_separator' => ';',
        'thousand_separator' => ' ',
    ],
    // Sistema duodecimale (dozzinale)
    'duodecimal' => [
        'hel_symbol' => mb_chr(581, 'UTF-8'), // Simbolo per Ʌ (hel) U+0245
        'dek_symbol' => mb_chr(440, 'UTF-8'), // Simbolo per Ƹ (dek) U+01B8
    ],
    // Alfabeto di Anthal
    'alphabet' => [
        'letters' => ['a', 'b', 'k', 'ĉ', 'd', 'e', 'f', 'g', 'ĝ', 'h', 'i', 'y', 'j', 'l', 'm', 'n', 'o', 'p', 'r', 's', 'x', 't', 'u', 'w', 'v', 'z'],
        'vowels' => ['a', 'e', 'i', 'y', 'o', 'u', 'w'],
        'consonants' => ['b', 'k', 'ĉ', 'd', 'f', 'g', 'ĝ', 'h', 'j', 'l', 'm', 'n', 'p', 'r', 's', 'x', 't', 'v', 'z'],
    ],
    'bank' => [
        'name' => 'Banca di Anthalys',
        'transfer_limit' => 5000, // Limite massimo per un singolo bonifico
        'commission_fee' => 1.00, // Costo per effettuare una transazione
        'loan_interest_rate' => 0.05, // Tasso d'interesse per i prestiti
        'loan_default_penalty' => 0.02, // Penalità per mancato pagamento del prestito (2%)
        'minimum_balance' => 10, // Saldo minimo per evitare commissioni extra
        'overdraft_limit' => -500, // Limite di scoperto (permette di andare in negativo fino a -500)
        'investment' => [
            'low_risk_return_rate' => 0.02, // 2% di rendimento per investimenti a basso rischio
            'medium_risk_return_rate' => 0.05, // 5% di rendimento per investimenti a medio rischio
            'high_risk_min_return_rate' => -0.10, // Minimo -10% per investimenti ad alto rischio
            'high_risk_max_return_rate' => 0.20, // Massimo 20% per investimenti ad alto rischio
        ],
    ],
    'wiki' => [
        'weights' => [
            'view' => 1.00,         // Peso per la visualizzazione
            'like' => 3.00,         // Peso per il "like"
            'comment' => 2.00,      // Peso per il commento
            'time_spent' => 0.12,   // Peso per ogni secondo di permanenza
        ],
    ]
];
