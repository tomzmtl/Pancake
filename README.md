# Pancake

Current version : `1.0.0`

A simple PDO-based MySQL abstraction class.m
Inspired by the simplicity of ezSQL and WordPress DB Class.


## Overview

Pancake include easy-to-use and straightforward methods for the following operations :

- [x] Insert rows
- [x] Delete rows
- [x] Update rows
- [x] Fetch data (sets, single variable)
- [x] Count elements


## Getting Started

To start using Pancake, simply instantiate the class.
You'll have to provide your database credentials.

```php
<?php

  require_once('lib/Pancake.class.php');

  $pancake = new Pancake( DB_NAME, DB_USER, DB_PASS, DB_HOST );

?>
```


## Complete documentation

You will find the complete documentation in the /docs/ folder.