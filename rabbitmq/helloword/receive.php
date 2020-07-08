<?php

declare(strict_types=1);

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once '../vendor/autoload.php';

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->basic_qos(null, 1, null);
$channel->queue_declare('hello', false, true, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
    $decode = json_decode($msg->body, true);
    if (isset($decode['sleep'])) {
        sleep($decode['sleep']);
    }
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    echo ' [x] End Received ', $msg->body, "\n";
};

$channel->basic_consume('hello', '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();