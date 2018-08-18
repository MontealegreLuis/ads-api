<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

return [
    'debug' => true,
    'db' => [
        'connection' => [
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/../var/db.sqlite',
        ]
    ],
    'orm' => [
        'paths' => [
            __DIR__ . '/../src/CodeList/Posters/Ports/Resources',
            __DIR__ . '/../src/Application/DomainEvents/Ports/Resources',
        ]
    ],
    'log' => [
        'path' => __DIR__ . '/../var/log/app.log',
    ],
    'settings' => [
        'determineRouteBeforeAppMiddleware' => true,
    ],
];
