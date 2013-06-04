<?php

// load Pancake
require_once('../Pancake.class.php');

// specify your databases credentials here
$pancake = new Pancake( "pancake", "root", "root", "localhost" );

// prepare your data
$where = array( 'id' => 4 );

// fetch data from table
$var = $pancake->getVar( "users", "age", $where );

var_dump($var);