# PHP library for the ShopRunBack API

The ShopRunBack PHP library is designed to be a PHP overlay for the ShopRunBack API. For more information on how the library works, visit the [documentation](https://shoprunback.github.io/documentation/php.html).

## Install

Download the library, move it to your library folder and add this line in your `init.php` file.

`require dirname(__FILE__) . '/lib/shoprunback-php/init.php';`

You can now use the PHP library.

## Usage

Before using the library you must set your token with the RestClient.

`\Shoprunback\RestClient::getClient()->setToken('yourApiToken');`

In order to work with the different ShopRunBack elements, you will need to to call each object using their namespace, or with the keyword `use` (example: `use Shoprunback\\Elements\\User;`)