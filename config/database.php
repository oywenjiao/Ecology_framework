<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/5
 * Time: 14:54
 */
return [
    'driver'      => 'mysql',
    'host'        => env('DB_HOST', '127.0.0.1'),
    'port'        => env('DB_PORT', '3306'),
    'database'    => env('DB_DATABASE', 'forge'),
    'username'    => env('DB_USERNAME', 'forge'),
    'password'    => env('DB_PASSWORD', ''),
    'charset'     => 'utf8mb4',
    'collation'   => 'utf8mb4_unicode_ci',
    'prefix'      => env('DB_PREFIX', ''),
    'strict'      => true,
    'engine'      => null,
];