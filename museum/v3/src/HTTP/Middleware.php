<?php

namespace App\HTTP;

trait Middleware
{
  /**
   * Callable alias for Middleware::process()
   *
   * @param $request
   * @param $handler
   */
  public function __invoke( $request, $handler )
  {
    $this->process( $request, $handler );
  }

  /**
   * Process Request
   *
   * @param $request
   * @param $handler
   */
  public function process( $request, $handler )
  {
    // Default behaviour
    return $handler->handle( $request );
  }

  /**
   * Set URI Path
   *
   * @param $path
   */
  public function setPath( $path )
  {
    $this->path = $path;
  }

  /**
   * Get URI Path
   */
  public function getPath()
  {
    return $this->path;
  }

  /**
   * Check wether Request URL matches Middleware Path
   *
   * @param $request
   */
  public function match( $request )
  {
    return fnmatch(
      $this->getPath(),
      rtrim( $request->getPath(), "/" ),
      FNM_PATHNAME
    );
  }
}
