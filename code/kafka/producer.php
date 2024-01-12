<?php

declare(strict_types=1);

$conf = new \RdKafka\Conf();
$conf->set('bootstrap.servers', 'kafka:9092');
$conf->set('socket.timeout.ms', (string)50);
$conf->set('queue.buffering.max.messages', (string)20);
$conf->set('max.in.flight.requests.per.connection', (string)1);

$conf->set('log_level', (string)LOG_DEBUG);
//$conf->set('debug', 'all');

$conf->set('statistics.interval.ms', (string)1000);


$topicConf = new \RdKafka\TopicConf();
$topicConf->set('message.timeout.ms', (string)30000);
$topicConf->set('request.required.acks', (string)-1);
$topicConf->set('request.timeout.ms', (string)5000);


$producer = new \RdKafka\Producer($conf);


$topic = $producer->newTopic('partTopic', $topicConf);

$partition = RD_KAFKA_PARTITION_UA;
foreach (range(1,100) as $i){
    $payload = sprintf('payload-%d-%s', $partition,$i);
    $topic->producev($partition, 0, $payload, null, null,time()*1000 + 1000*120 );
    // trigger callback queues
    $producer->poll(100);
}




$producer->flush(-1);