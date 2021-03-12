<?php

require __DIR__ . "/config.php";

/**
 * PSR-4 Class Autoloader
 *
 * @param $class
 */
spl_autoload_register( function( $class ) {
  if( defined( 'NAMESPACE_PREFIX' ) && 0 === strpos( $class, NAMESPACE_PREFIX ) ) {
    $file = __DIR__ . "/src/" . str_replace(
      "\\",
      "/",
      substr( $class, strlen( NAMESPACE_PREFIX ) )
    ) . ".php";
    if( is_readable( $file ) ) {
      require $file;
    }
  }
} );
