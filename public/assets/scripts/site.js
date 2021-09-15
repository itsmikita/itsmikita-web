( function () {
  window.addEventListener( "load", () => {
    fetch( "/api/media" )
      .then( response => response.json() )
      .then( response => {
        let next = 0;
        let interval = setInterval( () => {
          if( next > response.length - 1 ) {
            next = 0;
          }
          document.body.style.backgroundImage = "url('" + response[ next ].url + "')";
          next++;
        }, 87 );
      } )
      .catch( error => {
        console.error( error );
      } );
  } );
} )();
