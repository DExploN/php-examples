<?php

declare(strict_types=1);

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once '../vendor/autoload.php';

$sleep = (int)$argv[1];
$message = isset($argv[1]) ? "{\"sleep\":$sleep}" : 'Hello World!';

$key = $argv[2] ?? null;

if (!$key) {
    die("Not send key");
}


$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('log-direct', 'direct', false, false, false);

$msg = new AMQPMessage($message);

$channel->basic_publish($msg, 'log-direct', $key);

echo ' [x] Sent ', $message, "\n";

$channel->close();
$connection->close();