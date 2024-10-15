document.addEventListener( 'DOMContentLoaded', () => {
	document
		.querySelectorAll( '.js-con-utils-regenerate' )
		.forEach( ( item ) => {
			item.addEventListener( 'click', ( e ) => {
				e.preventDefault();

				fetch( '/wp-json/con/v1/utils/regenerate/', {
					method: 'POST',
				} )
					.then( ( response ) => {
						if ( response.ok ) {
							return Promise.resolve( response );
						}
						return Promise.reject( new Error( 'Failed to load' ) );
					} )
					.then( ( response ) => response.json() ) // parse response as JSON
					.then( ( json ) => {
						if ( json.success ) {
							console.log( json );
						}
					} )
					.catch( function ( error ) {
						console.log( `Error: ${ error.message }` );
					} );
			} );
		} );

	document.querySelectorAll( '.js-con-utils-reset' ).forEach( ( item ) => {
		item.addEventListener( 'click', ( e ) => {
			e.preventDefault();

			fetch( '/wp-json/con/v1/utils/reset/', {
				method: 'POST',
			} )
				.then( ( response ) => {
					if ( response.ok ) {
						return Promise.resolve( response );
					}
					return Promise.reject( new Error( 'Failed to load' ) );
				} )
				.then( ( response ) => response.json() ) // parse response as JSON
				.then( ( json ) => {
					if ( json.success ) {
						console.log( json );
					}
				} )
				.catch( function ( error ) {
					console.log( `Error: ${ error.message }` );
				} );
		} );
	} );
} );
