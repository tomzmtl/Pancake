<?php

/**
  * PANCAKE
  * ------------------------------------------------------------
  *
  * A PDO-based MySQL Abstraction class.
  * Inspired by the simplicity of EzSQL & Wordpress' WPDB.
  *
  * ------------------------------------------------------------
  *
  * @author Thomas Andreo
  * @version 0.8
  *
*/


Class Pancake
{
  private $db_name = NULL;
  private $db_user = NULL;
  private $db_pass = NULL;
  private $db_host = NULL;

  // Used to convert native_type to PDO_type
  private $column_types = array(
    PDO::PARAM_INT => array( "int", "tinyint" ),
    PDO::PARAM_STR => array( "varchar", "text" )
  );

  /**
    * Class constructor.
    *
    * @param string $name
    * Database Name.
    *
    * @param string $user
    * Database User name.
    *
    * @param string $pass
    * Database Password.
    *
    * @param string [optional] $host
    * Database Host. Default to "localhost".
    *
  */
  function __construct( $name, $user, $pass, $host = "localhost" )
  {
    $this->db_name = $name;
    $this->db_user = $user;
    $this->db_pass = $pass;
    $this->db_host = $host;
  }

  /**
    * Creates a brand new PDO session.
    * Should be used before any new DB operation.
    *
    * @return object
    * PHP PDO Object
    *
  */
  function createSession()
  {
    $pdo_options = array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8" );

    return new PDO( "mysql:host=$this->db_host;dbname=$this->db_name",
                     $this->db_user,
                     $this->db_pass,
                     $pdo_options );
  }






  /*--------------------------------------------------+
  |                                                   |
  |                UTILITY FUNCTIONS                  |
  |                                                   |
  +--------------------------------------------------*/

  /**
    * Converts an array of data to process into a SQL-readable
    * string. ex: "key1,ke2,key3"
    *
    * @param  array
    * @return string
    *
  */
  private function getKeys( $data )
  {
    return implode( "," , array_keys( $data ));
  }


  /**
    * Converts an array of data to process into a PDO-compatible
    * placeholders string. ex: "?,?,?"
    *
    * @param  array
    * @return string
    *
  */
  private function getPlaceholders( $data )
  {
    $placeholders = array();

    foreach ( $data as $d )
    {
      $placeholders[] = "?";
    }

    return $placeholders = implode( "," , $placeholders );
  }


  /**
    * Converts a WHERE-like array into a Where class instance.
    * Data errors are processed from within the Where class constructor.
    *
    * @param  array
    * @return object Where
    *
  */
  private function buildWhereObject( $where )
  {
    if ( $where instanceof Where )
    {
      return $where;
    }
    else
    {
      return new Where($where);
    }

  }


  /**
    * Gets the SQL type from a specific columns.
    *
    * @param  string $table
    * Table name.
    *
    * @param string $name
    * Column name.
    *
    * @return int
    * PDO PARAM_* constant value.
    * (see http://php.net/manual/en/pdo.constants.php)
    *
  */
  private function getColumnType( $table, $name )
  {
    $q = "SELECT DATA_TYPE
          FROM   INFORMATION_SCHEMA.COLUMNS
          WHERE  TABLE_NAME   = '$table'
          AND    COLUMN_NAME  = '$name'
          AND    TABLE_SCHEMA = '$this->db_name'";

    $dbh  = $this->createSession();
    $stmt = $dbh->prepare($q);

    $type = $stmt->fetchColumn(0);

    foreach ( $this->column_types as $pdo_type => $native )
    {
      if ( in_array( $type, $native, TRUE ))
      {
        return $pdo_type;
      }
    }

    // default : return string type
    return PDO::PARAM_STR;
  }


  /**
    * Auto-typecast a value based on its PDO type.
    *
    * @param string $var
    * Variable coming straight from the DB (expected to be a string).
    *
    * @param int $pdo_type
    * Value of the PDO constant matching the variable type.
    * (see http://php.net/manual/en/pdo.constants.php)
    *
    * @return mixed
    * Type-casted (or not) value.
    *
  */
  private function typify( $var, $pdo_type )
  {
    if ( $pdo_type === PDO::PARAM_INT )
    {
      return (int) $var;
    }

    return $var;
  }




  /*--------------------------------------------------+
  |                                                   |
  |                 PUBLIC METHODS                    |
  |                                                   |
  +--------------------------------------------------*/

  /**
    * Insert a new entry in the DB. Takes as argument a data array
    * with key/value pairs. Keys names must match the table's
    * column names.
    *
    * @param string $table
    * Table to process the insertion into.
    *
    * @param array $data
    * Set of key/value pairs. Keys must match the table's columns names.
    *
    * @return mixed
    * Insert ID (int) if insert is successful, or FALSE on failure.
    *
  */
  public function insert( $table, $data )
  {
    $keys = $this->getKeys($data);
    $placeholders = $this->getPlaceholders($data);

    $q = "INSERT INTO $table ($keys) VALUES ($placeholders)";

    $dbh = $this->createSession();

    $stmt = $dbh->prepare($q);

    if ( $stmt->execute( array_values($data) ))
    {
      return (int) $dbh->lastInsertId();
    }
    else
    {
      return FALSE;
    }
  }


  /**
    * Deletes one or multiple entries based on a set of conditions.
    *
    * @param string $table
    * Table to delete the entry from.
    *
    * @param mixed $where
    * A set of conditions to select the data to delete.
    * Can be an array or a Where object (see dcumentation).
    *
    * @return mixed
    * Number of deleted entries (int) if the query didn't fail.
    * FALSE if a problem occured.
    *
    * Note : this method can return 0 (int), if no error occured
    *        but no row was deleted.
    *
  */
  public function delete( $table, $where )
  {
    $where = $this->buildWhereObject($where);

    $q = "DELETE FROM $table WHERE " . $where->output();

    $dbh = $this->createSession();

    $stmt = $dbh->prepare($q);

    if ( $stmt->execute() === TRUE )
    {
      return $stmt->rowCount();
    }
    else
    {
      return FALSE;
    }
  }


  /**
    * Selects all fields from a single row in the table,
    * based on provided condition.
    * If multiple results are found, will return the first of the set.
    *
    * @param string $table
    * Table to get the data from.
    *
    * @param mixed $where
    * A set of conditions to select the data to return.
    * Can be an array or a Where object (see documentation).
    *
    * @return mixed
    * array : On success, an associative array with keys matching the column names.
    * int   : If the query succeeded but get an empty result, (int) 0.
    * bool  : On query failure, FALSE.
    *
  */
  public function getRow( $table, $where )
  {
    $where = $this->buildWhereObject($where);

    $q = "SELECT * FROM $table WHERE " . $where->output() . " LIMIT 0,1";

    $dbh = $this->createSession();

    $stmt = $dbh->prepare($q);

    if ( $stmt->execute() )
    {
      $result = $stmt->fetch( PDO::FETCH_ASSOC );

      if ( is_array( $result ) )
      {
        return $result;
      }
      else
      {
        return 0;
      }
    }
    else
    {
      return FALSE;
    }
  }


  /**
    * Updates a table.
    *
    * @param string $table
    * Table to update.
    *
    * @param array $set
    * Set of values to update.
    *
    * @param mixed $where
    * A set of conditions to the data to update.
    * Can be an array or a Where object (see documentation).
    *
    * @return mixed
    * int  : On success, number of rows affected.
    * bool : On failure, FALSE.
    *
  */
  public function update( $table, $set, $where )
  {
    $where = $this->buildWhereObject($where);

    $values = array();

    foreach ( $set as $col => $val )
    {
      if ( is_string( $val ))
      {
        $val = "'$val'";
      }

      $values[] = "$col = $val";
    }

    $values = implode( ", ", $values );

    $q = "UPDATE $table SET $values WHERE " . $where->output();

    $dbh = $this->createSession();

    $stmt = $dbh->prepare($q);

    if ( $stmt->execute() === TRUE )
    {
      return $stmt->rowCount();
    }
    else
    {
      return FALSE;
    }


  }


  /**
    * Gets a single value from the DB.
    * Auto-typecast if possible.
    *
    * @param string $table
    * Table to get the data from.
    *
    * @param array $column
    * Column where to get the value from.
    *
    * @param mixed $where
    * A set of conditions.
    * Can be an array or a Where object (see documentation).
    *
    * @return mixed
    * mixed : On success, the type-casted value. May be a string or int.
    * bool  : On failure, FALSE.
    *
  */
  public function getVar( $table, $column, $where )
  {
    $where = $this->buildWhereObject($where);

    $q = "SELECT $column FROM $table WHERE " . $where->output() . " LIMIT 0,1";

    $dbh  = $this->createSession();
    $stmt = $dbh->prepare($q);

    if ( $stmt->execute() === TRUE )
    {
      return $this->typify( $stmt->fetchColumn(0),
                            $this->getColumnType( $table, $column ));
    }
    else
    {
      return FALSE;
    }
  }


}





