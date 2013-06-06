# Pancake Methods

Here are listed all methods provided by Pancake.



## Insert a row

```php

Pancake::insert( $table, $data );
```

##### Parameters

  * `string` $table : Table to insert the data into.
  * `array` $data : Set of key/value pairs. Keys must match the table's columns names.


##### Return values

  * `int` Insert ID if insert is successful.
  * `bool(FALSE)` on failure.


##### Usage

Provide an array with key/value pairs matching your table's column names.

```php

// prepare your data
$data = array(
  'first_name' => "Brendan",
  'last_name'  => "Gallagher",
  'age'        => 20,
  'country'    => "Canada"
);

// execute insertion on the users table
$pancake->insert( "users", $data );
```


-----


## Delete rows

```php

Pancake::delete( $table, $where );
```

##### Parameters

  * `string` $table : Table to delete the entry from.
  * `mixed` $where : A set of conditions. Can be an array or a `Where` class instance.


##### Return values

  * `int` Number of deleted entries.
  * `bool(FALSE)` if a problem occured.


##### Usage

```php

// delete the row with the ID 34

$where = array(
  'id' => 34
);

$pancake->delete( "users", $where );
```


-----


## Fetch a data set

```php

Pancake::selectAll( $table, $where );
```

##### Parameters

  * `string` $table : Table to get the data from.
  * `mixed` $where : A set of conditions. Can be an array or a `Where` class instance.


##### Return values

  * `array` An array of associative arrays with key names matching the table's column names.
  * `int(0)` if the query returned an empty result.
  * `bool(FALSE)` if the query failed.


##### Usage

```php

$where = array(
  'country' => "Canada"
);

$results = $pancake->selectRow( "users", $where );
```

##### Additional notes

  * If data is returned, it will always be as an array of arrays, even if your query returns a single row.


-----


## Fetch a single row of data

```php

Pancake::selectRow( $table, $where );
```

##### Parameters

  * `string` $table : Table to get the data from.
  * `mixed` $where : A set of conditions. Can be an array or a `Where` class instance.


##### Return values

  * `array` An associative array with key names matching the table's column names.
  * `int(0)` if the query returned an empty result.
  * `bool(FALSE)` if the query failed.


##### Usage

```php

$where = array(
  'id' => 48
);

$results = $pancake->selectRow( "users", $where );
```

##### Additional notes

  * If your condition(s) match several rows, only the first one will be returned.


-----


## Fetch a single row of data

```php

Pancake::selectRow( $table, $where );
```

##### Parameters

  * `string` $table : Table to get the data from.
  * `mixed` $where : A set of conditions. Can be an array or a `Where` class instance.


##### Return values

  * `array` An associative array with key names matching the table's column names.
  * `int(0)` if the query returned an empty result.
  * `bool(FALSE)` if the query failed.


##### Usage

```php

$where = array(
  'id' => 48
);

$results = $pancake->selectRow( "users", $where );
```

##### Additional notes

  * If your condition(s) match several rows, only the first one will be returned.


-----


## Update data

```php

Pancake::update( $table, $set, $where );
```

##### Parameters

  * `string` $table : Table to update.
  * `array` $set : Set of values to update.
  * `mixed` $where : A set of conditions. Can be an array or a `Where` class instance.

##### Return values

  * `int` Number of rows affected by the update.
  * `bool(FALSE)` on failure.


-----


## Gets a single value

```php

Pancake::getVar( $table, $column, $where );
```

##### Parameters

  * `string` $table : Table to get the data from.
  * `array` $column : Column where to get the value from.
  * `mixed` $where : A set of conditions. Can be an array or a `Where` class instance.

##### Return values

  * `mixed` On success, the type-casted value. May be a `string` or `int`.
  * `bool(FALSE)` on failure.

##### Additional notes

  * This method will auto-typecast the return value if possible.


-----


## Counts items

```php

Pancake::count( $table, $where );
```

##### Parameters

  * `string` $table : Table to get the data from.
  * `mixed` $where : A set of conditions. Can be an array or a `Where` class instance.

##### Return values

  * `int` on success.
  * `bool(FALSE)` on failure.


-----


## Execute any manual query

```php

Pancake::query( $query );
```

##### Parameters

  * `string` $query : Query to execute.

##### Return values

  * `array` An associative array of data (SELECT queries).
  * `bool(TRUE)` on success except SELECT.
  * `bool(FALSE)` on failure (all queries).

##### Additional notes

  * Return values are simplified on purpose to promote the other methods.
