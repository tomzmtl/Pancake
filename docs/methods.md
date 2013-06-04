# Pancake Methods

Here are listed all methods provided by Pancake.



## insert( $table, $data )

Inserts a single row into a table.


#### Parameters

  * `string` $table : Table to insert the data into.
  * `array` $data : Set of key/value pairs. Keys must match the table's columns names.


#### Return values

  * `int` Insert ID if insert is successful.
  * `bool(FALSE)` on failure.


#### Usage

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
  $pancake->insert( "users", $data );

?>
```



## delete( $table, $where)

Deletes rows from a table.


#### Parameters

  * `string` $table : Table to delete the entry from.
  * `mixed` $where : A set of conditions to select the data to delete. Can be an array or a `Where` class instance (see below).


#### Return values

  * `int` Number of deleted entries.
  * `bool(FALSE)` if a problem occured.


#### Usage

```php
<?php

  // delete the row with the ID 34

  $where = array(
    'id' => 34
  );

  $pancake->delete( "users", $where );

?>
```



## getRow( $table, $where )

Fetches a single row of data.


#### Parameters

  * `string` $table : Table to get the data from.
  * `mixed` $where : A set of conditions to select the data to return. Can be an array or a `Where` class instance (see below).


#### Return values

  * `array` An associative array with key names matching the table's column names.
  * `int(0)` if the query returned an empty result.
  * `bool(FALSE)` if the query failed.


#### Usage

```php
<?php

  $where = array(
    'id' => 48
  );

  $results = $pancake->getRow( "users", $where );

?>
```

### Additional notes

  * If your condition(s) match several rows, only the first one will be returned.



## update( $table, $set, $where )

Updates data on a table.

#### Parameters

  * `string` $table : Table to update.
  * `array` $set : Set of values to update.
  * `mixed` $where : A set of conditions to the data to update. Can be an array or a `Where` class instance (see below).

#### Return values

  * `int` Number of rows affected by the update.
  * `bool(FALSE)` on failure.



## getVar( $table, $column, $where )

Gets a single value from the DB. Auto-typecast if possible.

#### Parameters

  * `string` $table : Table to get the data from.
  * `array` $column : Column where to get the value from.
  * `mixed` $where : A set of conditions. Can be an array or a `Where` class instance (see below).

#### Return values

  * `mixed` On success, the type-casted value. May be a `string` or `int`.
  * `bool(FALSE)` on failure.



## query( $query )

Generic method to execute any manual query.
Return values are simplified on purpose to promote the other methods.

#### Parameters

  * `string` $query : Query to execute.

#### Return values

  * `array` An associative array of data (SELECT queries).
  * `bool(TRUE)` on success except SELECT.
  * `bool(FALSE)` on failure (all queries).



## count( $query )

Counts items on a table.

#### Parameters

  * `string` $table : Table to get the data from.
  * `mixed` $where : A set of conditions. Can be an array or a `Where` class instance (see below).

#### Return values

  * `int` on success.
  * `bool(FALSE)` on failure.