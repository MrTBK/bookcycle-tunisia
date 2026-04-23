<?php

// Central application configuration 
return [
    'app_name' => 'BookCycle Tunisia',
    // APP_URL 
    'base_url' => getenv('APP_URL') ?: 'http://localhost/bookcycle-tunisia/public',
    'db' => [
        // Oracle connection 
        'dsn' => getenv('DB_DSN') ?: 'oci:dbname=//localhost:1521/XE;charset=AL32UTF8',
        'user' => getenv('DB_USER') ?: 'bookcycle_app',
        'password' => getenv('DB_PASSWORD') ?: 'BookCycle2026',
    ],
];
