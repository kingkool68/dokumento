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

function get_revisions( $post_id = NULL ){
	global $post;
	
	if( is_object( $post_id ) && isset( $post_id->ID ) ) {
		$post_id = $post_id->ID;
	}
	if( !$post_id && isset( $post->ID ) ) {
		$post_id = $post->ID;
	}
	
	$post_id = intval( $post_id );
	if( !$post_id ) { //Welp, we tried.
		return array();
	}
	
	$args = array(
		'post_parent' => $post->ID,
		'post_type' => 'revision',
		'posts_per_page' => -1,
		'post_status' => 'any'
	);
	
	$revisions = get_posts( $args );
	
	foreach( $revisions as $index => $rev ) {
		if( strstr( $rev->post_name, $post_id . '-autosave-' ) ) {
			unset( $revisions[$index] );
		}
	}
	
	return $revisions;
}

function get_dokumento_user( $user_id = FALSE, $property = FALSE ) {
	if( !$user_id || !$property ) {
		return false;
	}
	
	$user = get_user_by( 'id', $user_id );
	if( isset( $user->{$property} ) ) {
		return $user->{$property};
	}
	
	//If we've made it this far something is wrong...
	return false;
}