/**
  * PANCAKE CONDITION CLASS
  * ------------------------------------------------------------
  *
  * Specific helper class for Pancake to help build conditional
  * queries easily.
  *
  * See examples/Where.php to get started using it.
  *
  * ------------------------------------------------------------
  *
  * @author Thomas Andreo
  * @version 1.0
  *
*/

Class Where
{
  // Default logic elements
  const COMPARE = "=";
  const LOGIC   = "AND";

  // SQL condition statement
  private $statement = "";

  // Accepted logic elements
  private $compare = array("<","<=","=",">=",">","!=");
  private $logic   = array("AND","OR");


  /**
    * Class constructor.
    *
    * @param array $args
    * Set of conditions needed to build the SQL statement.
    * Should be an array of arrays, one for each sub-condition.
    *
    * Available fields for each sub-array :
    *
    * key     : the name of the columns
    * value   : the value associated to the key
    * compare : (optional) comparison operator for the given key/value pair.
    *           Default to "="
    * logic   : (optional) logical relation to the other conditions.
    *           Default to "AND"
    *
  */
  function __construct( $args )
  {
    $i = 0;

    if ( count($args) === 0 )
    {
      throw new Exception("Invalid data provided to Where constructor.");
    }
    else
    {
      // if a multi-dimension array is provided (complex mode)
      if ( count($args) !== count( $args, COUNT_RECURSIVE ))
      {
        foreach ( $args as $cond )
        {
          if ( $i > 0 )
          {
            $this->space();

            if ( isset( $cond['logic'] ) && in_array( $cond['logic'], $this->logic, TRUE ))
            {
              $this->append( $cond['logic'] );
            }
            else
            {
              $this->append( self::LOGIC );
            }

            $this->space();
          }

          $this->append( $cond['key'] );

          $this->space();

          if ( isset( $cond['compare'] ) && in_array( $cond['compare'], $this->compare, TRUE ))
          {
            $this->append( $cond['compare'] );
          }
          else
          {
            $this->append( self::COMPARE );
          }

          $this->space();

          if ( is_string( $cond['value'] ))
          {
            $this->append( "'" . $cond['value'] . "'" );
          }
          else
          {
            $this->append( $cond['value'] );
          }

          $i++;
        }
      }
      // if a single-dimension array is provided (simple mode)
      else
      {
        $i = 0;

        foreach ( $args as $field => $value )
        {
          if ( $i > 0 )
          {
            $this->append( " " . self::LOGIC . " " );
          }

          $this->append( $field . " " . self::COMPARE . " " );

          if ( is_string( $value ))
          {
            $this->append( "'" . $value . "'" );
          }
          else
          {
            $this->append( $value );
          }

          $i++;
        }
      }
    }
  }

  /**
    * Appends content to the Class SQL statement.
    *
    * @param mixed $content
    * Content to append.
    *
  */
  private function append($content)
  {
    $this->statement .= $content;
  }

  /**
    * Appends a space to the Class SQL statement.
  */
  private function space()
  {
    $this->append(" ");
  }

  /**
    * Outputs the condition as a full WHERE SQL statement
    *
    * @return string
    *
  */
  public function output()
  {
    return $this->statement;
  }

}