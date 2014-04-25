<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1 class="page-title">Backlinks for <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<div id="content" role="main">
			<?php
			$backlinks = get_backlinks();
			if( $backlinks ):
			?>
				<p>Pages that link to <em><?php the_title(); ?></em>:</p>
				<ul>
					<?php foreach( $backlinks as $backlink ): ?>
					<li><a href="<?php echo get_permalink( $backlink->ID ); ?>"><?php echo apply_filters( 'the_title', $backlink->post_title ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php else: ?>
				<h1>No backlinks.</h1> 
			<?php endif; ?>
		</div>
	<?php endwhile; else: ?>
	
	<?php endif; ?>

<?php get_sidebar(); ?>


<?php get_footer(); ?>