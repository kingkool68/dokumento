<?php
global $wp_query;
if( $wp_query->found_posts === 1 ) {
	$post = $wp_query->posts[0];
	$redirect_to = get_permalink( $post->ID );
	if( get_permalink( $post->ID ) ) {
		wp_redirect( $redirect_to );
	}
}


function dokumento_create_new_page_from_search() {
	$output = '';
	
	$post_types = array_values( get_post_types( array(), 'names') );
	$direct_match = get_page_by_title( get_search_query(), 'OBJECT', $post_types );
	
	if( !$direct_match ) {
		$admin_query = 'post-new.php?post_title=' .  urlencode( ucfirst( get_query_var('s') ) );
		$output = '<p>Create a new page called <a href="' . get_admin_url( $admin_query ) . '">' . ucfirst( get_search_query() ) . '</a>?</p>';
	}
	
	return $output;
}

get_header();
?>

<div id="content" role="main">
<?php if (have_posts()) : ?>
	<?php echo dokumento_create_new_page_from_search(); ?>
<?php while (have_posts()) : the_post(); ?>
		<h1 class="page-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		
			<?php the_excerpt(); ?>

			<div class="meta">
				<p><span title="<?php esc_attr_e( date( get_option('date_format') . ' ' . get_option('time_format'), strtotime($post->post_modified)) ) ?>" class="updated">Updated <?php echo dokumento_human_time_diff( 2, strtotime($post->post_modified) ); ?> ago</span> by <span class="author"><?php the_modified_author(); ?></span>.</p>
			</div>
	<?php endwhile; else: ?>
		<h1 class="page-title">No results found!</h1>
		<?php echo dokumento_create_new_page_from_search(); ?>
	<?php endif; ?>
</div>
<?php get_sidebar(); ?>


<?php get_footer(); ?>