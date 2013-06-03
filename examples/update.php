<?php

// load Pancake
require_once('../Pancake.class.php');

// specify your databases credentials here
$pancake = new Pancake( "pancake", "root", "root", "localhost" );

$set   = array( 'age' => 24 );
$where = array( 'age' => 20 );

// execute insertion on the users table
$affected_rows = $pancake->update( "users", $set, $where );


echo "Table updated. $affected_rows rows affected.";