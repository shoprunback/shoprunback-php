# PHP library for the ShopRunBack API

## Install

Download the library and move it to your library folder. Add the correct require in your init.php file. You can now use the PHP library.

## Usage

In order to work with the different shoprunback elements, you will need to to call each object using their namespace, or with the keyword `use` (example: ```use Shoprunback\\Elements\\User;```)

### Local development

The library comes with 2 environments preloaded (Sandbox and Production). To use it locally, simply set 2 environment variables:

- `SHOPRUNBACK_URL` : You local URL (ie: 'http://localhost:3000')
- `SHOPRUNBACK_TOKEN` that must contain your own ShopRunBack API token. **DO NOT SHARE IT OR PUSH IT ON GITHUB**

### Testing

To run tests, open your terminal, go to your project's root directory and run the command ```phpunit```

If you want to run a precise test, you need to specify the path to the test file like so ```phpunit path/to/FileTest.php```

If you want to run your tests on a local environment, copy the ```phpunit.xml``` into a new file, set your custom vars and run ```phpunit -c my_phpunit_file.xml```

# PHP Versions Compability

To test your code compatibility across older and newer PHP versions, run this command

```vendor/bin/phpcs --ignore=/vendor/ --standard=PHPCompatibility --extensions=php --runtime-set testVersion <version_to_check> <path_to_test>```

[More information on PHP compatibility](https://www.sitepoint.com/quick-intro-phpcompatibility-standard-for-phpcs-are-you-php7-ready/)
