<?php

// load Pancake
require_once('../Pancake.class.php');

// specify your databases credentials here
$pancake = new Pancake( "pancake", "root", "root", "localhost" );

// prepare your data
$where = array( 'id' => 44 );

// fetch data from table
$row = $pancake->getRow( "users", $where );


/* the getRow() method can return various response type, so be
 * sure to test every use case.
 *
 * Warning : both int(0) and bool(FALSE) can be returned.
*/
if ( is_array($row) ) // success
{
  foreach ( $row as $column => $value )
  {
    echo "$column : $value <br />";
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