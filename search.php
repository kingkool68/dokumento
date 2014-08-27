<?php
global $wp_query;

function is_direct_match() {
	global $wpdb;
	
	$args = array(
		'exclude_from_search' => false
	);
	$post_types = array_values( get_post_types( $args, 'names') );
	$post_type = esc_sql( $post_types );
	$post_type_in_string = "'" . implode( "','", $post_type ) . "'";
	$sql = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = lower(%s) AND post_type IN ($post_type_in_string)", strtolower( get_search_query() ) );
	
	$post = $wpdb->get_var( $sql );
	if ( $post ) {
		return get_post( $post );
	}

	return NULL;
}

function dokumento_create_new_page() {
	$output = '';
	if( !is_direct_match() ) {
		$admin_query = 'post-new.php?post_title=' . urlencode( ucfirst( get_search_query() ) );
		$output = '<p>Create a new page called <a href="' . get_admin_url() . $admin_query . '">' . ucfirst( get_search_query() ) . '</a>?</p>';
	}
	
	return $output;
}

if( ( $post = is_direct_match() ) && $wp_query->found_posts === 1 ) {
	$redirect = get_permalink( $post->ID );
	wp_redirect( $redirect );
	die();
}

get_header();
?>

<div id="content" role="main">
<?php if (have_posts()) : ?>
	<?php echo dokumento_create_new_page(); ?>
<?php while (have_posts()) : the_post(); ?>
		<h1 class="page-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		
			<?php the_excerpt(); ?>

			<div class="meta">
				<p><span title="<?php esc_attr_e( date( get_option('date_format') . ' ' . get_option('time_format'), strtotime($post->post_modified)) ) ?>" class="updated">Updated <?php echo dokumento_human_time_diff( 2, strtotime($post->post_modified) ); ?> ago</span> by <span class="author"><?php the_modified_author(); ?></span>.</p>
			</div>
	<?php endwhile; else: ?>
		<h1 class="page-title">No results found!</h1>
		<?php echo dokumento_create_new_page(); ?>
	<?php endif; ?>
</div>
<?php get_sidebar(); ?>


<?php get_footer(); ?>