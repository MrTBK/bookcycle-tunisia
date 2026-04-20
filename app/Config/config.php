<?php

// Central application configuration loaded by controllers and the database layer.
return [
    'app_name' => 'BookCycle Tunisia',
    // APP_URL lets the project switch cleanly between local and deployed environments.
    'base_url' => getenv('APP_URL') ?: 'http://localhost/bookcycle-tunisia/public',
    'db' => [
        // Oracle connection values can be overridden with environment variables when deployed.
        'dsn' => getenv('DB_DSN') ?: 'oci:dbname=//localhost:1521/XE;charset=AL32UTF8',
        'user' => getenv('DB_USER') ?: 'bookcycle_app',
        'password' => getenv('DB_PASSWORD') ?: 'BookCycle2026',
    ],
];
