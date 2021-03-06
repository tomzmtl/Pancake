# Pancake

Current version : `1.1.0`

A simple PDO-based MySQL abstraction class.
Inspired by the simplicity of ezSQL and WordPress DB Class.


## Overview

Pancake include easy-to-use and straightforward methods for the following operations :

  * Insert rows.
  * Delete rows.
  * Update data.
  * Fetch data (sets, single row, single variable).
  * Count items.


## Getting Started

To start using Pancake, simply instantiate the class.
You'll have to provide your database credentials.

```php

require_once('lib/Pancake.class.php');

$pancake = new Pancake( DB_NAME, DB_USER, DB_PASS, DB_HOST );
```


## Complete documentation

You will find the complete documentation in the /docs/ folder.