<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/7/10
 * Time: 11:31
 */

return [
    'default' => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'pwd'      => env('REDIS_PASSWORD', null),
        'port'     => env('REDIS_PORT', 6379),
        'database' => 0,
        'prefix'   => 'ecology:example:'
    ],
    'other'   => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'pwd'      => env('REDIS_PASSWORD', null),
        'port'     => env('REDIS_PORT', 6379),
        'database' => 1,
    ]
];