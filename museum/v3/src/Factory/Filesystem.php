<?php

namespace App\Factory;

use App\Factory\File;
use Exception;

class FileSystem
{
  /**
   * Constructor
   */
  public function __construct()
  {
    
  }

  /**
   * Read file contents from path ot file
   * 
   * @param $path
   */
  public function read( $path )
  {
    if( ! file_exists( $path ) ) {
      throw new Exception( "Could not read the file contents: {$path}" );
    }
    $file = new File( file_get_contents( $path ) );
    return $file;
  }

  /**
   * Write to file
   * 
   * @param $file
   */
  public function write( $file )
  {
    if( file_exists( $file->getPath() ) ) {
      unlink( $file );
    }
    file_put_contents( $file->getPath(), $file->getContents() );
  }
}