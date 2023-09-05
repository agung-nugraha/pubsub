<?php

require 'vendor/autoload.php';

use Google\Cloud\PubSub\PubSubClient;

$pubSub = new PubSubClient([
    'projectId' => 'calm-scarab-299010',
    'keyFilePath' => 'pubsub.json'
    // 'projectId' => 'sirclo-1152',
    // 'keyFilePath' => 'pubsub-staging.json'
]);

// prepare data
$files = json_decode(file_get_contents('data/delivery_order.json'), true);
// Get an instance of a previously created topic.
$topic = $pubSub->topic('jwt');
foreach ($files as $key => $value) {
    $publish = $topic->publish([
        'data' => json_encode($value)
    ]);
    echo json_encode($publish) . PHP_EOL;
}