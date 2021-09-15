<?php

require __DIR__ . "/config.php";
require __DIR__ . "/vendor/autoload.php";

use App\HTTP\Dispatcher;
use App\HTTP\Request;
use App\HTTP\Response;

try {
  $dispatcher = new Dispatcher( [
    // API
    new App\Resources\Media,
    new App\Resources\Instagram,
    
    // Front-End
    function( $request, $handler ) {
      $home = file_get_contents( ABSPATH . "/public/index.html" );
      echo $home;
      exit;
    }
  ] );
  $request = Request::createFromGlobals();
  $response = $dispatcher->handle( $request );
  if( ! $response instanceof Response ) {
    throw new Exception( "Resource Not Found!" );
  }
} 
catch( Exception $error ) {
  $response = new Response( [
    'code' => $error->getCode(),
    'error' => $error->getMessage()
  ] );
}

$response->setHeaders( [
  'Content-Type' => "application/json; charset=utf-8",
  'Accept' => "application/json; charset=utf-8",
  'Access-Control-Allow-Origin' => "*",
  'Access-Control-Allow-Headers' => "Access-Control-Allow-Headers,Access-Control-Allow-Origin,Authorization,Content-Type,Accept"
] );
$response->send();
