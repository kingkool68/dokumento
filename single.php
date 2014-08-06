<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1 class="page-title"><?php the_title(); ?> <a href="<?php echo get_edit_post_link();?>" class="edit">Edit</a></h1>
		<div id="content" role="main">
			<?php dokumento_toc(); ?>
			
			<?php the_content(); ?>
			
			<div class="meta">
				<p><span title="<?php esc_attr_e( date( get_option('date_format') . ' ' . get_option('time_format'), strtotime($post->post_modified)) ) ?>" class="updated">Updated <?php echo dokumento_human_time_diff( 2, strtotime($post->post_modified) ); ?> ago</span> by <span class="author"><?php the_modified_author(); ?></span>
				<?php
				$revs = get_revisions();
				if( $revs && count( $revs ) > 1 ): 
					$revisions_text = number_format( count( $revs ) - 1 ) . ' revisions';			
					$revisions_url = get_permalink( $post->ID ) . 'revisions/';
				?>
				&bull; <a href="<?php echo $revisions_url;?>" title="View <?php echo $revisions_text; ?> for <?php esc_attr_e( $post->post_title );?>"><?php echo $revisions_text; ?></a>
				<?php endif; ?>
				</p>
				<?php echo dokumento_tags(); ?>
			</div>
		</div>
	<?php endwhile; else: ?>
	
	<?php endif; ?>


<?php get_footer(); ?>