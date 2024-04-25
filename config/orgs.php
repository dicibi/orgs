<?php

return [
    // Prefix for every organization table
    'prefix' => env('ORG_DB_PREFIX', 'orgs_'),

    // User model to relate for office position user
    'user' => env('ORG_MODEL_USER', \App\Models\User::class),
];
