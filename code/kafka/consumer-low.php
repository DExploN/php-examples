<?php

declare(strict_types=1);



$conf = new RdKafka\Conf();
$conf->set('bootstrap.servers', "kafka:9092");
$conf->set('log_level', (string) LOG_DEBUG);
$conf->set('group.id', 'myConsumerGroup');
$conf->set('enable.partition.eof', 'true');
$conf->set('enable.auto.commit', 'false');
$conf->set('enable.auto.offset.store', 'false');
$conf->set('auto.commit.interval.ms', '10');
//$conf->set('debug', 'all');

$consumer = new \RdKafka\Consumer($conf);

$topicConf = new RdKafka\TopicConf();
$topicConf->set('enable.auto.commit', 'false');
$topicConf->set('auto.commit.interval.ms', '100');
$topicConf->set('auto.offset.reset', 'earliest');


$queue = $consumer->newQueue();
$topic = $consumer->newTopic("partTopic", $topicConf);



if (!$consumer->getMetadata(false, $topic, 2000)) {
    echo "Failed to get metadata, is broker down?\n";
    exit;
}

$topic->consumeQueueStart(0, RD_KAFKA_OFFSET_STORED,$queue);
$topic->consumeQueueStart(1, RD_KAFKA_OFFSET_STORED,$queue);

echo "consumer started" . PHP_EOL;
$start = microtime(true);
while (true) {
    $message = $queue->consume(10000);
    if(!isset($message)){
        continue;
    }
    echo microtime(true)- $start;
    $start = microtime(true);
    echo PHP_EOL;
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo "Received message: {$message->payload}\n";
            $topic->offsetStore($message->partition, $message->offset);
            break ;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "Reached end of partition\n";
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            echo "Timed out\n";
            break;
        default:
            $topic->consumeStop(0);
            $topic->consumeStop(1);
            throw new \Exception($message->errstr(), $message->err);
            break;
    }
    sleep(1);
}
