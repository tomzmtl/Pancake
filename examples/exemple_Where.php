<?php

// load Pancake
require_once('../Pancake.class.php');

// specify your databases credentials here
$pancake = new Pancake( "pancake_demo", "root", "root", "localhost" );

$where = array(
  'first_name' => "Thomas",
  'last_name'  => "Petate",
  'age'        => 20
);

new Where($where);