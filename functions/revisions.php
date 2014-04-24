<?php
function dokumento_revisions_init() {
	add_rewrite_endpoint( 'revisions', EP_PERMALINK );
}
add_action('init', 'dokumento_revisions_init');

function dokumento_revisions_template_include( $template ) {
	if( !is_revision() || !is_single() ) {
		return $template;
	}
	
	$revisions_template = locate_template( array( 'single-revisions.php' ) );
	if( !$revisions_template ) {
		return $template;
	}
	
	return $revisions_template;
}
add_filter( 'template_include', 'dokumento_revisions_template_include', 99 );

function is_revision() {
	global $wp_query;
	if( isset( $wp_query->query['revisions'] ) ) {
		return true;
	}
	
	return false;
}