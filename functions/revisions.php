<?php
function dokumento_revisions_init() {
	add_rewrite_endpoint( 'revisions', EP_PERMALINK | EP_PAGES | EP_ROOT );
}
add_action('init', 'dokumento_revisions_init');

function dokumento_pre_get_posts($query) {
	if ($query->is_home && $query->is_main_query() && isset( $query->query['revisions'] ) && empty( $query->query['revisions'] )  ) {
		$front_page_id = get_option('page_on_front');
		$query->set( 'p', $front_page_id );
		$query->set( 'post_type', array('post', 'page') );
	}
	
	//var_dump( $query );
	//die();
	
	return $query;
}
add_filter('pre_get_posts','dokumento_pre_get_posts');

function dokumento_revisions_template_include( $template ) {
	if( !is_revision() || (!is_single() && !is_home() ) ) {
		return $template;
	}
	
	$revisions_template = locate_template( array( 'single-revisions.php' ) );
	if( !$revisions_template ) {
		return $template;
	}
	
	return $revisions_template;
}
add_filter( 'template_include', 'dokumento_revisions_template_include', 99 );

function dokumento_revisions_admin_enqueue_scripts($hook) {
	if( $hook != 'post.php' ) {
        return;
	}
    wp_enqueue_script( 'dokumento-revisions', get_template_directory_uri() . '/js/dokumento-revisions.js', array('jquery') );
	wp_enqueue_style( 'dokumento-revisions', get_template_directory_uri() . '/css/dokumento-revisions-admin.css', array(), '0.1', 'all' );
}
add_action( 'admin_enqueue_scripts', 'dokumento_revisions_admin_enqueue_scripts' );

function dokumento_edit_form_top() {
	$screen = get_current_screen();
	if( $screen->base != 'post' ) {
		return;
	}
	?>
	<fieldset id="dokumento-revision-fields" style="display:none;">
		<label for="dockumento-revision-message">Enter a summary fo your changes</label>
		<textarea name="dockumento-revision-message" id="dockumento-revision-message"></textarea>
		
		<label><input type="checkbox" id="dockumento-revision-minor" name="dockumento-revision-minor" value="true"> Minor Revision</label>
	</fieldset>
	<?php
}
add_action( 'edit_form_top', 'dokumento_edit_form_top', 2, 100 );

function dokumento_save_post( $post_id ) {
	$rev = array_shift( get_revisions( $post_id ) );
	
	if ( $parent_id = wp_is_post_revision( $rev->ID) ) {
		$changelog = get_post_meta( $parent_id, 'revision_changelog', true );
		if( !$changelog ) {
			$changelog = array();
		}
		$changelog[$rev->ID] = $_REQUEST['dockumento-revision-message'];
		
		update_post_meta($parent_id, 'revision_changelog', $changelog );
	}
}
add_action( 'save_post', 'dokumento_save_post' );

/* Helper Functions */
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
	
	$cache_key = 'dokumento_revs_' . $post_id;
	
	if( $revisions = wp_cache_get( $cache_key ) ) {
		return $revisions;
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
	
	wp_cache_add( $cache_key, $revisions );
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