Pancake
=======

Current version : **0.4**

A simple PDO-based MySQL abstraction class.

Inspired by the simplicity of ezSQL and WordPress DB Class.

Getting Started
---------------

To start using Pancake, simply instantiate the class.
You'll have to provide your database credentials.

```php
<?php

  require_once('lib/Pancake.class.php');

  $pancake = new Pancake( DB_NAME, DB_USER, DB_PASS, DB_HOST );

?>
```

Pancake Methods
---------------

Here are listed all methods provided by Pancake.

### Insert rows

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

**Note :** This method can only insert a single row.

The `insert()` method will return the new insert's ID, or `FALSE`if an error occured.

### Delete rows

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

**Note :** As of version `0.3`, multiple conditions only support `AND` logical relations.

### Fetch a row's data

Like `delete()`, the `getRow()` method use a single/multiple conditions array.

The data will be returned as an associative array, with key names matching the table's column names.

**Note : ** This method only fetch a single row. If your condition(s) match several rows, only the first one will be returned.

```php
<?php

  $where = array(
    'id' => 48
  );

  $results = $pancake->getRow( "users", $where );

?>
```

**Note :** As of version `0.3`, multiple conditions only support `AND` logical relations.