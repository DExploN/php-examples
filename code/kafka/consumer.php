<?php

declare(strict_types=1);



$conf = new RdKafka\Conf();
$conf->set('log_level', (string) LOG_DEBUG);
$conf->set('group.id', 'myConsumerGroup');

//$conf->set('debug', 'all');
$conf->set('bootstrap.servers', "kafka:9092");
$consumer = new \RdKafka\Consumer($conf);


$topicConf = new RdKafka\TopicConf();
$topicConf->set('auto.commit.interval.ms', '100');
//
//// Set where to start consuming messages when there is no initial offset in
//// offset store or the desired offset is out of range.
//// 'smallest': start from the beginning
$topicConf->set('auto.offset.reset', 'smallest');



$topic = $consumer->newTopic("playground2", $topicConf);

if (!$consumer->getMetadata(false, $topic, 2000)) {
    echo "Failed to get metadata, is broker down?\n";
    exit;
}
$topic->consumeStart(0, RD_KAFKA_OFFSET_STORED);

echo "consumer started" . PHP_EOL;
while (true) {
    $message = $topic->consume(0, 1000);
    if(!isset($message)){
        continue;
    }
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo "Received message: {$message->payload}\n";
            $topic->offsetStore($message->partition, $message->offset);
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "Reached end of partition\n";
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            echo "Timed out\n";
            break;
        default:
            throw new \Exception($message->errstr(), $message->err);
            break;
    }

}
