<?php

return [
    // Prefix for every organization table
    'prefix' => env('ORG_TABLE_PREFIX', 'orgs_'),

    // User model to relate for office position user
    'user' => \App\Models\User::class,
];
