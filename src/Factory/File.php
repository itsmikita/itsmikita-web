<?php

namespace App\Factory;

use Exception;

class File
{
  // Contents
  private $data;

  /**
   * Constructor
   * 
   * @param $data
   */
  public function __construct( $data = null )
  {
    $this->data = $data;
  }

  /**
   * Read Data from File
   * 
   * @param $path
   */
  public function read( $path )
  {
    if( ! file_exists( $path ) ) {
      throw new Exception( "Could not read the file contents!" );
    }
    $this->data = file_get_contents( $path );
  }

  /**
   * Write Data to the File
   * 
   * @param $path
   */
  public function write( $path )
  {
    if( file_exists( $path ) ) {
      throw new Exception( "File already exists!" );
    }
    file_put_contents( $path, $this->data );
  }
}