<?php


namespace tests;


class SetTest extends RedisBase
{
    public function test()
    {
        $this->client->sadd('st', ['one']);
        $this->client->sadd('st', ['one']);
        $this->client->sadd('st', ['two']);
        $this->assertEquals(2, $this->client->scard('st'));
    }
}