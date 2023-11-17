<?php

declare(strict_types=1);

$conf = new \RdKafka\Conf();
$conf->set('bootstrap.servers', 'kafka:9092');
$conf->set('socket.timeout.ms', (string)50);
$conf->set('queue.buffering.max.messages', (string)3);
$conf->set('max.in.flight.requests.per.connection', (string)1);

$conf->set('log_level', (string)LOG_DEBUG);
//$conf->set('debug', 'all');

$conf->set('statistics.interval.ms', (string)1000);


$topicConf = new \RdKafka\TopicConf();
$topicConf->set('message.timeout.ms', (string)30000);
$topicConf->set('request.required.acks', (string)-1);
$topicConf->set('request.timeout.ms', (string)5000);


$producer = new \RdKafka\Producer($conf);


$topic = $producer->newTopic('playground2', $topicConf);

$i = 1;
$key = $i % 10;
$payload = sprintf('payload-%d-%s', $i, $key);

$topic->produce(RD_KAFKA_PARTITION_UA, 0, $payload);

// trigger callback queues
$producer->poll(1);

$producer->flush(1000);