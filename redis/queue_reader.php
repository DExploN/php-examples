<?php
require __DIR__ . '/' . 'vendor/autoload.php';

$client = new Predis\Client([
    'scheme' => 'tcp',
    'host' => 'redis',
    'port' => 6379,
]);

while (true) {
    if ($res = $client->brpop(['ls', 'ls2'], 5)) {
        var_dump($res);
    } else {
        echo "Пусто " . PHP_EOL;
    }
}