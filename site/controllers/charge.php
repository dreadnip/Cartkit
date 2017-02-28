<?php
require_once('vendor/autoload.php');

return function($site, $pages, $page) {

	//set Stripe API key
	if($site->sandbox() == true){
		\Stripe\Stripe::setApiKey($site->stripe_testing_secret_key());
	}else{
		\Stripe\Stripe::setApiKey($site->stripe_secret_key());
	}
	
	/*
		Payment handling
	*/

	//grab the 2 container arrays from the Stripe checkout window (token and args)
	//header('Content-Type: application/json');
	$json = file_get_contents('php://input');
	$obj = json_decode($json);

	$token = $obj->token;
	$args = $obj->args;

	//take the data we need from the arrays
	$token_id = $token->id; //The ID of the token representing the payment details
	$email  = $token->email; //The email address the user entered during the Checkout process
	$billing_name  = $args->billing_name;
	$address  = $args->billing_address_line1;
	$zip  = $args->billing_address_zip;
	$state  = $args->billing_address_state;
	$city  = $args->billing_address_city;
	$country  = $args->billing_address_country;

	//prep the customer object for Stripe
	$customer = \Stripe\Customer::create(array(
	    'email' => $email,
	    'source'  => $token_id
	));

	//recount the total charge
	$total = cart_calc_total();
  	$postage = cart_postage($total);
  	$postage_in_cents = price_to_cents($postage);
  	$final_amount = $total+$postage;
  	$final_amount_in_cents = price_to_cents($final_amount);

	//charge the customer
	$charge = \Stripe\Charge::create(array(
	    'customer' => $customer->id,
	    'amount'   => $final_amount_in_cents,
	    'currency' => 'eur'
	));

	//grab the transaction id to link to later
	$stripe_id = $charge->id;

	//adding an order record in the sqlite database
	$order_id = add_order($billing_name, $email, $stripe_id, $final_amount_in_cents, $postage_in_cents);

	//redirect to success/fail page
	go('cart/paid');
};