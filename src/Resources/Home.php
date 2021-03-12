<?php

namespace App\Resources;

use App\HTTP\Request;
use App\HTTP\Response;
use App\HTTP\Middleware;

class Home
{
  use Middleware;

  // URL Path
  private $path = "/api";

  /**
   * Handle HTTP Request
   * 
   * @param $request
   * @param $handler
   */
  public function process( $request, $handler )
  {
    $instagram = new Request(
      "GET",
      "https://graph.instagram.com/17841401572810170/media?fields=media_url,caption&access_token=IGQVJXc2p2amNpVGVNTGxnR3g3ZAjRiV1N4MlFVVnByZAkhZAUzEzajdxNnJaRElrNEVtdlNvZAno5UWYtYmJVdzdOYVdwM2ljYlJYcDd1MmpZAdzBTTXJla1QwbVNkWURQczBNSHI3eHhSbk10UXZAfdTZAoVgZDZD",
      [
        'Content-Type' => "application/json; charset=utf-8"
      ]
    );
    $response = $instagram->transmit();
    return new Response(
      $response->getBody(),
      [
        'Content-Type' => "application/json; charset=utf-8"
      ]
    );
  }
}
