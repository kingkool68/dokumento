<?php get_header(); ?>

<div id="content" role="main">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1 class="page-title"><?php the_title(); ?></h1>
		<?php the_content(); ?>
		<?php dokumento_tags(); ?>
		<div class="meta">
			<p><span title="<?php esc_attr_e( date( get_option('date_format') . ' ' . get_option('time_format'), strtotime($post->post_modified)) ) ?>">Updated <?php echo dokumento_human_time_diff( 2, strtotime($post->post_modified) ); ?> ago</span> by <?php the_modified_author(); ?>.</p>
		</div>
	<?php endwhile; else: ?>
	
	<?php endif; ?>
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>