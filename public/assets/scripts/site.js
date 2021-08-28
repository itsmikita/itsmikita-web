( function () {
  window.addEventListener( "load", () => {
    fetch( "/api" )
      .then( response => response.json() )
      .then( response => {
        console.log( response );
      } )
      .catch( error => {
        console.error( error );
      } );
  } );
} )();
