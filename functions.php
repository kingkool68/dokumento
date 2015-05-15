<?php

add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
	
// custom menu support
register_nav_menus(
	array(
		'main-menu' => 'Main Menu'
	)
);

//Nice Search URL
function txfx_nice_search() {
    if ( is_search() && strpos($_SERVER['REQUEST_URI'], '/wp-admin/') === false && strpos($_SERVER['REQUEST_URI'], '/search/') === false ) {
        wp_redirect(get_bloginfo('home') . '/search/' . str_replace(' ', '+', str_replace('%20', '+', get_query_var('s'))) . '/');
        exit();
    }
}
add_action('template_redirect', 'txfx_nice_search');

function replace_pluses_with_spaces($s) {
	return str_replace('+', ' ', $s);
}
add_filter('get_search_query', 'replace_pluses_with_spaces');

//No admin bar because I'm the devil
add_filter('show_admin_bar', '__return_false');

//Remove features from the post post type.
function dokumento_init() {
	$features_to_be_removed = array('thumbnail', 'trackbacks', 'comments', 'custom-fields');
	foreach( $features_to_be_removed as $feature ) {
		remove_post_type_support( 'post', $feature );
	}
}
add_action( 'init', 'dokumento_init' );

function dockumento_default_attachment_display_settings() {
	update_option( 'image_default_align', 'right' );
	update_option( 'image_default_link_type', 'post' );
	update_option( 'image_default_size', 'medium' );
}
add_action( 'after_setup_theme', 'dockumento_default_attachment_display_settings' );

function dokumento_remove_post_metaboxes() {
    //args: $categoryslug.'div', $posttype, $location
    remove_meta_box('categorydiv', 'post', 'side');
}
add_action('admin_init', 'dokumento_remove_post_metaboxes');

//After editing a post or a term, we should redirect you to the frontend to see your changes.
function dokumento_edit_post_redirect($post_id, $post) {
	if( !isset($_POST['post_status'] ) || !$_POST['post_status'] ) {
		return;
	}
	
	$rev = array_shift( get_revisions( $post_id ) );
	if ( $parent_id = wp_is_post_revision( $rev->ID) ) {
		$permalink = get_permalink( $parent_id );
	} else {
		$permalink = get_permalink( $post_id );
	}
	
	if( $permalink && $_POST['post_status'] == 'publish' ) {
		wp_redirect( $permalink );
		die();
	}
}
add_action('wp_insert_post', 'dokumento_edit_post_redirect', 99, 2);

function dokumento_edit_attachment_redirect($attachment_id) {
	$permalink = get_permalink( $attachment_id );

	//Prevent infinite loop...
	remove_action('edit_attachment', 'dokumento_edit_attachment_redirect', 99);
	
	$post = array(
		'ID' => $attachment_id,
		'post_modified' => current_time( 'mysql' ),
		'post_modified_gmt' => current_time( 'mysql', 1)
	);
	wp_update_post( $post );
	
	if( $permalink && $_POST['save'] == 'Update' ) {
		wp_redirect( $permalink );
		die();
	}
}
add_action('edit_attachment', 'dokumento_edit_attachment_redirect', 99 );

function dokumento_edited_term_redirect($term_id, $tt_id, $taxonomy) {
	$permalink = get_term_link( $term_id, $taxonomy );
	if( $permalink ) {
		wp_redirect( $permalink );
		die();
	}
}
add_action('edited_term', 'dokumento_edited_term_redirect', 99, 3);

//Tags
function get_dokumento_tags() {
	global $post;
	$args = array(
		'orderby' => 'count'
	);
	
	$tags = wp_get_object_terms( $post->ID, array('post_tag'), $args );
	$new_tags = array();
	foreach( $tags as $tag ):
		if( $tag->count > 1 ) {
			$new_tags[] = $tag;
		}
	endforeach;

	return $new_tags;
}
function dokumento_tags() {
	global $post;
	$tags = get_dokumento_tags();
	
	if( $tags ):
	?>
	<div id="tags">
		<h2>Tags</h2>
		<ul>
	<?php foreach( $tags as $tag ):
		if($post->post_type != 'post') {
			$permalink = get_site_url() . '/' . $post->post_type . '/tag/' . $tag->slug . '/';
		} else {
			$permalink = get_term_link( $tag, 'post_tag' );
		}
	?>
			<li><a href="<?php echo $permalink; ?>"><?php echo $tag->name; ?></a></li>
	<?php endforeach;?>
		</ul>
	</div>
	<?php
	endif;
}

