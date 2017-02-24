<?php
require_once('vendor/autoload.php');

return function($site, $pages, $page) {

	//handle cart logic + grab returned cart
	$cart = cart_logic();

	//generate a list of all available products to loop over
	$products = $pages->find('products')->children()->visible();

	return array(
	    'cart' => $cart,
	    'products' => $products
	  );
};