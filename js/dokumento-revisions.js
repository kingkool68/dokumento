/**
 * Script necessary to launch the modal.
 * When adapting for your own use, you'll want to rename / namespace the "aut0poietic_iframe_modal_close_handler" function
 * to prevent collision as it lives in the global scope.
 */
jQuery( function ( $ ) {
	$( '#publish' ).on( "click" , function ( e ) {
		e.preventDefault();

		// Retrieve the URL for th iframe stored in data-content-url created in Plugin::metabox_content
		var url = $( e.currentTarget ).attr( "data-content-url" );
		
		var dialogHTML = '<div tabindex="0" id="dokumento-revisions-modal" role="dialog">';
		dialogHTML += 		'<div class="media-modal wp-core-ui">';
		dialogHTML += 			'<a role="button" class="media-modal-close" href="#" title="Close"><span class="media-modal-icon ir">Close</span></a>';
		dialogHTML += 			'<div class="media-modal-content">';
		dialogHTML +=				'<div class="media-frame-title">';
		dialogHTML +=					'<h1>Revision Summary</h1>';
		dialogHTML +=				'</div>';
		//dialogHTML += 				'<div class="media-frame-content">';
		dialogHTML += 					$('#dokumento-revision-fields').html();
		dialogHTML += 					'<div class="media-frame-toolbar">';
		dialogHTML += 						'<div class="media-toolbar">';
		dialogHTML +=							'<div class="media-toolbar-primary"><a href="#" class="button media-button button-primary button-large media-button-insert" disabled="disabled">Insert into post</a></div>';
		dialogHTML += 						'</div>';
		dialogHTML += 					'</div>';
		//dialogHTML += 				'</div>';
		dialogHTML += 			'</div>';
		dialogHTML += 		'</div>';
		dialogHTML += 		'<div class="media-modal-backdrop" role="presentation"></div>';
		dialogHTML += '</div>';
		
		$dialogHTML = $( dialogHTML );
		$dialogHTML.find('#iframe_modal-frame' ).attr('src' , url );

		// Attach the close button event handler.
		$dialogHTML.find( '.media-modal-close' ).on( "click" , window.aut0poietic_iframe_modal_close_handler );
		$dialogHTML.find( '.media-modal-backdrop' ).on( "click" , window.aut0poietic_iframe_modal_close_handler );

		// When the user shifts focus (typically through pressing the tab key ).
		// If the new focus target is not a child of the modal or the modal itself,
		// set the focus on the modal -- thus resetting the tab order.
		$( document ).on( "focusin" , function( e ) {
			var $element = jQuery( '#dokumento-revisions-modal' );
			if ( $element[0] !== e.target && !$element.has( e.target ).length ) {
				$element.focus();
			}
		} ) ;
		// Set overflow to hidden on the body, preventing the user from scrolling the
		// disabled content and append the dialog to the body.
		$( "body" ).css( { "overflow": "hidden" } ).append( $dialogHTML );
	} );

	/**
	 *  Global Modal.close method.
	 *  Unfortunately, we must expose the method globally so that the iframe can access the method, removing
	 *  event-handlers added during Modal.open
	 * @param e jQuery-Normalized event object
	 */
	window.aut0poietic_iframe_modal_close_handler = function( e ){
		e.preventDefault();
		$( document ).off( "focusin" ) ;
		$( "body" ).css( { "overflow": "auto" } );
		$( "#dokumento-revisions-modal .media-modal-close" ).off( "click" );
		$( "#dokumento-revisions-modal").remove( );
	};

} );