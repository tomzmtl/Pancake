<?php

// load Pancake
require_once('../Pancake.class.php');

// specify your databases credentials here
$pancake = new Pancake( "pancake", "root", "root", "localhost" );

// prepare your data
$where = array( 'country' => "Canada" );

// fetch data from table
$result = $pancake->selectAll( "users", $where );


/* the selectAll() method can return various return value types,
 * so be sure to test every use case.
 *
 * Warning : both int(0) and bool(FALSE) can be returned.
*/
if ( is_array($result) ) // success
{
  foreach ( $result as $r )
  {
    echo "<br />";
    foreach ( $r as $column => $value )
    {
      echo "$column : $value <br />";
    }
  }
}
elseif ( $row === 0 ) // empty result
{
  echo "Nothing to return";
}
elseif( $row === FALSE ) // query failure
{
  echo "The query failed";
}