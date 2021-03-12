<?php
$appId = "304484074305214";
$appSecret = "62b6744b2a6f47a63583b680f365c614";
$accessToken = "IGQVJVdXR2Q01mWlo0bzVaS0V0dk9ET2dDR19ESEQtcG45X1NaVUV4RXp0UVFXQU82eTFQd0ZAla1pscDFWb2o5ZAVYxWUlBaFdIczRfX2pXTmNxLXNWNU1KcE1QYXRhZAWI1Y0lnYm5OVjlFV1dYMG5qLQZDZD";

if( isset( $_REQUEST['nw'] ) && isset( $_REQUEST['do'] ) ) {
	switch( $_REQUEST['nw'] ) {
		case "instagram":
			switch( $_REQUEST['do'] ) {
				case "auth":
				case "deauth";
				case "destroy";
					//...
				break;
			}
		break;
	}
}


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
