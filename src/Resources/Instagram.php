<?php

namespace App\Resources;

use App\HTTP\Response;
use App\HTTP\Middleware;
use App\Instagram\Spider;
use App\Factory\FileSystem;
use App\Factory\File;
use Exception;

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
      $image = file_get_contents( $item['remote_url'] );
      $filename = substr( basename( $item['remote_url'] ), 0, strpos( basename( $item['remote_url'] ), "?" ) );
      $path = sprintf( "%s/%s", UPLOAD_DIR, $filename );
      $url = sprintf( "%s/%s", UPLOAD_URL, $filename );
      file_put_contents( $path, $image );
      $media[ $x ]['url'] = $url;
      $media[ $x ]['path'] = $path;
      unset( $media[ $x ]['remote_url'] );
    }
    $storage = UPLOAD_DIR . "/media.json";
    if( file_exists( $storage ) ) {
      unlink( $storage );
    }
    file_put_contents( $storage, json_encode( $media ) );
    return new Response( [ 'media' => $storage ] );  
  }
}
