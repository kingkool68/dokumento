<?php
// post thumbnail support
add_theme_support( 'post-thumbnails' );
	
// custom menu support
add_theme_support( 'menus' );
if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus(
		array(
	  		  'header_menu' => 'Header Menu',
	  		  'sidebar_menu' => 'Sidebar Menu',
	  		  'footer_menu' => 'Footer Menu'
	  	)
	);
}
	
// Removes Trackbacks from the comment cout
add_filter('get_comments_number', 'comment_count', 0);
function comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;
		$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
		return count($comments_by_type['comment']);
	} else {
		return $count;
	}
}
	
// category id in body and post class
function category_id_class($classes) {
	global $post;
	foreach((get_the_category($post->ID)) as $category)
		$classes [] = 'cat-' . $category->cat_ID . '-id';
		return $classes;
}
	add_filter('post_class', 'category_id_class');
	add_filter('body_class', 'category_id_class');

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

//Register CSS
function register_dokumento_styles() {
	wp_register_style( 'reset', get_template_directory_uri() . '/css/reset.css', false, NULL, 'all' );
	wp_register_style( 'main', get_template_directory_uri() . '/css/main.css', array('reset'), NULL, 'all' );
}
add_action( 'init', 'register_dokumento_styles' );



/* Register Custom Post Types
function register_dokumento_post_types() {
  $common_args = array(
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'taxonomies' => array('post_tag'),
    'supports' => array( 'title', 'editor', 'revisions' )
  ); 
  
  $args = &$common_args;
  $args['menu_position'] = 5;
  $args['labels'] = array(
    'name' => 'Design',
    'singular_name' => 'Design',
  );
  register_post_type('design', $args);
  
  $args = &$common_args;
  $args['menu_position'] = 4;
  $args['labels'] = array(
    'name' => 'Publishing',
    'singular_name' => 'Publishing',
  );
  register_post_type('publishing', $args);
  
  $args = &$common_args;
  $args['menu_position'] = 6;
  $args['labels'] = array(
    'name' => 'Code',
    'singular_name' => 'Code',
  );
  register_post_type('code', $args);
}
add_action( 'init', 'register_dokumento_post_types' );

*/

function register_default_dokumento_post_type() {
	$args = array(
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'taxonomies' => array('post_tag'),
    'supports' => array( 'title', 'editor', 'revisions' ),
	'menu_position' => 5,
	'labels' => array(
    	'name' => 'Documentation',
    	'singular_name' => 'Documentation',
  	)
  );
  register_post_type('documentation', $args);
}
add_action( 'init', 'register_default_dokumento_post_type' );

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
	$tags = get_dokumento_tags();
	
	if( $tags ):
	?>
	<div id="tags">
		<h2>Tags</h2>
		<ul>
	<?php foreach( $tags as $tag ): ?>
			<li><a href="<?php echo get_term_link( $tag, 'post_tag' ); ?>"><?php echo $tag->name; ?></a></li>
	<?php endforeach;?>
		</ul>
	<?php
	endif;
}

?>