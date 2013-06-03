# Pancake

Current version : __0.6__

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

-----


## Pancake Methods

Here are listed all methods provided by Pancake.



### insert( $table, $data )

Inserts a single row into a table.


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
  $pancake->insert( "users", $data );

?>
```



### delete( $table, $where)

Deletes rows from a table.


##### Parameters

  * `string` $table : Table to delete the entry from.
  * `mixed` $where : A set of conditions to select the data to delete. Can be an array or a `Where` class instance (see below).


##### Return values

  * `int` Number of deleted entries.
  * `bool(FALSE)` if a problem occured.


##### Usage

```php
<?php

  // delete the row with the ID 34

  $where = array(
    'id' => 34
  );

  $pancake->delete( "users", $where );

?>
```



### getRow( $table, $where )

Fetches a single row of data.


##### Parameters

  * `string` $table : Table to get the data from.
  * `mixed` $where : A set of conditions to select the data to return. Can be an array or a `Where` class instance (see below).


##### Return values

  * `array` An associative array with key names matching the table's column names.
  * `int(0)` if the query returned an empty result.
  * `bool(FALSE)` if the query failed.


##### Usage

```php
<?php

  $where = array(
    'id' => 48
  );

  $results = $pancake->getRow( "users", $where );

?>
```

#### Additional notes

  * If your condition(s) match several rows, only the first one will be returned.



### update( $table, $set, $where )

Updates data on a table.

##### Parameters

  * `string` $table : Table to update.
  * `array` $set : Set of values to update.
  * `mixed` $where : A set of conditions to the data to update. Can be an array or a `Where` class instance (see below).

##### Return values

  * `int` Number of rows affected by the update.
  * `bool(FALSE)` on failure.

-----


## Where helper class

Pancake core methods accept simple arrays to define WHERE conditions.
Those will always use `=` comparison operator and `AND` logical bind with other conditions.

Example :

```php
<?php

  $where = array(
    'age'     => 20,
    'country' => "Canada"
  );

  $pancake->delete( "users", $where );

?>
```

This will translate by the following SQL statement :

```sql
DELETE FROM table WHERE age = 20 AND country = 'Canada'
```

The `Where` helper class is here to fulfill the needs for more complex condition sets.
To use it, simply provide an instance of the `Where` class in place of the aforementioned condition array.

### Creating a Where instance

`Where` constructor takes as arguments an array of arrays.
Each sub-array represents a sub-condition.

#### Structure of a sub-array

  * `key` Name of the column
  * `value` Value associated with the key (int or string)
  * `compare` (optional) Comparison operator for the given key/value pair. Accepted values are "<", "<=", "=", ">=", ">", "!=". Default to "=".
  * `logic` (optional) Logical relation to the other conditions. Accepted values are "AND" and "OR". Default to "AND".

If void or incorrect values are provided for `compare` and/or `logic`, default values will be used.

#### Examples

```php
<?php

  $args = array(
    array(
      'key'     => "age",
      'value'   => 20,
      'compare' => ">="
    ),
    array(
      'key'   => "country",
      'value' => "Canada",
      'logic' => "OR"
    )
  );

  $results = $pancake->getRow( "users", new Where($args) );

?>
```

This will translate by the following SQL statement :

```sql
SELECT * FROM table WHERE age >= 20 OR country = 'Canada'
```

#### Additional notes

Pancake methods using condition sets can take either a Where instance or a Where-ready array as argument.
