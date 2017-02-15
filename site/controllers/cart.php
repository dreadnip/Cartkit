<?php
require_once('vendor/autoload.php');

return function($site, $pages, $page) {

	//grab cart from session
	$cart = cart_logic(get_cart());

	//generate a list of all available products to loop over
	$products = $pages->find('products')->children()->visible();

	return array(
	    'cart' => $cart,
	    'products' => $products
	  );
};