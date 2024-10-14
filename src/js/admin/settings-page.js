document.addEventListener( 'DOMContentLoaded', () => {
	document
		.querySelectorAll( '.ddna-settings-accordion-heading' )
		.forEach( ( item ) => {
			item.addEventListener( 'click', ( accordeonEvent ) => {
				accordeonEvent.preventDefault();
				accordeonEvent.target.setAttribute(
					'aria-expanded',
					'true' ===
						accordeonEvent.target.getAttribute( 'aria-expanded' )
						? 'false'
						: 'true'
				);
				accordeonEvent.currentTarget.parentElement
					.querySelectorAll( '.ddna-settings-accordion-panel' )
					.forEach( ( panel ) => {
						if ( panel.getAttribute( 'hidden' ) === 'hidden' ) {
							panel.removeAttribute( 'hidden' );
						} else {
							panel.setAttribute( 'hidden', 'hidden' );
						}
					} );
			} );
		} );
} );
