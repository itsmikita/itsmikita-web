<?php

namespace App\HTTP;

/**
 * HTTP Redirect Response Class
 */
class Redirect
{
  use App\HTTP\Response;

  public function __construct( $url, $status = 302 )
  {
    parent::__construct( null, [ 'Location' => $url ] );
    $this->setStatus( $status );
  }
}
