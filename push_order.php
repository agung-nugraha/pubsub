<?php

require 'vendor/autoload.php';

use Google\Cloud\PubSub\PubSubClient;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

$pubSub = new PubSubClient([
    'projectId' => 'project-id',
    'keyFilePath' => 'pubsub-staging.json'
]);

// prepare data
$files = json_decode(file_get_contents('data/mp_adapter_order.json'), true);
// Get an instance of a previously created topic.
$topic = $pubSub->topic('CNX_ORDER_FETCHED_32');

$faker = Faker\Factory::create();
$remoteOrder = json_decode(file_get_contents('data/remote_order_id.json'), true);
$sku = json_decode(file_get_contents('data/sku.json'), true);

$date = Carbon::now()->format('Ymd');
$orders = array();
for ($i = 0; $i < 500; $i++) {
    $reference = time() + $i;

    $id = 'INV/' . date('Ymd') . '/MPL/' . $reference;

    $files['id'] = (string) Str::uuid();
    $files['order']['mp_adapter_order_id'] = $reference;
    $files['order']['order_date'] = Carbon::now()->format('Y-m-d\TH:i:s\Z');
    $files['order']['delivery_name'] = $faker->name();
    $files['order']['remote_order_id'] = $remoteOrder[$i] ?? $id;
    $files['order']['customer_reference'] = $reference;
    $files['order']['delivery_address_1'] = $faker->address();
    $files['order']['delivery_mobile'] = $faker->phoneNumber();
    $files['order']['order_status'] = $faker->randomElement(['pending']);
    $files['order']['phone_number'] = $faker->phoneNumber();

    foreach ($files['order']['line_items'] as $key => $item) {
        $files['order']['line_items'][$key]['product_code'] = $faker->randomElement($sku);
    }

    $orders[]['data'] = json_encode($files);
}

$publish = $topic->publishBatch($orders);
echo json_encode($publish) . PHP_EOL;
