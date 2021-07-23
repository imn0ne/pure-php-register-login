<?php

// Error reporting
error_reporting(0);

$GLOBALS['config'] = [
    'database' => [
        'host' => '127.0.0.1',
        'name' => 'pure_php_login_register',
        'username' => 'root',
        'password' => '',
    ],
    'session' => [
        'name' => 'user',
    ],
    'remember' => [
        'cookie_expiry' => 1400,
    ],
    
];
