<?php

require 'vendor/autoload.php';

use Google\Cloud\PubSub\PubSubClient;

$pubSub = new PubSubClient([
    'projectId' => 'calm-scarab-299010',
    'keyFilePath' => 'pubsub.json'
]);

// prepare data
$files = json_decode(file_get_contents('data/product.json'), true);
// Get an instance of a previously created topic.
$topic = $pubSub->topic('MID_ITEM_NETSUITE');
$count = 1;
foreach ($files as $key => $value) {
    $publish = $topic->publish([
        'data' => json_encode($value)
    ]);
    echo json_encode($publish) . PHP_EOL;
    if ($count == 100) break;
    $count++;
}