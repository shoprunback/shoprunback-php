<?php

// Load root files
require dirname(__FILE__) . '/lib/Shoprunback.php';
require dirname(__FILE__) . '/lib/RestClient.php';
require dirname(__FILE__) . '/lib/RestResponse.php';
require dirname(__FILE__) . '/lib/RestMocker.php';
require dirname(__FILE__) . '/lib/ElementIterator.php';
require dirname(__FILE__) . '/lib/ElementManager.php';

// Load Traits
require dirname(__FILE__) . '/lib/Elements/traits/Retrieve.php';
require dirname(__FILE__) . '/lib/Elements/traits/All.php';
require dirname(__FILE__) . '/lib/Elements/traits/Update.php';
require dirname(__FILE__) . '/lib/Elements/traits/Create.php';
require dirname(__FILE__) . '/lib/Elements/traits/Delete.php';

// Load Elements
require dirname(__FILE__) . '/lib/Elements/NestedAttributes.php';
require dirname(__FILE__) . '/lib/Elements/Element.php';
// require dirname(__FILE__) . '/lib/Elements/ShoprunbackObject.php';
require dirname(__FILE__) . '/lib/Elements/Brand.php';
require dirname(__FILE__) . '/lib/Elements/Product.php';
// require dirname(__FILE__) . '/lib/Elements/Item.php';
// require dirname(__FILE__) . '/lib/Elements/Order.php';
// require dirname(__FILE__) . '/lib/Elements/Shipback.php';
// require dirname(__FILE__) . '/lib/Elements/User.php';

// Load Utils
require dirname(__FILE__) . '/lib/Util/Container.php';
require dirname(__FILE__) . '/lib/Util/Inflector.php';
require dirname(__FILE__) . '/lib/Util/Logger.php';

// Load Errors
require dirname(__FILE__) . '/lib/Error/Error.php';
require dirname(__FILE__) . '/lib/Error/UnknownApiToken.php';
require dirname(__FILE__) . '/lib/Error/NotFoundError.php';
require dirname(__FILE__) . '/lib/Error/RestClientError.php';
require dirname(__FILE__) . '/lib/Error/ElementNumberDoesntExists.php';
