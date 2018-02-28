<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require dirname(__FILE__, 2) . '/init.php';

use Shoprunback\Shoprunback;
use Shoprunback\Resources\Brand;
use Shoprunback\Resources\Product;
use Shoprunback\Resources\User;
use Shoprunback\Resources\Item;
use Shoprunback\Resources\Order;
use Shoprunback\ApiCaller;
use Shoprunback\Util\Converter;
use Shoprunback\Util\Logger;

// Setup for test
Shoprunback::setApiBaseUrl(getenv('DASHBOARD_URL') . '/api/v1/');
Shoprunback::setApiToken(getenv('SHOPRUNBACK_API_TOKEN'));

// Use this var to check if the exception is caught
// Shoprunback::setApiToken('afalsetoken');

// Fetch and update Brand
// $brand = Brand::fetch('Fashion-Manufacturer');
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
