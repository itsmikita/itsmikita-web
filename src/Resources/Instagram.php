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
    $data = $spider->getMedia( "itsmikita" );
    if( ! $data ) {
      throw new Exception( "Failed to fetch media!" );
    }
    foreach( $data as $x => $item ) {
      $image = file_get_contents( $item['remote_url'] );
      $filename = substr( basename( $item['remote_url'] ), 0, strpos( basename( $item['remote_url'] ), "?" ) );
      $path = sprintf( "%s/%s", UPLOAD_DIR, $filename );
      $url = sprintf( "%s/%s", UPLOAD_URL, $filename );
      file_put_contents( $path, $image );
      $data[ $x ]['url'] = $url;
      $data[ $x ]['path'] = $path;
      unset( $data[ $x ]['remote_url'] );
    }
    $media = UPLOAD_DIR . "/media.json";
    if( file_exists( $media ) ) {
      unlink( $media );
    }
    file_put_contents( $media, json_encode( $data ) );
    return new Response( [ 'media' => $media ] );  
  }
}
