<?php
function dokumento_backlinks_init() {
	add_rewrite_endpoint( 'backlinks', EP_PERMALINK | EP_ATTACHMENT );
}
add_action('init', 'dokumento_backlinks_init');

function dokumento_backlinks_template_include( $template ) {
	if( !is_backlink() || !is_single() ) {
		return $template;
	}
	
	$backlinks_template = locate_template( array( 'single-backlinks.php' ) );
	if( !$backlinks_template ) {
		return $template;
	}
	
	return $backlinks_template;
}
add_filter( 'template_include', 'dokumento_backlinks_template_include', 99 );

function is_backlink() {
	global $wp_query;
	if( isset( $wp_query->query['backlinks'] ) ) {
		return true;
	}
	
	return false;
}

function get_backlinks( $post_id = NULL ){
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
	
	$permalink = get_permalink( $post_id );
	
	$args = array(
		'posts_per_page' => -1,
		's' => $permalink
	);
	
	$backlinks = get_posts( $args );
	
	return $backlinks;
}