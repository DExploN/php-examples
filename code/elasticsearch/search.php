<?php


require __DIR__ . '/vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()->setHosts(["elasticsearch"])->build();

$params = [
    'index' => 'article',
];
print_r($client->search($params));