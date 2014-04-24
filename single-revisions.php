<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1 class="page-title">Revisions for <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<div id="content" role="main">
			
		</div>
	<?php endwhile; else: ?>
	
	<?php endif; ?>

<?php get_sidebar(); ?>


<?php get_footer(); ?>