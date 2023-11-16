<?php

declare(strict_types=1);

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once '../vendor/autoload.php';


$keys = array_slice($argv, 2);

$queue_name = $argv[1] ?? null;

if (!$keys || !$queue_name) {
    die("Not send queue_name or keys");
}


$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('log-direct', 'direct', false, false, false);

$channel->queue_declare($queue_name, false, false, false, false);

foreach ($keys as $key) {
    $channel->queue_bind($queue_name, 'log-direct', $key);
}


echo " [*] Waiting for logs. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
    $decode = json_decode($msg->body, true);
    if (isset($decode['sleep'])) {
        sleep($decode['sleep']);
    }
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    echo ' [x] End Received ', $msg->body, "\n";
};

$channel->basic_consume($queue_name, '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

$channel->close();
$connection->close();