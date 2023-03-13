<?php

namespace App\HTTP;

use App\HTTP\Message;

/**
 * HTTP Redirect Response Class
 */
class Redirect
{
  use Message;

  public function __construct( $url, $status = null )
  {
    parent::__construct( null, [ 'Location' => $url ] );
    $this->setStatus( $status );
  }
}
