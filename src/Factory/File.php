<?php

namespace App\Factory;

use App\Factory\FileSystem;
use Exception;

class File
{
  // Contents
  private $data;

  // Path
  private $path;

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
    $filesystem = new FileSystem();
    $file = $filesystem->read( $path );
    return $file;
  }

  /**
   * Write Data to the File
   * 
   * @param $path
   */
  public function write( $path )
  {
    $this->setPath( $path );
    $filesystem = new FileSystem();
    $filesystem->write( $this );
  }

  /**
   * Set full file path
   * 
   * @param $path
   */
  public function setPath( $path )
  {
    $this->path = $path;
  }

  /**
   * Get file path
   */
  public function getPath()
  {
    return $this->path;
  }

  /**
   * Get file data
   */
  public function getContents()
  {
    return $this->data;
  }
}