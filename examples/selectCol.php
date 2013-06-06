<?php

// load Pancake
require_once('../Pancake.class.php');

// specify your databases credentials here
$pancake = new Pancake( "pancake", "root", "root", "localhost" );

// prepare your data
$where = array( 'country' => "Canada" );

// fetch data from table
$col = $pancake->selectCol( "users", "first_name", $where );


/* the selectAll() method can return various return value types,
 * so be sure to test every use case.
 *
 * Warning : both int(0) and bool(FALSE) can be returned.
*/
if ( is_array($col) ) // success
{
  foreach ( $col as $c )
  {
    echo "<br />";
    echo "$c";
  }
}
elseif ( $col === 0 ) // empty result
{
  echo "Nothing to return";
}
elseif( $col === FALSE ) // query failure
{
  echo "The query failed";
}