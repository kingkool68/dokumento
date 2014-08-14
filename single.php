<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<?php if( !is_front_page() ): ?>
			<h1 class="page-title"><?php the_title(); ?> <a href="<?php echo get_edit_post_link();?>" class="edit">Edit</a></h1> 
		<?php endif; ?>
		<p class="freshness"><a href="<?php echo get_permalink( $post->ID ); ?>revisions/" title="<?php esc_attr_e( date( get_option('date_format') . ' ' . get_option('time_format'), strtotime($post->post_modified)) ) ?>" class="updated">Updated <?php echo dokumento_human_time_diff( 1, strtotime($post->post_modified) ); ?> ago</a> by <a href="" class="author"><?php the_modified_author(); ?></a></p>
					
		<div id="content" role="main">		
			<?php the_content(); ?>
			
			<div class="meta">
				<?php echo dokumento_tags(); ?>
			</div>
		</div>
	<?php endwhile; else: ?>
	
	<?php endif; ?>


<?php get_footer(); ?>