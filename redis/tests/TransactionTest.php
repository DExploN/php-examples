<?php


namespace tests;


class TransactionTest extends RedisBase
{

    public function testTransaction()
    {
        $this->client->set('key', 1);
        $trans = $this->client->transaction();
        $trans->multi();
        $trans->incrby('key', 3);
        $trans->incrby('key', 3);
        $trans->discard();
        $this->assertEquals(1, $this->client->get('key'));

        $trans->multi();
        $trans->incrby('key', 3);
        $trans->incrby('key', 3);
        $trans->exec();
        $this->assertEquals(7, $this->client->get('key'));

    }
}