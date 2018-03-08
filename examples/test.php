<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require dirname(__FILE__, 2) . '/init.php';

use Shoprunback\Shoprunback         as Shoprunback;
use Shoprunback\Resources\Brand     as Brand;
use Shoprunback\Resources\Product   as Product;
use Shoprunback\Resources\User      as User;
use Shoprunback\Resources\Item      as Item;
use Shoprunback\Resources\Order     as Order;
use Shoprunback\Resources\Shipback  as Shipback;
use Shoprunback\RestClient          as RestClient;
use Shoprunback\Util\Converter      as Converter;
use Shoprunback\Util\Logger         as Logger;
use Shoprunback\Util\Inflector      as Inflector;

// Setup for test
// Shoprunback::setApiBaseUrl(getenv('DASHBOARD_URL') . '/api/v1/');
// Shoprunback::setApiToken(getenv('SHOPRUNBACK_API_TOKEN'));

// Use this var to check if the exception is caught
// Shoprunback::setApiToken('afalsetoken');

// Fetch and update Brand
RestClient::getClient()->enableTesting();
// RestClient::getClient()->disableTesting();
// $brand = Brand::retrieve('b5ebd8d0-d223-40a1-8b0c-d54b1505a454'); // PAUL
// var_dump($brand);
// $brand = Brand::retrieve('5d87e512-719b-44b2-8f5c-43cc6bb7b834'); // JULIEN

// RestClient::getClient()->disableTesting();
// $brand = Brand::retrieve('b5ebd8d0-d223-40a1-8b0c-d54b1505a454'); // PAUL
// $brand->name = 'AH';
// Brand::update($brand);
// var_dump($brand);

// $brand = new Brand();
// $brand->name = 'final fantasy';
// $brand->reference = 'final-fantasy';
// $createdBrand = Brand::create($brand);
// var_dump($createdBrand);

$result = Brand::delete('05f044d4-e385-496c-a242-aae58e19df87');
var_dump($result);



// echo Inflector::classify('brand');
// echo Inflector::classify('brands');
// echo Inflector::classify('country');
// echo Inflector::classify('countries');

// echo Inflector::pluralize('brand');
// echo Inflector::pluralize('brands');#TODO
// echo Inflector::pluralize('country');
// echo Inflector::pluralize('countries');#TODO
// $brand->name = 'Delfino place 2';
// $brand->save();

// Fetch and update User
// $user = User::fetch();
// $user->first_name = $user->first_name . 'l';
// $user->save();

// Get a product and update Product
// $product = Product::fetch('pp');
// $product->label = 'PoppyProduct';
// $product->save();

// Save a brand with an already taken reference (must throw an exception)
// $brand = new Brand();
// $brand->name = 'Airship';
// $brand->reference = 'airship';
// try {
//     $brand->save();
// } catch (Error $e) {
//     echo $e;
// }

// Save a new brand with a new reference. It must not have an ID before being saved and have one after
// $brand = new Brand();
// $brand->name = 'Mario';
// $brand->reference = 'mario';
// var_dump($brand);
// $brand->save();
// var_dump($brand);

// Fetch an order and display it with its first item and brand
// $order = Order::fetch('poppytest');
// var_dump($order->display(), $order->items[0]->product->display(), $order->items[0]->product->brand->display());

// Fetch a Shipback and display its content that must have the correct class name
// $shipback = Shipback::fetch('166bc3f3-1a08-42b6-bc72-f731105f8785');
// var_dump($shipback->returned_items[0]);
// var_dump($shipback->order);
// var_dump($shipback->order->items[0]);
// var_dump($shipback->order->items[0]->product);
// var_dump($shipback->order->items[0]->product->brand);