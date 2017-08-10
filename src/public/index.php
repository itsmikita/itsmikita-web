<?php

/**
 * It's Mikita playground
 */

$feed = json_decode( file_get_contents( "https://www.instagram.com/itsmikita/media/" ) );
$images = [];

foreach( $feed->items as $item ) {
	$images[] = $item->images->standard_resolution->url;
}

?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="description" content="Sound Alchemist. Visual Therapist. Code Poetrist.">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Hello! It's Mikita — Sound Alchemist and Visual Idiot.</title>

		<link rel="stylesheet" href="assets/styles/site.css">

		<script src="assets/scripts/jquery.js"></script>
		<script src="assets/scripts/site.js"></script>
		<script type="text/javascript">
			var images = <?php print json_encode( $images ); ?>
		</script>
	</head>
	<body>
    <img id="logo" src="assets/images/itsmikita.svg">
		<img id="itsmikita" src="">
		<a href="mailto:itsmikita@gmail.com?subject=Hello%20from%20Web" id="mask"></a>
	</body>
</html>
