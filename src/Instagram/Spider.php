<?php

namespace App\Instagram;

use App\HTTP\Request;
use Exception;

class Spider
{
  // Extracted Instagram Media
  private $media = [];

  /**
   * Constructor
   */
  public function __construct()
  {
    
  }

  /**
   * Fetch Instagram Account
   * 
   * @param $username
   */
  public function fetchSource( $username )
  {
    $source = file_get_contents( "https://www.instagram.com/{$username}/" );
    return $source;
  }

  /**
   * Extract JSON from source
   * 
   * @param $source
   */
  public function extractJSON( $source )
  { 
    $pattern = '#<\s*?script\b[^>]*>window\._sharedData = (\{.*?\});?</script\b[^>]*>#s';
    if( ! preg_match( $pattern, $source, $matches ) ) {
      throw new Exception( "Failed to not extract JSON from source!" );
    }
    $data = json_decode( $matches[ 1 ], true );
    return $data;
  }

  /**
   * Parse JSON Edges to Media
   * 
   * @param $edges
   */
  public function parseEdges( $edges )
  {
    foreach( $edges as $edge ) {
      $node = $edge['node'];
      if( "GraphSidecar" == $node['__typename'] ) {
        $this->parseEdges( $node['edge_sidecar_to_children']['edges'] );
      }
      $this->media[] = [
        'type' => $node['is_video'] ? "video" : "image",
        'id' => $node['id'],
        'shortcode' => $node['shortcode'],
        'width' => $node['dimensions']['width'],
        'height' => $node['dimensions']['height'],
        'image' => $node['display_url'],
        'video' => $node['video_url'] ?? null,
        'description' => $node['edge_media_to_caption']['edges'][ 0 ]['node']['text'] ?? ""
      ];
    }
    return $this->media;
  }

  /**
   * Get Instagram Profile Media
   * 
   * @param $username
   */
  public function getMedia( $username )
  {
    $source = $this->fetchSource( $username );
    $data = $this->extractJSON( $source );
    $media = $this->parseEdges( $data );
    return $media;
  }
}