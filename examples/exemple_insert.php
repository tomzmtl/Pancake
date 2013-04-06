<?php

// load Pancake
require_once('../Pancake.class.php');

// specify your databases credentials here
$pancake = new Pancake( "pancake", "root", "root", "localhost" );

// prepare your data
$data = array(
  'first_name' => "Brendan",
  'last_name'  => "Gallagher",
  'age'        => 20,
  'country'    => "Canada"
);

// execute insertion on the users table
$insert_id = $pancake->insert( "users", $data);


echo $data['first_name'] . " " . $data['last_name'] . " has been inserted with the ID $insert_id";