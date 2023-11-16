<?php


namespace tests;


class HashTest extends RedisBase
{
    public function test()
    {
        $this->client->hset('map', 'fld', 5);
        $this->assertEquals(5, $this->client->hget('map', 'fld'));
        $this->assertEquals(1, $this->client->hexists('map', 'fld'));
        $this->client->hdel('map', ['fld']);
        $this->assertEquals(0, $this->client->hexists('map', 'fld'));

        $this->client->hset('map', 'fld', 5);
        $this->client->hincrby('map', 'fld', 2);
        $this->assertEquals(7, $this->client->hget('map', 'fld'));

        $this->client->hset('map', 'fld2', 5);
        $this->assertEquals(['fld', 'fld2'], $this->client->hkeys('map'));

        $this->client->hset('map', 'fld3', 3);
        $this->assertEquals(3, $this->client->hlen('map'));
    }


}