<?php
require __DIR__ . '/' . 'vendor/autoload.php';

$client = new Predis\Client([
    'scheme' => 'tcp',
    'host' => 'redis',
    'port' => 6379,
]);
$i = 1;
while (true) {
    $client->lpush('ls', [$i++]);
    sleep(1);
}