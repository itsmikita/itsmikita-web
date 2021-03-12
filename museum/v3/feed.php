<?php

/**
 * This would be a custom feed of all my recent social activities
 */

/**

Facebook:
https://graph.facebook.com/itsmikita/posts?access_token=CAACEdEose0cBAAZBwyxx1USPjh4zebYtkr7MwkLgzxS7V0iR61sRZA7bkfai0ZBQw9safM5k1UO84M5OZBREZCCKVogJdsuDH8ZBpQBlvYb7cpPJnEP4KNNNH3ktnFK6Txebrck3ZCyODiaiIKV96AgIxqLZCGdrxSUZD

Instagram:
https://api.instagram.com/v1/users/10932427/media/recent?client_id=ae02ccecb15b4e99b5fcfeaebcf47e06&access_token=10932427.1fb234f.fbfefa04332948a19eb65a61127bf959

Soundcloud
https://api.soundcloud.com/users/108891/tracks.json?client_id=e01e47ec27e54c052d2770f834839867

Mixcloud:
http://api.mixcloud.com/itsmikita/feed/

 */

class Feeds {
	/**
	 * Constructor
	 */
	public function __construct() {
		
	}
	
	/**
	 * Grab Facebook
	 */
	public function grabFacebook() {
		$feed_url = 'https://graph.facebook.com/itsmikita/posts?access_token=CAACEdEose0cBAAZBwyxx1USPjh4zebYtkr7MwkLgzxS7V0iR61sRZA7bkfai0ZBQw9safM5k1UO84M5OZBREZCCKVogJdsuDH8ZBpQBlvYb7cpPJnEP4KNNNH3ktnFK6Txebrck3ZCyODiaiIKV96AgIxqLZCGdrxSUZD';
		
		if( ! $content = file_get_contents( $feed_url ) )
			return $this->error( 'Oh man! I\'m failed doing Facebook...' );
		
		$data = json_decode( $content );
		
		var_dump( $data );
	}
}

$feeds = new Feeds();
$feeds->grabFacebook();