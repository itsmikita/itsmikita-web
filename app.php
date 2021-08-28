<?php

require __DIR__ . "/autoload.php";

use App\HTTP\Dispatcher;
use App\HTTP\Request;
use App\HTTP\Response;

// Tiny Application
try {
  $dispatcher = new Dispatcher( [
    new App\Resources\Home
    // new App\Resources\Instagram
  ] );
  $request = Request::createFromGlobals();
  $response = $dispatcher->handle( $request );
  if( ! $response instanceof Response ) {
    throw new Exception( "Resource Not Found!", 404 );
  }
} catch( Exception $error ) {
  $response = new Response( [
    // 'code' => $error->getCode(),
    'error' => $error->getMessage()
  ] );
}

// Transmit HTTP Response back to client in JSON format
$response->setHeaders( [
  'Content-Type' => "application/json; charset=utf-8",
  'Accept' => "application/json; charset=utf-8",
  'Access-Control-Allow-Origin' => "*",
  'Access-Control-Allow-Headers' => "Access-Control-Allow-Headers,Access-Control-Allow-Origin,Authorization,Content-Type,Accept"
] );

$response->send();
