<?php

namespace App\Resources;

use App\HTTP\Response;
use App\HTTP\Middleware;
use App\Instagram\Spider;
use App\Factory\File;

class Instagram
{
  use Middleware;

  // URL Path
  private $path = "/api/instagram";

  /**
   * Handle HTTP Request
   * 
   * @param $request
   * @param $handler
   */
  public function process( $request, $handler )
  {
    $spider = new Spider();
    $media = $spider->getMedia( "itsmikita" );
    foreach( $media as $x => $item ) {
      $image = new File( file_get_contents( $item['remote_url'] ) );
      list( $filename ) = explode( "?", basename( $item['remote_url'] ) );
      $filepath = sprintf( "%s/%s", UPLOAD_DIR, $filename );
      $url = sprintf( "%s/%s", UPLOAD_URL, $filename );
      if( file_exists( $filepath ) ) {
        unlink( $filepath );
      }
      $image->write( $filepath );
      $media[ $x ]['url'] = $url;
      $media[ $x ]['file'] = $filepath;
    }
    $store = new File( json_encode( $media ) );
    $path = UPLOAD_DIR . "/media.json";
    if( file_exists( $path ) ) {
      unlink( $path );
    }
    $store->write( $path );
    return new Response( [ 'store' => $path ] );
  }
}
