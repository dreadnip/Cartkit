<?php 

return array(
  'title' => 'Latest orders',
  'html' => function() {
    return tpl::load(__DIR__ . DS . 'orders.html.php');
  }  
);