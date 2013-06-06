## Pancake change log & roadmap

#### Change log

###### version `1.1.0` `CURRENT`

  * Added method `selectAll()` to fetch multiple rows (all fields).
  * Added method `selectCol()` to get a single column as an array.
  * Renamed method 'getRow()` to `selectRow()`.
  * Use `query()` in `getColumnType()` private method.
  * Tweaked documentation and comments.

-----

###### version `1.0.1`

  * Improved documentation.
  * Fixed mistakes in documentation.

-----

###### version `1.0.0`

  * Added `count()` method.
  * Cleaned comments.
  * Separated methods and Where documentations in separate files under /doc/.

-----

###### version `0.9`

  * Added `query()` generic method.

-----

###### version `0.8`

  * Added `getVar()` method.
  * Added private methods to request and manipulate column types.

-----

###### version `0.7`

  * Added `update()` method.

-----

###### version `0.6.1`

Bugfixes :

  * `delete()` method not returning affected rows.
  * all methods using Where objects failing at execution.

-----

###### version `0.6`

  * Added `Where` class, and implemented its logic into the core class.

-----

###### version `0.5`

  * `getRow()` method has a complete return values set.
  * Added `SET NAMES UTF8` statement to all sessions.

-----

###### version `0.4`

  * `delete()` method has a complete return values set.

-----

###### version `0.3` (Initial release)

  * Initial class core.
  * Initial `insert()`, `delete()`, `getRow()` methods.