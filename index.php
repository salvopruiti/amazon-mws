<?php

require "vendor/autoload.php";


$config = [
    'Seller_Id' => 'A2Y2I1Q1G7ZD5Q',
    'Marketplace_Id' => 'APJ6JRA9NG5V4',
    'Access_Key_ID' => "AKIAILNSHCLBT6IWXQUA",
    'Secret_Access_Key' => "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",
    'MWSAuthToken' => null
];


$client = new Pruiti\AmazonMWS\Client($config);

$product = new \Pruiti\AmazonMWS\Models\FeesEstimateRequestElement();

$product->MarketplaceId = "APJ6JRA9NG5V4";
$product->IdValue = "B004D0WQXC";
$product->PriceToEstimateFees->ListingPrice->Amount = 100;
$product->PriceToEstimateFees->ListingPrice->CurrencyCode = "EUR";
$product->PriceToEstimateFees->Shipping->Amount = 0;
$product->PriceToEstimateFees->Shipping->CurrencyCode = "EUR";
$product->IsAmazonFulfilled = false;
$product->Identifier = "TEST";


print_r($client->getMyFeesEstimate($product));





