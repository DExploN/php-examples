<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Predis\Client;

class RedisBase extends TestCase
{
    protected $client;

    protected function setUp(): void
    {

        parent::setUp();
        $this->client = new Client([
            'scheme' => 'tcp',
            'host' => 'redis',
            'port' => 6379,
        ]);
    }


    protected function tearDown(): void
    {
        $this->client->flushall();
        $this->client = null;
    }

}