<?php
function get_cart() {
  s::start();
  $cart = s::get('cart', array());
  return $cart;
}

function cart_logic() {
  $cart = s::get('cart', array());

  if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    $id = $_REQUEST['id'];
    switch ($action) {
      case 'add':
      if (isset($cart[$id])) {
        $cart[$id]++;
      } else {
        $cart[$id] = 1;
      }
      break;
      case 'remove':
      if (isset($cart[$id])) {
        $cart[$id]--;
      } else {
        $cart[$id] = 1;
      }
      break;
      case 'update':
      if (isset($_REQUEST['quantity'])) {
        $quantity = intval($_REQUEST['quantity']);
        if ($quantity < 1) {
          unset($cart[$id]);
        } else {
          $cart[$id] = $quantity;                
        }
      }
      break;
      case 'delete':
      if (isset($cart[$id])) {
        unset($cart[$id]);
      }
      break;
    }
    s::set('cart', $cart);
  }

  return $cart;
}

function cart_count() {
  $cart = s::get('cart', array());

  $count = 0;
  foreach ($cart as $id => $quantity) {
    $count += $quantity;
  }
  return $count;
}

function cart_calc_total() {
  //initialize the $site and $pages variables so we can access all product pages
  $site  = site();
  $pages = $site->pages();
  $products = $pages->find('products')->children()->visible();
  //get the latest cart from the session
  $cart = s::get('cart', array());

  $count = 0; $total = 0;
  foreach($cart as $id => $quantity){
    if($product = $products->findByURI($id)){
      $count += $quantity;
      $prodtotal = floatval($product->price()->value)*$quantity;
      $total += $prodtotal;
    }
  }
  return $total;
}

function cart_postage($total) {
  $postage;
  switch ($total) {
    case ($total < 10):
    $postage = 2.5;
    break;
    case ($total < 30):
    $postage = 5.5;
    break;
    case ($total < 75):
    $postage = 8;
    break;
    case ($total < 150):
    $postage = 11.56;
    break;
    case ($total < 300):
    $postage = 28.30;
    break;
    default:
    $postage = 40.75;
  }
  return $postage;
}

function price_to_cents($int){
  return ($int*100);
}

function connect_to_db(){
  $site  = site();
  $db_path = kirby()->roots()->assets().'/data/orders.sqlite';
  return $db = new Database(array(
    'type'     => 'sqlite',
    'database' => $db_path
  ));
}

function get_latest_orders(){
  $db = connect_to_db();
  $orders = $db->table('orders');
  $results = $orders->limit(10)->all();
  $db = null;
  return $results;
}

function add_order($billing_name, $email, $customer_id, $total_with_shipping, $postage){
  $db = connect_to_db();
  $orders = $db->table('orders');
  if($id = $orders->insert(array(
    'billing_name' => $billing_name,
    'email'    => $email,
    'stripe_id' => $customer_id,
    'timestamp' => time(),
    'total_with_shipping'    => $total_with_shipping,
    'postage' => $postage
  ))) {
    //what to do after adding
    $db = null;
    return $id;
  }
}