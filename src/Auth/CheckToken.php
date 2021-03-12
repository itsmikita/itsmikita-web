<?php

namespace App\Auth;

use App\Factory\Tokens;
use App\Auth\JWT;
use Exception;

/**
 * Check Token Middleware Class
 */
class CheckToken
{
  /**
   * Check Token callable
   *
   * @param $request
   * @param $handler
   */
  public function __invoke( $request, $handler )
  {
    if( 0 === strpos( $request->getPath(), "/auth" ) ) {
      return $handler->handle( $request );
    }
    if( ! $request->hasHeader( 'Authorization' ) ) {
      throw new Exception( "Unauthorized!", 400 );
    }
    $jwt = new JWT( $request->getHeader( 'Authorization' ) );
    if( ! $jwt->verify() ) {
      throw new Exception( "Invalid Token Signature!", 400 );
    }
    $tokens = new Tokens;
    if( ! $tokens->exist( [ 'token' => $jwt->getToken() ] ) ) {
      throw new Exception( "Invalid Token!", 400 );
    }
    return $handler->handle( $request );
  }
}
