<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */

    'column.name' => 'Nom',
    'column.guard_name' => 'Nom du Guard',
    'column.roles' => 'Rôles',
    'column.permissions' => 'Permissions',
    'column.updated_at' => 'Mis à jour à',

    /*
    |--------------------------------------------------------------------------
    | Form Fields
    |--------------------------------------------------------------------------
    */

    'field.name' => 'Nom',
    'field.guard_name' => 'Nom du Guard',
    'field.permissions' => 'Permissions',
    'field.select_all.name' => 'Tout sélectionner',
    'field.select_all.message' => 'Activer toutes les autorisations pour ce profil',

    /*
    |--------------------------------------------------------------------------
    | Navigation & Resource
    |--------------------------------------------------------------------------
    */

    'nav.group' => 'Sécurité',
    'nav.role.label' => 'Profils',
    'nav.role.icon' => 'heroicon-o-shield-check',
    'resource.label.role' => 'Profil',
    'resource.label.roles' => 'Profils',

    /*
    |--------------------------------------------------------------------------
    | Section & Tabs
    |--------------------------------------------------------------------------
    */

    'section' => 'Entités',
    'resources' => 'Ressources',
    'widgets' => 'Widgets',
    'pages' => 'Pages',
    'custom' => 'Permissions personnalisées',

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    'forbidden' => 'Vous n\'avez pas la permission d\'accéder',

    /*
    |--------------------------------------------------------------------------
    | Resource Permissions' Labels
    |--------------------------------------------------------------------------
    */

    'resource_permission_prefixes_labels' => [
        'view' => 'Voir',
        'view_any' => 'Voir tout',
        'create' => 'Créer',
        'update' => 'Modifier',
        'delete' => 'Supprimer',
        'delete_any' => 'Supprimer tout',
        'force_delete' => 'Forcer supprimer',
        'force_delete_any' => 'Forcer supprimer tout',
        'restore' => 'Restaurer',
        'replicate' => 'Repliquer',
        'reorder' => 'Reordonner',
        'restore_any' => 'Restaurer tout',
        'pass_validation_turn' => 'Passer tour de validation',
        'add_signataires' => 'Ajouter signataire(s)',
        'export_doc_for_signing' => 'Exporter doc. pour signature',
        'import_signed_doc' => 'Importer doc. signé'
    ],
];
