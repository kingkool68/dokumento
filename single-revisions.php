<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1 class="page-title">Revisions for <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<div id="content" role="main">
			<?php
			$revisions = get_revisions();
			if( count($revisions) > 1 ):
				$latest_revision = $revisions[ 0 ]->ID;
			?>
				<ol>
					<?php foreach( $revisions as $rev ):
						$compare_url = get_admin_url() . 'revision.php?from=' . $rev->ID . '&to=' . $latest_revision;
						$absolute_date = date('M. j, Y g:i a', strtotime( $rev->post_date ));
						$relative_date = dokumento_human_time_diff(1, strtotime( $rev->post_date ) );
						$display_name = get_dokumento_user( $rev->post_author, 'display_name' );
					?>
					<li><a href="<?php echo $compare_url; ?>" title="Compare current version to the version on <?php echo $absolute_date;?>"><?php echo $relative_date; ?> ago - by <?php echo $display_name; ?></a></li>
					<?php endforeach; ?>
				</ol>
			<?php else: ?>
				<h1>No revisions.</h1> 
			<?php endif; ?>
		</div>
	<?php endwhile; else: ?>
	
	<?php endif; ?>

<?php get_sidebar(); ?>


<?php get_footer(); ?>