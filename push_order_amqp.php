<?php

require 'vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

$exchange = 'router';
$queue = 'msgs';

$connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);

$channel = $connection->channel();
$channel->queue_declare($queue, durable: true);

$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
$channel->queue_bind($queue, $exchange);

$messageBody = implode(' ', array_slice($argv, 1));
$message = new AMQPMessage(
    $messageBody,
    [
        'content_type' => 'application/json',
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
    ]
);
$channel->basic_publish($message, $exchange);

$channel->close();
try {
    $connection->close();
} catch (Exception $e) {
}