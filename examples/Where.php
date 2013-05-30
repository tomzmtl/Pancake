<?php

// load Pancake
require_once('../Pancake.class.php');

// specify your databases credentials here
$pancake = new Pancake( "pancake_demo", "root", "root", "localhost" );

$where = array(
  array(
    'key'      => "first_name",
    'value'    => "Thomas"
  ),
  array(
    'key'     => "age",
    'value'   => 20,
    'compare' => ">"
  ),
  array(
    'key'     => "age",
    'value'   => 24,
    'logic'   => "OR"
  )
);

$logic = array();

new Where( $where );