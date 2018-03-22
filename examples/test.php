<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require dirname(dirname(__FILE__)) . '/init.php';

use Shoprunback\Shoprunback         as Shoprunback;
use Shoprunback\Elements\Brand      as Brand;
use Shoprunback\Elements\Product    as Product;
use Shoprunback\Elements\User       as User;
use Shoprunback\Elements\Item       as Item;
use Shoprunback\Elements\Order      as Order;
use Shoprunback\Elements\Shipback   as Shipback;
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
RestClient::getClient()->disableTesting();

// $brand = Brand::retrieve(1);
// $brand->printElementBody();
// $brand->name = 'toto';
// $brand->printElementBody();


// $product = Product::retrieve(1);
// $product->brand = Brand::retrieve(1);
// $product->printElementBody();

// $product = Product::retrieve(1);
// $product->brand = Brand::retrieve(1);
// $product->brand->name = 'mario';
// $product->printElementBody();

// $product = Product::retrieve(1);
// $brand = new Brand();
// $brand->name = 'toto';
// $brand->reference = 'toto';
// $product->brand = $brand;
// $product->printElementBody();

// $product = Product::retrieve('a529f86c-ece7-4880-95bc-1608ae18752b');
// $product->brand = Brand::retrieve('5c07f77f-6db7-41cc-95d2-5bbfdb10ddb6');
// var_dump($product->_origValues);
// var_dump($product->brand);
// $product->printElementBody();



// $brand = new Brand();
// $brand->name = 'lol';
// $brand->reference = 'lol';
// $brand->printElementBody();
// var_dump($brand->getDirtyKeys());

// echo '<br>____________________________________________________________________________________<br>';

// $product = new Product();
// $product->label = 'lol';
// $product->reference = 'lol';
// $product->brand = new Brand();
// $product->brand->name = 'nana';
// $product->brand->reference = 'nana';
// $product->printElementBody();
// var_dump($product->getDirtyKeys());

// echo '<br>____________________________________________________________________________________<br>';

// $product = new Product();
// $product->label = 'lol';
// $product->reference = 'lol';
// $product->brand = Brand::retrieve('b5ebd8d0-d223-40a1-8b0c-d54b1505a454');
// $product->printElementBody();
// var_dump($product->getDirtyKeys());

// echo '<br>____________________________________________________________________________________<br>';

// $product = Product::retrieve('a529f86c-ece7-4880-95bc-1608ae18752b');
// $product->brand->name = 'lol';
// $product->printElementBody();
// var_dump($product->getDirtyKeys());

// echo '<br>____________________________________________________________________________________<br>';


// $product = Product::retrieve('a529f86c-ece7-4880-95bc-1608ae18752b');
// $product->brand = Brand::retrieve('fe8e3e93-be16-46af-8a42-f80ec91ea7fe');
// $product->printElementBody();
// var_dump($product->getDirtyKeys());

// $product = Product::retrieve('a529f86c-ece7-4880-95bc-1608ae18752b');
// $product->brand = Brand::retrieve($product->brand->id);
// $product->printElementBody();
// var_dump($product->getDirtyKeys());

// echo '<br>____________________________________________________________________________________<br>';

// $product = Product::retrieve('a529f86c-ece7-4880-95bc-1608ae18752b');
// $product->brand = Brand::retrieve('b5ebd8d0-d223-40a1-8b0c-d54b1505a454');
// $product->brand->name = 'adad';
// $product->printElementBody();
// var_dump($product->getDirtyKeys());
// var_dump($product);

// echo '<br>____________________________________________________________________________________<br>';


$products = Product::all();
foreach ($products as $product) {
    echo($product->label) . "\n";
}

// $brands = Brand::all();
// echo $brands->count;
// foreach ($brands as $brand) {
//     echo($brand->name) . "\n";
// }
die;

$products = Product::all();
$product->brand_id = Brand::all()[1]->id;
$product->printElementBody();
var_dump($product->getDirtyKeys());





// $brand = Brand::retrieve('b5ebd8d0-d223-40a1-8b0c-d54b1505a454'); // PAUL
// var_dump($brand);
// var_dump($brand->_origValues);
// $brand->name = 'mamamamama';
// var_dump($brand);
// var_dump($brand->_origValues);
// $updatedBrand = Brand::update($brand);
// var_dump($updatedBrand);
// var_dump($updatedBrand->_origValues);
// $brand = Brand::retrieve('5d87e512-719b-44b2-8f5c-43cc6bb7b834'); // JULIEN
// Brand::delete('2a9f7ec0-2fa7-4758-be9c-5aa0abd8ed38');

// RestClient::getClient()->disableTesting();
// $brand = Brand::retrieve('b5ebd8d0-d223-40a1-8b0c-d54b1505a454'); // PAUL
// $brand->name = 'AH';
// Brand::update($brand);
// var_dump($brand);

// $brand = new Brand();
// $brand->id = 'toto';
// $brand->name = 'final fantasy';
// $brand->reference = 'final-fantasy';
// $brand->save();
// var_dump($createdBrand);

// $result = Brand::delete('05f044d4-e385-496c-a242-aae58e19df87');
// var_dump($result);


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

// $products = Product::all();
// var_dump($products);die;

// $product = new Product();
// $product->label = 'lebeau';
// $product->reference = rand();
// $product->weight_grams = 10000000;
// $product->brand = $brand;
// $createdProduct = Product::create($product);

// $product = new Product();
// $product->label = 'lebeau';
// $product->reference = rand();
// $product->weight_grams = 10000000;
// $product->brand = $brand;
// $createdProduct = Product::create($product);
// var_dump($createdProduct);

// $product = Product::retrieve('a529f86c-ece7-4880-95bc-1608ae18752b');
// var_dump($product);

// $product->ean = '789456123';
// $updatedProduct = Product::update($product);
// var_dump($updatedProduct);

// Product::delete(1234);




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

// Fetch a Shipback and display its content that must have the correct class name
// $shipback = Shipback::fetch('166bc3f3-1a08-42b6-bc72-f731105f8785');
// var_dump($shipback->returned_items[0]);
// var_dump($shipback->order);
// var_dump($shipback->order->items[0]);
// var_dump($shipback->order->items[0]->product);
// var_dump($shipback->order->items[0]->product->brand);