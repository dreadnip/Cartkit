<?php
require_once('vendor/autoload.php');

return function($site, $pages, $page) {
	
	/*
		Configuration
	*/

	//setup Stripe config array
	$stripe = array(
		"secret_key"      => "sk_test_BQokikJOvBiI2HlWgH4olfQ2",
		"publishable_key" => "pk_test_6pRNASCoBOKtIshFeQd4XMUh"
	);

	//set Stripe API key
	\Stripe\Stripe::setApiKey($stripe['secret_key']);

	/*
		Payment handling
	*/

	//grab the 2 container arrays from the Stripe checkout window (token and args)
	$token = $_POST['token'];
	$args = $_POST['args'];

	//take the data we need from the arrays
	$token_id = $token['id']; //The ID of the token representing the payment details
	$email  = $token['email']; //The email address the user entered during the Checkout process
	$billing_name  = $args['billing_name'];
	$address  = $args['billing_address_line1'];
	$zip  = $args['billing_address_zip'];
	$state  = $args['billing_address_state'];
	$city  = $args['billing_address_city'];
	$country  = $args['billing_address_country'];

	//prep the customer object for Stripe
	$customer = \Stripe\Customer::create(array(
	    'email' => $email,
	    'source'  => $token_id
	));

	//recount the total charge
	$final_amount = cart_logic(cart_calc_total($cart));

	//charge the customer
	$charge = \Stripe\Charge::create(array(
	    'customer' => $customer->id,
	    'amount'   => $final_amount,
	    'currency' => 'eur'
	));

	//redirect to success/fail page
	go('cart/paid');
	/*
	return array(
	    'test' => $_POST
	  );
	  */
};