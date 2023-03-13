<?php

namespace App\HTTP;

/**
 * HTTP Message Trait
 */
trait Message
{
  // HTTP Headers
  private $headers = [];

  // HTTP Body
  private $body;

  /**
   * Constructor
   *
   * @param $body
   * @param $headers
   */
  public function __construct( $headers, $body )
  {
    $this->setBody( $body );
    $this->setHeaders( $headers );
  }

  /**
   * Set HTTP Headers
   *
   * @param $headers
   */
  public function setHeaders( $headers )
  {
    if( is_array( $headers ) ) {
      foreach( $headers as $name => $value ) {
        $this->setHeader( $name, $value );
      }
    }
  }

  /**
   * Set HTTP Header
   *
   * @param $name
   * @param $value
   */
  public function setHeader( $name, $value )
  {
    $this->headers[ $name ] = $value;
  }

  /**
   * Get HTTP Headers (as num array)
   */
  public function getHeaders()
  {
    return array_map( function( $name, $value ) {
      return "{$name}: {$value}";
    }, array_keys( $this->headers ), array_values( $this->headers ) );
  }

  /**
   * Check wether HTTP Header exist
   *
   * @param $name
   */
  public function hasHeader( $name )
  {
    return isset( $this->headers[ $name ] );
  }

  /**
   * Get HTTP Header
   *
   * @param $name
   */
  public function getHeader( $name )
  {
    if( $this->hasHeader( $name ) ) {
      return $this->headers[ $name ];
    }
  }

  /**
   * Set HTTP Body
   *
   * @param $body
   */
  public function setBody( $body )
  {
    $this->body = $body;
  }

  /**
   * Get HTTP Body
   */
  public function getBody()
  {
    return $this->body;
  }

  /**
   * Has HTTP Body
   */
  public function hasBody()
  {
    return ! empty( $this->body );
  }
}
