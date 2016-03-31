<?php

$app['ec2'] = [
    'client' => [
        'version' => 'latest',
        'region' => '', // your EC2 region
        'credentials' => [
            'key' => '', // your auth API key,
            'secret' => '', // your secret API key
        ],
    ],
    'dryRun' => false,
];

// Twig config
$app['twig.options'] = [
    'auto_reload' => false,
    'cache' => __DIR__ . '/../cache/twig',
];
