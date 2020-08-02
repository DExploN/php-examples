<?php


require __DIR__ . '/vendor/autoload.php';

use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()->setHosts(["elasticsearch"])->build();

$params = [
    'index' => 'article',
    'id' => '1'
];
var_dump($client->get($params));