<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';


use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()->setHosts(["elasticsearch"])->build();

$params = [
    "index" => "article",
    "id" => 5,
    "body" => '{
  "title": "Веселые собаки",
  "content": "<p>Смешная история про собак<p>",
  "tags": [
    "котята",
    "смешная история"
  ],
  "published_at": "2014-09-14T20:44:42+00:00"
}'
];

var_dump($client->index($params));