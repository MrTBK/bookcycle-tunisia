<?php

return [
    'app_name' => 'BookCycle Tunisia',
    'base_url' => getenv('APP_URL') ?: 'http://localhost/bookcycle-tunisia/public',
    'db' => [
        'dsn' => getenv('DB_DSN') ?: 'oci:dbname=//localhost:1521/XE;charset=AL32UTF8',
        'user' => getenv('DB_USER') ?: 'bookcycle_app',
        'password' => getenv('DB_PASSWORD') ?: 'BookCycle2026',
    ],
];
