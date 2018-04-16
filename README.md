# PHP library to ease the usage of the API of ShopRunBack

To use, simply put the whole folder into your libraries folder and require the init.php file.

Next, you can call all the objects by calling the objects with their namespace (or by using `use`). Sample: ```use Shoprunback\\Elements\\User;```

To run tests, go in your command line, then go to the root folder and execute this line:

```phpunit```

If you want to run a precise test:

```phpunit path/to/FileTest.php```

If you want to run your tests on a local environment, copy the ```phpunit.xml``` into a new file, set your custom vars and run:

```phpunit -c my_phpunit_file.xml```

# PHP Versions Compability

Doc: https://www.sitepoint.com/quick-intro-phpcompatibility-standard-for-phpcs-are-you-php7-ready/

To test if the code respect writing standards and is compatible with various PHP versions, run

```vendor/bin/phpcs --ignore=/vendor/ --standard=PHPCompatibility --extensions=php --runtime-set testVersion <version_to_check> <path_to_test>```

Example:

```vendor/bin/phpcs --ignore=/vendor/ --standard=PHPCompatibility --extensions=php --runtime-set testVersion 7.2 ./tests/```
