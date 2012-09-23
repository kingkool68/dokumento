<?php get_header(); ?>
<div id="content" role="main">
	<h1><?php bloginfo('name'); ?></h1>
	<?php if( $tagline = get_bloginfo('description') ): ?>
		<p class="tagline"><?php echo $tagline; ?></p>
	<?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>