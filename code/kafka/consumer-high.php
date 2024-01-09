<?php

declare(strict_types=1);



$conf = new RdKafka\Conf();
$conf->set('bootstrap.servers', "kafka:9092");
$conf->set('log_level', (string) LOG_DEBUG);
$conf->set('group.id', 'myConsumerGroup');
$conf->set('enable.partition.eof', 'true');
$conf->set('auto.offset.reset', 'earliest');
//$conf->set('debug', 'all');

$consumer = new \RdKafka\KafkaConsumer($conf);
$consumer->subscribe(['partTopic']);
//
//// Set where to start consuming messages when there is no initial offset in
//// offset store or the desired offset is out of range.
//// 'smallest': start from the beginning

echo "consumer started" . PHP_EOL;
$start = microtime(true);
while (true) {
    $message = $consumer->consume(120000);
    if(!isset($message)){
        continue;
    }
    echo microtime(true)- $start;
    $start = microtime(true);
    echo PHP_EOL;
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo "Received message: {$message->payload}\n";
            $consumer->commit($message);
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
    sleep(1);
}
