#PHP library to ease the usage of the API of ShopRunBack

To use, simply put the whole folder into your libraries folder and require the init.php file.

Next, you can call all the objects by calling the objects with their namespace (or by using `use`). Sample: `use Shoprunback\\Resources\\User;`

Next, you can call all the objects by calling the objects with their namespace (or by using use). Sample: use Shoprunback\\Resources\\User

To run tests, go in your command line, then go to the root folder and execute this line:
```phpunit```
If you want to run a precise test:
```phpunit path/to/FileTest.php```
If you want to run your tests on a local environment, copy the ```phpunit.xml``` into a new file, set your custom vars and run:
```phpunit -c my_phpunit_file.xml```
