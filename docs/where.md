# Where helper class

Pancake core methods accept simple arrays to define WHERE conditions.
Those will always use `=` comparison operator and `AND` logical bind with other conditions.

Example :

```php

$where = array(
  'age'     => 20,
  'country' => "Canada"
);

$pancake->delete( "users", $where );
```

This will translate by the following SQL statement :

```sql
DELETE FROM table WHERE age = 20 AND country = 'Canada'
```

The `Where` helper class is here to fulfill the needs for more complex condition sets.
To use it, simply provide an instance of the `Where` class in place of the aforementioned condition array.

## Creating a Where instance

`Where` constructor takes as arguments an array of arrays.
Each sub-array represents a sub-condition.

### Structure of a sub-array

  * `key` Name of the column
  * `value` Value associated with the key (int or string)
  * `compare` (optional) Comparison operator for the given key/value pair. Accepted values are "<", "<=", "=", ">=", ">", "!=". Default to "=".
  * `logic` (optional) Logical relation to the other conditions. Accepted values are "AND" and "OR". Default to "AND".

If void or incorrect values are provided for `compare` and/or `logic`, default values will be used.

### Examples

```php

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

$results = $pancake->selectRow( "users", new Where($args) );
```

This will translate by the following SQL statement :

```sql
SELECT * FROM table WHERE age >= 20 OR country = 'Canada'
```

### Additional notes

Pancake methods using condition sets can take either a Where instance or a Where-ready array as argument.
