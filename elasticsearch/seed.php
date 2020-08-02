<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';


use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()->setHosts(["elasticsearch"])->build();

$faker = Faker\Factory::create();

for ($i = 1; $i < 1000; $i++) {
    $params = [
        "index" => "seed",
        "id" => $i,
        "body" => [
            'title' => $faker->title,
            'content' => $faker->sentence,
            'created_at' => date("c", time() - $i * 60 * 60 * $faker->numberBetween(1, 5))
        ]
    ];
    $client->index($params);
}

