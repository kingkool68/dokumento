<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1 class="page-title">Revisions for <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<div id="content" role="main">
			<?php
			$revisions = get_revisions();
			if( count($revisions) > 1 ):
				
				$latest_revision = array_shift( $revisions );
				$relative_date = dokumento_human_time_diff(2, strtotime( $latest_revision->post_date ) );
				$display_name = get_dokumento_user( $latest_revision->post_author, 'display_name' );
				$revision_changelogs = get_post_meta( $post->ID, 'revision_changelog', true );
				
				$changelog = '';
				if( isset( $revision_changelogs[ $latest_revision->ID ] ) ) {
					$changelog = '<em>' . $revision_changelogs[ $latest_revision->ID ] . '</em>';
				}
				
			?>
				<p>Current Revision: <?php echo $relative_date; ?> ago - by <?php echo $display_name; ?> <?php echo $changelog; ?></p>
				<ol>
					<?php foreach( $revisions as $rev ):
						$compare_url = get_admin_url() . 'revision.php?from=' . $rev->ID . '&to=' . $latest_revision->ID;
						$absolute_date = date('M. j, Y g:i a', strtotime( $rev->post_date ));
						$relative_date = dokumento_human_time_diff(2, strtotime( $rev->post_date ) );
						$display_name = get_dokumento_user( $rev->post_author, 'display_name' );
						
						$changelog = '';
						if( isset( $revision_changelogs[ $rev->ID ] ) ) {
							$changelog = $changelog = '<em>' . $revision_changelogs[ $rev->ID ] . '</em>';
						}
					?>
					<li><a href="<?php echo $compare_url; ?>" title="Compare current version to the version on <?php echo $absolute_date;?>"><?php echo $relative_date; ?> ago - by <?php echo $display_name; ?></a> <?php echo $changelog; ?></li>
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