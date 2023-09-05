<?php

require 'vendor/autoload.php';

use Google\Cloud\PubSub\PubSubClient;

$pubSub = new PubSubClient([
    // 'projectId' => 'calm-scarab-299010',
    // 'keyFilePath' => 'pubsub.json'
    'projectId' => 'sirclo-1152',
    'keyFilePath' => 'pubsub-staging.json'
]);

// prepare data
$files = json_decode(file_get_contents('data/good_received.json'), true);
// Get an instance of a previously created topic.
$topic = $pubSub->topic('goods-received');
foreach ($files as $key => $value) {
    $publish = $topic->publish([
        'data' => json_encode($value)
    ]);
    echo json_encode($publish) . PHP_EOL;
}