//My own human time diff function from http://www.php.net/manual/en/ref.datetime.php#90989
function dokumento_human_time_diff( $levels = 2, $from, $to = false ) {
	if( !$to ) {
		$to = strtotime( current_time( 'mysql' ) );
	}
	
	$blocks = array(
		array('name'=>'year','amount'	=>	60*60*24*365	),
		array('name'=>'month','amount'	=>	60*60*24*31	),
		array('name'=>'week','amount'	=>	60*60*24*7	),
		array('name'=>'day','amount'	=>	60*60*24	),
		array('name'=>'hour','amount'	=>	60*60		),
		array('name'=>'minute','amount'	=>	60		),
		array('name'=>'second','amount'	=>	1		)
	);
   
	$diff = abs($from-$to);
   
	$current_level = 1;
	$result = array();
	foreach($blocks as $block) {
		if ($current_level > $levels) {
			break;
		}
		
		if ($diff/$block['amount'] >= 1) {
			$amount = floor($diff/$block['amount']);
			$plural = '';
			if ($amount > 1) {
				$plural = 's';
			} 
			
			$result[] = $amount . ' ' . $block['name'] . $plural;
			$diff -= $amount * $block['amount'];
			$current_level++;
		}
	}
	
	if( empty($result) ) {
		$result[] = '0 seconds';
	}
	
	return implode(' ', $result);
}

function dokumento_attachment_link( $link, $post_id ) {
	$post = get_post( $post_id );
	$new_link = get_site_url() . '/attachment/' . $post->post_name . '/';
	return $new_link;
}
add_filter( 'attachment_link', 'dokumento_attachment_link', 2, 10 );

function dokumento_get_edit_post_link( $id = 0 ) {
	if ( ! $post = get_post( $id ) ) {
		return;
	}
	
	$action = '&action=edit';
	if ( $post->post_type === 'revision' ) {
		$action = '';
	}
	
	$post_type_object = get_post_type_object( $post->post_type );
	if ( !$post_type_object ) {
		return;
	}
	
	return admin_url( sprintf( $post_type_object->_edit_link . $action, $post->ID ) );
}


/* Show all articles */
function dokumento_show_all_template_redirect( $template ) {
	global $wp_query;
	
	if( !isset( $wp_query->query['name'] ) || $wp_query->query['name'] != 'all' ) {
		return $template;
	}
	
	$wp_query->is_404 = false;
	$wp_query->is_archive = true;
	$wp_query->is_single = false;
	$wp_query->is_page = false;
	
	return $template;
}
add_filter('template_redirect', 'dokumento_show_all_template_redirect' );

function dokumento_show_all_pre_get_posts( $q ) {
	if( !isset($q->query['name']) || $q->query['name'] != 'all' || !$q->is_main_query() ) {
		return;
	}
	$q->set( 'is_404', false );
	$q->set( 'is_single', false );
	$q->set( 'is_page', false );
	$q->set( 'is_archive', true );
	$q->set( 'posts_per_page', -1 );
	$q->set( 'post_type', 'post' );
	$q->set( 'name', '' );
	$q->set( 'post_status', 'publish' );
	$q->set( 'order', 'ASC' );
}
add_action( 'pre_get_posts', 'dokumento_show_all_pre_get_posts' );

//Include functions
$dokumento_functions_path = TEMPLATEPATH . '/functions';
include  $dokumento_functions_path . '/css-js.php';
include  $dokumento_functions_path . '/toc.php';
include  $dokumento_functions_path . '/revisions.php';
include  $dokumento_functions_path . '/backlinks.php';