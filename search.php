<?php get_header(); ?>

<div id="content" role="main">
<?php if (have_posts()) : ?>
	<p>Create a new page called <a href="<?php echo get_admin_url()?>post-new.php?post_title=<?php echo urlencode( ucfirst( get_query_var('s') ) );?>"><?php echo ucfirst( get_search_query() ); ?></a>?</p>
<?php while (have_posts()) : the_post(); ?>
		<h1 class="page-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		
			<?php the_excerpt(); ?>

			<div class="meta">
				<p><span title="<?php esc_attr_e( date( get_option('date_format') . ' ' . get_option('time_format'), strtotime($post->post_modified)) ) ?>" class="updated">Updated <?php echo dokumento_human_time_diff( 2, strtotime($post->post_modified) ); ?> ago</span> by <span class="author"><?php the_modified_author(); ?></span>.</p>
			</div>
	<?php endwhile; else: ?>
		<h1 class="page-title">No results found!</h1>
		<p>Create a new page called <a href="<?php echo get_admin_url()?>post-new.php?post_title=<?php echo urlencode( ucfirst( get_query_var('s') ) );?>"><?php echo ucfirst( get_search_query() ); ?></a>?</p>
	<?php endif; ?>
</div>
<?php get_sidebar(); ?>


<?php get_footer(); ?>