<?php

// load Pancake
require_once('../Pancake.class.php');


/* Simple Mode */

$conditions = array(
  'first_name' => "Thomas",
  'age' => 20
);

$where = new Where( $conditions );

echo "Simple : " . $where->output();



echo "<br /><br />";


/* Complex Mode */

$conditions = array(
  array(
    'key'      => "first_name",
    'value'    => "Thomas"
  ),
  array(
    'key'     => "age",
    'value'   => 20,
    'compare' => "!="
  ),
  array(
    'key'     => "age",
    'value'   => 24,
    'logic'   => "OR"
  )
);

$where = new Where( $conditions );

echo "Complex : " . $where->output();
