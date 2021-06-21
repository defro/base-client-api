<?php

use Example\AuthMiddleware\ApiClient\ExampleApi;
use Example\AuthMiddleware\ApiClient\Resources\OrderResource;


/**
 * Instantiating the API
 */
$params = [
    'isDevEn'       => true,
    'timeout'       => 999,
    'baseUri'       => 'https://example.com/',
    'client_id'     => 'client_id',
    'client_secret' => 'client_secret',
];

$api = new ExampleApi($params);


/**
 * Getting a element
 */
$orderId = 'xxxxxx';

/** @var OrderResource $order */
$order = $api->order()->get($orderId);
echo 'Order name:' . $order->name;


/**
 * Creating a element
 */

$newOrderParams   = [
    'name'             => 'xxxxx',
    'createdDate'      => 'xxxxx',
    'createdById'      => 'xxxxx',
    'lastModifiedDate' => 'xxxxx',
    'lastModifiedById' => 'xxxxx',
    'account'          => 'xxxxx',
    'telephone'        => 'xxxxx',
    'orderDate'        => 'xxxxx',
    'orderStatus'      => 'xxxxx',
    'grandTotal'       => 'xxxxx',
    'totalPaid'        => 'xxxxx',
    'subtotal'         => 'xxxxx',
    'discount'         => 'xxxxx',
    'taxAmount'        => 'xxxxx',
    'shippingAmount'   => 'xxxxx',
];
$newOrderResponse = $api->order()->create($newOrderParams);
if ($newOrderResponse->isSuccess()) {
    echo 'Order created';
}

if ($newOrderResponse->isFailed()) {
    $phase        = $newOrderResponse->getReasonPhrase();
    $code         = $newOrderResponse->getStatusCode();
    $responseBody = $newOrderResponse->getBody();
    $message      = $responseBody['error_message'];
    
    echo sprintf('Error on create a order. Api returned code: %s (%s).\\n %s',
        $phase, $code, $message);
}


$newOrder2            = new OrderResource();
$newOrder2->name      = 'xxxxxx';
$newOrder2->telephone = 'xxxxxx';
$newOrder2->contact   = 'xxxxxx';
$newOrder2->disputed  = 'xxxxxx';

$newOrder2Response = $api->order()->create($newOrder2->toArray());
if ($newOrder2Response->isSuccess()) {
    echo 'Order created';
} else {
//    ...
    echo 'Error on create a order ...';
}


/**
 * Updating a element
 */

$updOrderId     = 'xxxxxx';
$updOrderParams = [
    'name'      => 'yyyyyyy',
    'account'   => 'yyyyyyy',
    'telephone' => 'yyyyyyy',
];

$updOrderResponse = $api->order()->update($updOrderId, $updOrderParams);
if ($updOrderResponse->isSuccess()) {
    echo 'Order updated';
}
if ($updOrderResponse->isFailed()) {
    $phase        = $updOrderResponse->getReasonPhrase();
    $code         = $updOrderResponse->getStatusCode();
    $responseBody = $updOrderResponse->getBody();
    $message      = $responseBody['error_message'];
    
    echo sprintf('Error on update order %s. Api returned code: %s (%s).\\n %s',
        $updOrderId, $phase, $code, $message);
}

$updOrder2Id          = 'xxxxxx';
$updOrder2            = $api->order()->get($updOrder2Id);
$updOrder2->telephone = 'YYYYYY';
$updOrder2->contact   = 'YYYYYY';
$updOrder2->discount  = 'YYYYYY';

$updOrder2Response = $api->order()->update($updOrder2Id, $updOrder2->toArray());
if ($updOrder2Response->isSuccess()) {
    echo 'Order updated';
} else {
    echo 'Error on update a order ...';
}



