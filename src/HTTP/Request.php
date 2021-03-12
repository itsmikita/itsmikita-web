<?php

namespace App\HTTP;

use App\HTTP\Message;
use App\HTTP\Response;

/**
 * HTTP Request Class
 */
class Request
{
  use Message;

  // Request Cookies
  private $cookies = [];

  // Request HTTP Method
  private $method;

  // Request URL
  private $url;

  // Request Params
  private $params = [];

  // Uploaded files
  private $files = [];

  /**
   * Constructor
   *
   * @param $method
   * @param $url
   * @param $headers
   * @param $body
   */
  public function __construct( $method, $url, $headers, $body = null )
  {
    $this->setURL( $url );
    $this->setMethod( $method );
    $this->setHeaders( $headers );
    $this->setBody( $body );
  }

  /**
   * Set Request URL
   *
   * @param $url
   */
  public function setURL( $url )
  {
    $this->url = $url;
  }

  /**
   * Get Request URL
   */
  public function getURL()
  {
    return $this->url;
  }

  /**
   * Get URL from globals
   */
  public static function getURLFromGlobals()
  {
    $scheme = isset( $_SERVER['HTTPS'] ) && "off" !== $_SERVER['HTTPS']
      ? "https" : "http";
    $domain = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_ADDR'];
    $path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
    $query = $_SERVER['QUERY_STRING'] ?? "";

    return "{$scheme}://{$domain}{$path}";
  }

  /**
   * Get URL Path
   */
  public function getPath()
  {
    return parse_url( $this->url, PHP_URL_PATH );
  }

  /**
   * Set HTTP Request Method
   *
   * @param $method
   */
  public function setMethod( $method )
  {
    $this->method = $method;
  }

  /**
   * Get HTTP Request Method
   */
  public function getMethod()
  {
    return $this->method;
  }

  /**
   * Set HTTP Request Param
   *
   * @param $name
   * @param $value
   */
  public function setParam( $name, $value )
  {
    $this->params[ $name ] = $value;
  }

  /**
   * Check wether Request param name exists
   *
   * @param $name - Can be array of names
   */
  public function hasParam( $name )
  {
    if( ! is_array( $name ) ) {
      $name = [ $name ];
    }
    foreach( $name as $key ) {
      if( ! isset( $this->params[ $key ] ) ) {
        return false;
      }
    }
    return true;
  }

  /**
   * Get HTTP Request Param
   *
   * @param $name
   */
  public function getParam( $name )
  {
    if( $this->hasParam( $name ) ) {
      return $this->params[ $name ];
    }
  }

  /**
   * Set HTTP Request Params
   *
   * @param $params
   */
  public function setParams( $params )
  {
    foreach( $params as $name => $value ) {
      $this->setParam( $name, $value );
    }
  }

  /**
   * Get all HTTP Request Params
   */
  public function getParams()
  {
    return $this->params;
  }

  /**
   * Get HTTP Request Body from globals
   */
  public static function getBodyFromGlobals()
  {
    return file_get_contents( "php://input" );
  }

  /**
   * Set Uploaded Files
   *
   * @param $files
   */
  public function setFiles( $files )
  {
    $this->files = $files;
  }

  /**
   * Get Uploaded Files
   */
  public function getFiles()
  {
    return $this->files;
  }

  /**
   * Set HTTP Request Cookies
   *
   * @param $cookies
   */
  public function setCookies( $cookies )
  {
    foreach( $cookies as $name => $value )
    {
      $this->setCookie( $name, $value );
    }
  }

  /**
   * Get HTTP Request Cookies
   */
  public function getCookies()
  {
    return $this->cookies;
  }

  /**
   * Set single Cookie
   *
   * @param $name
   * @param $value
   */
  public function setCookie( $name, $value )
  {
    $this->cookies[ $name ] = $value;
  }

  /**
   * Get single Cookie
   *
   * @param $name
   */
  public function getCookie( $name )
  {
    if( isset( $this->cookies[ $name ] ) ) {
      return $this->cookies[ $name ];
    }
  }

  /**
   * Create HTTP Request from Globals
   */
  public static function createFromGlobals()
  {
    $method = $_SERVER['REQUEST_METHOD'] ?? "GET";
    $url = self::getURLFromGlobals();
    $headers = getallheaders();
    $body = self::getBodyFromGlobals();
    $request = new Request( $method, $url, $headers, $body );
    $request->setParams( $_REQUEST );
    $request->setFiles( $_FILES );
    $request->setCookies( $_COOKIE );
    return $request;
  }

  /**
   * Transmit Request to the remote server
   */
  public function transmit()
  {
    $request = curl_init();
    curl_setopt_array(
      $request,
      [
        CURLOPT_CUSTOMREQUEST => $this->getMethod(),
        CURLOPT_URL => $this->getURL(),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $this->getHeaders(),
        CURLOPT_POSTFIELDS =>
          $this->hasBody() ? json_encode( $this->getBody() ) : null,
        CURLINFO_HEADER_OUT => true
      ]
    );
    $body = curl_exec( $request );
    $headers = curl_getinfo( $request, CURLINFO_HEADER_OUT );
    $status = curl_getinfo( $request, CURLINFO_RESPONSE_CODE );
    return new Response( $body, $headers, $status );
  }
}
