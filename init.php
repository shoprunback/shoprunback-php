<?php

// Load root files
require dirname(__FILE__) . '/lib/Shoprunback.php';
require dirname(__FILE__) . '/lib/ApiCaller.php';

// Load resources
require dirname(__FILE__) . '/lib/Resources/Resource.php';
require dirname(__FILE__) . '/lib/Resources/ApiObject.php';
require dirname(__FILE__) . '/lib/Resources/Brand.php';
require dirname(__FILE__) . '/lib/Resources/Item.php';
require dirname(__FILE__) . '/lib/Resources/Order.php';
require dirname(__FILE__) . '/lib/Resources/Product.php';
require dirname(__FILE__) . '/lib/Resources/User.php';

// Load Utils
require dirname(__FILE__) . '/lib/Util/Converter.php';
require dirname(__FILE__) . '/lib/Util/Logger.php';

// Load Errors
require dirname(__FILE__) . '/lib/Error/Error.php';
require dirname(__FILE__) . '/lib/Error/ReferenceTaken.php';
require dirname(__FILE__) . '/lib/Error/UnknownApiToken.php';
