<?php get_header(); ?>

<div id="content" role="main">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
	<?php endwhile; else: ?>
	
	<?php endif; ?>
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>