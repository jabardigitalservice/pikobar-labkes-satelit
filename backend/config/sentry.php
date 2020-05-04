<?php

return [

    'dsn' => 'http://ef3b82de72e943d78d227a63f7b07b2c:d6ce0926f38e4d68ae79d78d81b75845@sentry.velotek.co.id/27',
    // 'dsn' => env('SENTRY_LARAVEL_DSN', env('SENTRY_DSN')),

    // capture release as git sha
    // 'release' => trim(exec('git --git-dir ' . base_path('.git') . ' log --pretty="%h" -n1 HEAD')),

    'breadcrumbs' => [
        // Capture Laravel logs in breadcrumbs
        'logs' => true,

        // Capture SQL queries in breadcrumbs
        'sql_queries' => true,

        // Capture bindings on SQL queries logged in breadcrumbs
        'sql_bindings' => true,

        // Capture queue job information in breadcrumbs
        'queue_info' => true,
    ],

];
