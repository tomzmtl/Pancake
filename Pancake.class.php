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
  * @version 0.5
  *
*/


Class Pancake
{
  private $db_name = NULL;
  private $db_user = NULL;
  private $db_pass = NULL;
  private $db_host = NULL;

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
    return new PDO( "mysql:host=$this->db_host;dbname=$this->db_name",
                              $this->db_user, $this->db_pass );
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
    * Converts a WHERE-like array into a SQL-readable
    * coondition. ex: "a = 1 AND b = 'b'"
    *
    * @param  array
    * @return string
    *
  */
  private function getConditions( $where )
  {
    $conditions = array();

    foreach ( $where as $field => $value )
    {
      if ( is_int( $value ) )
        $conditions[] = "$field = $value";
      else
        $conditions[] = "$field = '$value'";
    }

    return implode( " AND " , $conditions );
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
    * @param array $where
    * A set of conditions to select the data to delete.
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
    $conditions = $this->getConditions( $where );

    $q = "DELETE FROM $table WHERE $conditions";

    $dbh = $this->createSession();

    $stmt = $dbh->prepare($q);
    
    if ( $stmt->execute() === TRUE )
    {
      return $dbh->rowCount();
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
    * @param array $where
    * A set of conditions to select the data to return.
    *
    * @return mixed
    * array : On success, an associative array with keys matching the column names.
    * int   : If the query succeed but get an empty result, (int) 0.
    * bool  : On query failure, FALSE.
    *
  */
  public function getRow( $table, $where )
  {
    $conditions = $this->getConditions( $where );

    $q = "SELECT * FROM $table WHERE $conditions LIMIT 0,1";

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
      error_code( $stmt->errorCode() );
      return FALSE;
    }
  }
}