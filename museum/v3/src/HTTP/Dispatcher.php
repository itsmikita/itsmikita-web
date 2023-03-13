<?php

namespace App\HTTP;

use App\HTTP\Middleware;
use Exception;

/**
 * HTTP Dispatcher
 */
class Dispatcher
{
  // Stack
  private $middlewares = [];

  /**
   * Constructor
   *
   * @param $middlewares
   */
  public function __construct( $middlewares = [] )
  {
    $this->setMiddlewares( $middlewares );
  }

  /**
   * Set Middlewares
   *
   * @param $middlewares
   */
  public function setMiddlewares( $middlewares )
  {
    $this->middlewares = $middlewares;
  }

  /**
   * Get Middlewares
   */
  public function getMiddlewares()
  {
    return $this->middlewares;
  }

  /**
   * Handle HTTP Request
   *
   * @param $request
   */
  public function handle( $request )
  {
    if( empty( $this->middlewares ) ) {
      throw new Exception( "Not Found.", 404 );
    }

    $middleware = array_shift( $this->middlewares );
    $handler = clone $this;

    // Middleware
    if( method_exists( $middleware, "match" ) ) {
      if( $middleware->match( $request ) ) {
        return $middleware->process( $request, $handler );
      }
      else {
        return $handler->handle( $request );
      }
    }

    // Callable
    elseif( is_callable( $middleware ) ) {
      return $middleware( $request, $handler );
    }

    // Next Middleware
    else {
      return $handler->handle( $request );
    }
  }
}
