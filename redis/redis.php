<?php

require __DIR__ . '/' . 'vendor/autoload.php';

$client = new Predis\Client([
    'scheme' => 'tcp',
    'host' => 'redis',
    'port' => 6379,
]);
$client->set('foo', 'bar');
$value = $client->get('foo');