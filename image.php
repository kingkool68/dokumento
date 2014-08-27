<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1 class="page-title"><?php the_title(); ?> <a href="<?php echo dokumento_get_edit_post_link();?>" class="edit" title="Edit"><i class="icon-pencil"></i><span class="hidden">Edit</span></a></h1> 
		<p class="freshness"><a href="<?php echo get_permalink( $post->ID ); ?>revisions/" title="<?php esc_attr_e( date( get_option('date_format') . ' ' . get_option('time_format'), strtotime($post->post_modified)) ) ?>" class="updated">Updated <?php echo dokumento_human_time_diff( 1, strtotime($post->post_modified) ); ?> ago</a> by <a href="" class="author"><?php the_modified_author(); ?></a></p>
		
		<div id="content" role="main">
			<?php
				$src = wp_get_attachment_image_src( $post->ID, 'large' );
				$alt = get_post_meta($post->ID, '_wp_attachment_image_alt', true);
				if( !$alt ) {
					$alt = $post->post_title;
				}
			?>
			
			<img src="<?php echo $src[0]; ?>" alt="<?php esc_attr_e($alt); ?>" width="<?php echo $src[1];?>" height="<?php echo $src[2];?>">
			
			<?php the_content(); ?>
			
		</div>
	<?php endwhile; else: ?>
	
	<?php endif; ?>

<?php get_sidebar(); ?>


<?php get_footer(); ?>