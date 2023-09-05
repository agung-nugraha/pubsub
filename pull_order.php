<?php

require 'vendor/autoload.php';
require_once 'helper.php';

use Google\Cloud\PubSub\PubSubClient;

$pubSub = new PubSubClient([
    'projectId' => 'calm-scarab-299010',
    'keyFilePath' => 'pubsub.json'
]);

$start = microtime(true);
$subscriber = $pubSub->subscription("CNX_ORDER_FETCHED_3");

do {
	$next = false;

	$batchAck = array();

	$messages = $subscriber->pull();
	foreach ($messages as $message) {
		$order = json_decode((string) $message->data(), true);
		if (JSON_ERROR_NONE != json_last_error()) {
			echo "Invalid order data" . PHP_EOL;
			continue;
		}

		echo $message->data() . PHP_EOL;

		$batchAck[] = $message;
		$next = true;
	}

	if (!empty($batchAck)) {
		$subscriber->acknowledgeBatch($messages);
	}
} while ($next);

$end = microtime(true);
echo "Process running for " . ($end - $start) * 1000 . " milisecond";