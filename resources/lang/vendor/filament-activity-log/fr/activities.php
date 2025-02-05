<?php

return [
    'title' => 'Historique des activités',

    'date_format' => 'j F, Y',
    'time_format' => 'H:i l',

    'filters' => [
        'date' => 'Date',
        'causer' => 'Initiateur',
        'subject_type' => 'Sujet',
        'subject_id' => 'ID du sujet',
        'event' => 'Action',
    ],
    'table' => [
        'field' => 'Champ',
        'old' => 'Ancien',
        'new' => 'Nouveau',
        'value' => 'Valeur',
        'no_records_yet' => 'Il n\'y a pas encore d\'entrées',
    ],
    'events' => [
        'created' => [
            'title' => 'Créé',
            'description' => 'Entrée créée',
        ],
        'updated' => [
            'title' => 'Mis à jour',
            'description' => 'Entrée mise à jour',
        ],
        'deleted' => [
            'title' => 'Supprimé',
            'description' => 'Entrée supprimée',
        ],
        'restored' => [
            'title' => 'Restauré',
            'description' => 'Entrée restaurée',
        ],
        'attached' => [
            'title' => 'Attaché',
            'description' => 'Entrée attachée',
        ],
        'detached' => [
            'title' => 'Détaché',
            'description' => 'Entrée détachée',
        ],
        // Vos événements personnalisés...
    ],
    'boolean' => [
        'true' => 'Vrai',
        'false' => 'Faux',
    ],
];
