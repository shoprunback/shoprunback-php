<?php

// Load root files
require dirname(__FILE__) . '/lib/Shoprunback.php';
require dirname(__FILE__) . '/lib/RestClient.php';
require dirname(__FILE__) . '/lib/RestResponse.php';
require dirname(__FILE__) . '/lib/RestMocker.php';
require dirname(__FILE__) . '/lib/Pagination.php';

// Load resources
require dirname(__FILE__) . '/lib/Resources/traits/Retrieve.php';
require dirname(__FILE__) . '/lib/Resources/traits/All.php';
require dirname(__FILE__) . '/lib/Resources/traits/Update.php';
require dirname(__FILE__) . '/lib/Resources/traits/Create.php';
require dirname(__FILE__) . '/lib/Resources/traits/Delete.php';
require dirname(__FILE__) . '/lib/Resources/Resource.php';
// require dirname(__FILE__) . '/lib/Resources/ShoprunbackObject.php';
require dirname(__FILE__) . '/lib/Resources/Brand.php';
require dirname(__FILE__) . '/lib/Resources/Product.php';
// require dirname(__FILE__) . '/lib/Resources/Item.php';
// require dirname(__FILE__) . '/lib/Resources/Order.php';
// require dirname(__FILE__) . '/lib/Resources/Shipback.php';
// require dirname(__FILE__) . '/lib/Resources/User.php';

// Load Utils
require dirname(__FILE__) . '/lib/Util/Container.php';
require dirname(__FILE__) . '/lib/Util/Inflector.php';
require dirname(__FILE__) . '/lib/Util/Logger.php';

// Load Errors
require dirname(__FILE__) . '/lib/Error/Error.php';
require dirname(__FILE__) . '/lib/Error/UnknownApiToken.php';
require dirname(__FILE__) . '/lib/Error/NotFoundError.php';
require dirname(__FILE__) . '/lib/Error/RestClientError.php';
