<?php

error_reporting(- 1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define("TESTING", true);

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Those loadings are necessary to do a single file test
// Load root class
require_once __DIR__ . '/BaseTest.php';

// Load Base classes and traits
require_once __DIR__ . '/Elements/BaseElementTest.php';
require_once __DIR__ . '/Elements/BrandTrait.php';
require_once __DIR__ . '/Elements/ProductTrait.php';
require_once __DIR__ . '/Elements/Mocker/BaseMockerTest.php';
require_once __DIR__ . '/Elements/Api/BaseApiTest.php';

// ------------------------------------------------------------------------------
// FOR THE FOLOWING, SOME LOADS ARE REQUIRED FOR SOME TESTS BUT WILL FAIL OTHERS
// ------------------------------------------------------------------------------

// Needed to test ProductTest
// Will fail BrandTest
// require_once __DIR__ . '/Elements/Mocker/BrandTest.php';
// require_once __DIR__ . '/Elements/Api/BrandTest.php';
