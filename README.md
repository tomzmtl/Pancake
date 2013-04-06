# Pancake

Current version : __0.5__

A simple PDO-based MySQL abstraction class.

Inspired by the simplicity of ezSQL and WordPress DB Class.



## Getting Started

To start using Pancake, simply instantiate the class.
You'll have to provide your database credentials.

```php
<?php

  require_once('lib/Pancake.class.php');

  $pancake = new Pancake( DB_NAME, DB_USER, DB_PASS, DB_HOST );

?>
```



## Pancake Methods

Here are listed all methods provided by Pancake.



### insert( $table, $data )

Insert a single row into a table.


##### Parameters

  * `string` $table : Table to insert the data into.
  * `array` $data : Set of key/value pairs. Keys must match the table's columns names.


##### Return values

  * `int` Insert ID if insert is successful.
  * `bool(FALSE)` on failure.


##### Usage

Provide an array with key/value pairs matching your table's column names.

```php
<?php

  // prepare your data
  $data = array(
    'first_name' => "Brendan",
    'last_name'  => "Gallagher",
    'age'        => 20,
    'country'    => "Canada"
  );

  // execute insertion on the users table
  $pancake->insert( "users", $data);

?>
```



### delete( $table, $where)

Delete rows from a table.


##### Parameters

  * `string` $table : Table to delete the entry from.
  * `array` $where : A set of conditions to select the data to delete.


##### Return values

  * `int` Number of deleted entries.
  * `bool(FALSE)` if a problem occured.


##### Usage

Provide a set of conditions matching the entries to delete.

```php
<?php
  
  // delete the row with the ID 34

  $where = array(
    'id' => 34
  );

  $pancake->delete( "users", $where );

?>
```

To use multiple conditions, use a multiple-rows array :

```php
<?php

  // delete all users of age 25 from the USA

  $where = array(
    'age'     => 25,
    'country' => "USA"
  );

  $pancake->delete( "users", $where );

?>
```

__Note :__ As of version `0.3`, multiple conditions only support `AND` logical relations.



### getRow( $table, $where )

Fetch a single row's data.


##### Parameters

  * `string` $table : Table to get the data from.
  * `array` $where : A set of conditions to select the data to return.


##### Return values

  * `array` An associative array with key names matching the table's column names.
  * `int(0)` if the query returned an empty result.
  * `bool(FALSE)` if the query failed.


##### Usage

Like `delete()`, the `getRow()` method use a single/multiple conditions array.

__Note : __ : If your condition(s) match several rows, only the first one will be returned.

```php
<?php

  $where = array(
    'id' => 48
  );

  $results = $pancake->getRow( "users", $where );

?>
```

__Note :__ As of version `0.3`, multiple conditions only support `AND` logical relations.