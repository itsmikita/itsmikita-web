<?php

namespace App\Factory;

use App\Factory\Storage;
use PDO;
use DateTime;
use Exception;

/**
 * Factory Trait
 */
trait Factory
{
  /**
   * Prepare placeholders
   *
   * @param $params
   * @param $suffix
   */
  public static function getPlaceholders( $data, $suffix = "" )
  {
    $placeholders = [];
    foreach( array_keys( $data ) as $column ) {
      $placeholders[] = "{$column} = :{$column}{$suffix}";
    }
    return $placeholders;
  }

  /**
   * Prepare params from data
   *
   * @param $data
   * @param $suffix
   */
  public static function getParams( $data, $suffix = "" )
  {
    $params = [];
    foreach( $data as $column => $value ) {
      if( "NOW()" === $value ) {
        $now = new DateTime();
        $value = $now->format( "Y-m-d H:i:s" );
      }
      $params[ ":{$column}{$suffix}" ] = $value;
    }
    return $params;
  }

  /**
   * Get record[s] from database
   *
   * @param $record
   * @param $limit
   * @param $offset
   */
  public function get( $record, $limit = 12, $offset = 0 )
  {
    if( is_numeric( $record ) ) {
      $record = [ 'id' => $record ];
    }
    $placeholders = self::getPlaceholders( $record );
    $query = Storage::prepare(
      sprintf(
        'SELECT * FROM %s WHERE %s',
        $this->table,
        $placeholders ? join( ' AND ', $placeholders ) : "1"
      )
    );
    $query->execute( self::getParams( $record ) );
    if( 1 === $query->rowCount() ) {
      return $query->fetch( PDO::FETCH_ASSOC );
    }
    else {
      return $query->fetchAll( PDO::FETCH_ASSOC );
    }
  }

  /**
   * Check wether record exist in database
   *
   * @param $record
   */
  public function exist( $record )
  {
    $placeholders = self::getPlaceholders( $record );
    $query = Storage::prepare(
      sprintf(
        'SELECT COUNT( * ) FROM %s WHERE %s',
        $this->table,
        join( ' AND ', $placeholders )
      )
    );
    $query->execute( self::getParams( $record ) );
    return 0 != $query->fetchColumn();
  }

  /**
   * Add new record[s] to database
   *
   * @param $records
   */
  public function add( $records )
  {
    if( ! isset( $records[ 0 ] ) ) {
      $records = [ $records ];
    }
    $query = Storage::prepare(
      sprintf(
        'INSERT INTO %s SET %s ON DUPLICATE KEY UPDATE %s',
        $this->table,
        join( ', ', self::getPlaceholders( $records[ 0 ] ) ),
        join( ', ', array_map(
          function( $column ) {
            return "{$column} = VALUES( {$column} )";
          },
          array_filter(
            array_keys( $records[ 0 ] ),
            function( $column ) {
              return ! in_array( $column, [ "user_id" ] );
            }
          )
        ) )
      )
    );
    $ids = [];
    foreach( $records as $record ) {
      $query->execute( self::getParams( $record ) );
      $ids[] = Storage::lastInsertId();
    }
    if( 1 === count( $ids ) ) {
      return reset( $ids );
    }
    else {
      return $ids;
    }
  }

  /**
   * Update records in database
   *
   * @param $records
   * @param $where
   */
  public function update( $records, $where = [] )
  {
    if( ! isset( $records[ 0 ] ) ) {
      $records = [ $records ];
    }
    if( is_numeric( $where ) ) {
      $where = [ 'id' => $where ];
    }
    $query = Storage::prepare(
      sprintf(
        'UPDATE %s SET %s WHERE %s',
        $this->table,
        join( ', ', self::getPlaceholders( $records[ 0 ] ) ),
        empty( $where ) ? "1" : join( ' AND ', self::getPlaceholders( $where ) )
      )
    );
    $count = 0;
    foreach( $records as $record ) {
      $query->execute( self::getParams( $record + $where ) );
      $count += $query->rowCount();
    }
    return $count;
  }

  /**
   * Delete row[s] from database
   *
   * @param $records
   */
  public function remove( $records = [] )
  {
    if( ! isset( $records[ 0 ] ) ) {
      $records = [ $records ];
    }
    $count = 0;
    $query = Storage::preapre(
      sprintf(
        'DELETE FROM %s WHERE %s',
        $this->table,
        self::getPlaceholders( $records[ 0 ] )
      )
    );
    foreach( $records as $record ) {
      $query->execute( self::getParams( $record ) );
      $count += $query->rowsCount();
    }
    return $count;
  }
}
