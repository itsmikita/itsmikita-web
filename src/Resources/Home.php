<?php

namespace App\Resources;

use App\HTTP\Request;
use App\HTTP\Response;
use App\HTTP\Middleware;
use Exception;

class Home
{
  use Middleware;

  // URL Path
  private $path = "/api";

  /**
   * Handle HTTP Request
   * 
   * @param $request
   * @param $handler
   */
  public function process( $request, $handler )
  {
    $path = UPLOAD_DIR . "/media.json";
    if( ! file_exists( $path ) ) {
      throw new Exception( "Storage file does not exist!" );
    }
    $media = json_decode( file_get_contents( $path ), true );
    return new Response( $media );
  }
}
