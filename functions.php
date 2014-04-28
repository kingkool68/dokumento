<?php

add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
	
// custom menu support
register_nav_menus(
	array(
		'header_menu' => 'Header Menu'
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
		$to = time();
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
			$plural='';
			if ($amount > 1) {
				$plural='s';
			} 
			
			$result[] = $amount.' '.$block['name'].$plural;
			$diff -= $amount*$block['amount'];
			$current_level++;
		}
	}
	
	return implode(' ',$result);
}

//Include functions
$dokumento_functions_path = TEMPLATEPATH . '/functions';
include  $dokumento_functions_path . '/css-js.php';
include  $dokumento_functions_path . '/toc.php';
include  $dokumento_functions_path . '/revisions.php';
include  $dokumento_functions_path . '/backlinks.php';