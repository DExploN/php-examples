<?php

declare(strict_types=1);

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once '../vendor/autoload.php';

$sleep = (int)$argv[1];
$message = isset($argv[1]) ? "{\"sleep\":$sleep}" : 'Hello World!';

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('hello', false, true, false, false);

$msg = new AMQPMessage($message, array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));
$channel->basic_publish($msg, '', 'hello');

echo " [x] Sent '$message'\n";

$channel->close();
$connection->close();