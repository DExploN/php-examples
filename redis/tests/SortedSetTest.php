<?php


namespace tests;


class SortedSetTest extends RedisBase
{
    public function test()
    {
        $this->client->zadd('zst', ['one' => 1]);
        $this->client->zadd('zst', ['one' => 2]);
        $this->client->zadd('zst', ['two' => 1]);
        $this->client->zadd('zst', ['three' => 3]);

        $this->client->zrange('zst', 0, -1, ['WITHSCORES' => true]);

        $this->assertEquals(3, $this->client->zcard('zst'));

        $this->assertEquals(2, $this->client->zcount('zst', 2, 3));

        $this->client->zincrby('zst', 3, 'one');
        $this->assertEquals(5, $this->client->zscore('zst', 'one'));
    }
}