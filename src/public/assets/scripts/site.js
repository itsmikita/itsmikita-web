;( function( $ ) {
	$( function() {
		var next = 0;
		var interval = setInterval( function() {
			if( next > images.length )
				next = 0;
			
			$( "#itsmikita" ).attr( 'src', window.images[ next ] );
			
			next++;
		}, 87 );
	} );
} )( jQuery );