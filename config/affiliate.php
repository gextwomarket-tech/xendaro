<?php

/*
 * Bareme de commissions du programme de parrainage/IB (voir xendaro-fox-plan.json,
 * page id 10 "affiliate-program" > stats_cards > "donnee statique de config pour le MVP").
 * Modifiable ici sans toucher au code des vues (resources/views/vitrine/affiliate-program.blade.php).
 */

return [
    'tiers' => [
        [
            'label' => 'Palier Bronze',
            'range' => "jusqu'à 10 filleuls actifs",
            'commission' => '20%',
        ],
        [
            'label' => 'Palier Argent',
            'range' => '11 à 50 filleuls actifs',
            'commission' => '30%',
        ],
        [
            'label' => 'Palier Or',
            'range' => '50+ filleuls actifs',
            'commission' => '40%',
        ],
    ],
];
