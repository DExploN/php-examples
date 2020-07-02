<?php

namespace tests;

use Predis\Collection\Iterator\Keyspace;

class StringTest extends RedisBase
{

    public function testAppend()
    {
        $this->client->append('val', 'hello');
        $this->assertSame($this->client->get('val'), 'hello');
        $this->client->append('val', ' world');
        $this->assertSame($this->client->get('val'), 'hello world');
    }

    public function testDecr()
    {
        $this->client->set('val', 5);
        $this->client->decr('val');
        $this->assertEquals(4, $this->client->get('val'));
        $this->client->decrby('val', 3);
        $this->assertEquals(1, $this->client->get('val'));
    }

    public function testIncr()
    {
        $this->client->set('val', 5);
        $this->client->incr('val');
        $this->assertEquals(6, $this->client->get('val'));
        $this->client->incrby('val', 2);
        $this->assertEquals(8, $this->client->get('val'));
    }

    public function testMsetMget()
    {
        $data = ['val1' => 1, 'val2' => 2];
        $this->client->mset($data);
        $this->assertEquals(array_values($data), $this->client->mget(['val1', 'val2']));
    }

    public function testRange()
    {
        $this->client->set('val', 'hello');
        $this->assertEquals('hel', $this->client->getrange('val', 0, 2));
        $this->client->setrange('val', 0, 'row');
        $this->assertEquals('rowlo', $this->client->get('val'));
        $this->assertEquals(5, $this->client->strlen('val'));

    }

    public function testScan()
    {
        for ($i = 1; $i < 6; $i++) {
            $this->client->set("key$i", "val$i");
        }
        foreach (new Keyspace($this->client, 'key*') as $val) {
            var_dump($val);
        }
        $this->assertTrue(true);
    }


}