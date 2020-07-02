<?php


namespace tests;


class ListTest extends RedisBase
{
    public function testLeft()
    {
        $this->client->lpush('ls', ['one', 'two']);
        $this->client->lpush('ls', ['three']);

        $this->assertEquals(['two', 'one'], $this->client->lrange('ls', 1, 2));
        $this->assertEquals('one', $this->client->lindex('ls', 2));
        $this->assertEquals('three', $this->client->lpop('ls'));
        $this->assertEquals('two', $this->client->lpop('ls'));
        $this->assertEquals('one', $this->client->lpop('ls'));
    }

    public function testRight()
    {
        $this->client->rpush('ls', ['one', 'two']);
        $this->client->rpush('ls', ['three']);

        $this->assertEquals(['two', 'three'], $this->client->lrange('ls', 1, 2));
        $this->assertEquals('three', $this->client->lindex('ls', 2));
        $this->assertEquals('three', $this->client->rpop('ls'));
        $this->assertEquals('two', $this->client->rpop('ls'));
        $this->assertEquals('one', $this->client->rpop('ls'));
    }

    public function testLTrim()
    {
        $this->client->rpush('ls', ['one', 'two', 'three']);
        $this->assertSame(['one', 'two', 'three'], $this->client->lrange('ls', 0, 2));
        $this->client->ltrim('ls', 1, 2);
        $len = $this->client->llen('ls');
        $this->assertSame(['two', 'three'], $this->client->lrange('ls', 0, $len - 1));
    }
}