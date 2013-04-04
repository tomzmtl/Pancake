<?php

/*

  PANCAKE
  --------------------

  A PDO-based MySQL Abstraction class.
  Inspired by the simplicity of EzSQL & Wordpress' WPDB.

*/


Class Pancake
{
  private $db_name = NULL;
  private $db_user = NULL;
  private $db_pass = NULL;
  private $db_host = NULL;

  private $session = NULL;

  function __construct( $name, $user, $pass, $host = "localhost" )
  {
    $this->db_name = $name;
    $this->db_user = $user;
    $this->db_pass = $pass;
    $this->db_host = $host;
  }

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
    * string. ex: "(key1,ke2,key3)"
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
    * placeholders string. ex: "(?,?,?)"
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
    * An array-based single condition to target the item(s) to delete.
    * Exemple : array( 'id' => 23 ) equals to the query WHERE id = 234.
    *
    * @return mixed
    * Number of deleted entries (int) if the query didn't fail.
    * FALSE if a problem occured.
    *
    * Note : this method can return 0 (int), so be careful when
    * processing the output.
    *
  */
  public function delete( $table, $where )
  {
    $conditions = $this->getConditions( $where );

    $q = "DELETE FROM $table WHERE $conditions";

    $dbh = $this->createSession();

    $stmt = $dbh->prepare($q);
    $stmt->execute();
  }


  /**
    * Selects all fields from a single row in the table,
    * based on provided condition.
    * If multiple results are found, will return the first of the set.
    *
    * @param string $table
    * Table to delete the entry from.
    *
    * @return array
    * An associative array with keys matching the column names.
    *
  */
  public function getRow( $table, $where )
  {
    $conditions = $this->getConditions( $where );

    $q = "SELECT * FROM $table WHERE $conditions";

    $dbh = $this->createSession();

    $stmt = $dbh->prepare($q);
    $stmt->execute();

    return $stmt->fetch( PDO::FETCH_ASSOC );
  }
}