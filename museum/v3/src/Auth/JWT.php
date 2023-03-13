<?php

namespace App\Auth;

use Exception;

/**
 * JSON Web Token (JWT) Class
 *
 * $payload = [
 *   'iat' => time(), // Issued stamp
 *   'sub' => 1234567890, // User ID
 *   'name' => "username", // Username (optional)
 *   'exp' => time() + 3600, // Expiration stamp (optional)
 * ]
 */
class JWT
{
  // Header
  private $header = [];

  // Payload
  private $payload = [];

  // Signature
  private $signature = "";

  // JWT
  private $token = "";

  /**
   * Constructor
   *
   * @param $token
   */
  public function __construct( $token = "" )
  {
    if( ! empty( $token ) ) {
      $this->decode( str_replace( "Bearer ", "", $token ) );
    }
  }

  /**
   * Encode Payload
   *
   * @param $payload
   */
  public function encode( $payload = [] )
  {
    $this->setPayload( $payload );
    $headerEncoded = $this->base64Encode(
      json_encode( $this->getHeader() )
    );
    $payloadEncoded = $this->base64Encode(
      json_encode( $payload )
    );
    $signatureEncoded = $this->base64Encode(
      $this->sign( "{$headerEncoded}.{$payloadEncoded}" )
    );
    $token = "{$headerEncoded}.{$payloadEncoded}.{$signatureEncoded}";
    $this->setToken( $token );
    return $token;
  }

  /**
   * Decode JWT
   *
   * @param $jwt
   */
  public function decode( $token = "" )
  {
    $this->setToken( $token );
    $segments = explode( ".", $token );

    // Invalid JWT
    if( 3 !== count( $segments ) ) {
      throw new Exception( "Invalid JWT!" );
    }

    $this->setHeader( $this->base64Decode( $segments[ 0 ] ) );
    $this->setPayload( $this->base64Decode( $segments[ 1 ] ) );
    $this->setSignature( $this->base64Decode( $segments[ 2 ] ) );

    return $this->getPayload();
  }

  /**
   * Sign SHA256
   *
   * @param $data
   */
  public function sign( $data )
  {
    return hash_hmac( "sha256", $data, JWT_SECRET, true );
  }

  /**
   * Verify JWT
   *
   * @param $token
   */
  public function verify( $token = "" )
  {
    if( empty( $token ) ) {
      $token = $this->getToken();
    }
    $this->decode( $token );

    $headerEncoded = $this->base64Encode(
      json_encode( $this->getHeader() )
    );
    $payloadEncoded = $this->base64Encode(
      json_encode( $this->getPayload() )
    );

    return hash_equals(
      $this->sign( "{$headerEncoded}.{$payloadEncoded}" ),
      $this->getSignature()
    );
  }

  /**
   * Safely decode JSON
   *
   * @param $json
   */
  public function jsonDecode( $json )
  {
    if( is_array( $json ) || is_object( $json ) ) {
      return $json;
    }
    $data = json_decode( $json, true );
    if( JSON_ERROR_NONE !== json_last_error() ) {
      return $json;
    }
    return $data;
  }

  /**
   * Safely URL and Base64 encode
   *
   * @param $data
   */
  public function base64Encode( $data )
  {
    $encoded = strtr( base64_encode( $data ), "+/", "-_" );
    return rtrim( $encoded, "=" );
  }

  /**
   * Safely URL and Base64 decode
   *
   * @param $data
   */
  public function base64Decode( $data )
  {
    $decoded = strtr( $data, "-_", "+/" );
    return base64_decode(
      str_pad( $decoded, strlen( $decoded ) % 4, "=", STR_PAD_RIGHT )
    );
  }

  /**
   * Set header
   *
   * @param $header
   */
  public function setHeader( $header )
  {
    $this->header = array_merge(
      $this->jsonDecode( $header ),
      [
        'alg' => "HS256",
        'typ' => "JWT"
      ]
    );
  }

  /**
   * Get header
   */
  public function getHeader()
  {
    return array_merge(
      $this->header,
      [
        'alg' => "HS256",
        'typ' => "JWT"
      ]
    );
  }

  /**
   * Set payload
   *
   * @param $payload
   */
  public function setPayload( $payload )
  {
    $this->payload = $this->jsonDecode( $payload );
  }

  /**
   * Get payload
   */
  public function getPayload()
  {
    return $this->payload;
  }

  /**
   * Set Signature
   *
   * @param $signature
   */
  public function setSignature( $signature )
  {
    $this->signature = $signature;
  }

  /**
   * Get signature
   */
  public function getSignature()
  {
    return $this->signature;
  }

  /**
   * Set token
   *
   * @param $token
   */
  public function setToken( $token )
  {
    $this->token = $token;
  }

  /**
   * Get token
   */
  public function getToken()
  {
    return $this->token;
  }
}
