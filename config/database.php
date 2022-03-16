<?php

// https://stackoverflow.com/a/49455413 - thank you!
$current = timezone_open(config('app.timezone'));
$utcTime = new \DateTime('now', new \DateTimeZone('UTC'));
$offsetInSecs = $current->getOffset($utcTime);
$hoursAndMins = gmdate('H:i', abs($offsetInSecs));
$tzHoursMinsOffset = $offsetInSecs >= 0 ? "+{$hoursAndMins}" : "-{$hoursAndMins}";

return [

    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', 3306),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => env('DB_PREFIX', ''),
            'strict' => env('DB_STRICT_MODE', true),
            'engine' => env('DB_ENGINE', null),
            'timezone' => $tzHoursMinsOffset,
        ],

    ],

    'migrations' => 'migrations',

];
