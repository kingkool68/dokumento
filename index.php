<?php get_header(); ?>
<div id="content">
	<h1>Pew Research Documentation</h1>
	<p>Writing Stuff Down!</p>
	<div id="code">
		<h2>Code</h2>
		<div class="recent-posts">
			<h3>Recently modified Code articles</h3>
			<ul>
				<?php
				$args = array(
					'post_type' => 'code',
					'orderby' => 'modified',
					'order' => 'desc',
					'number' => 5
				);
				$posts = get_posts($args);
				foreach( $posts as $post ) {
					setup_postdata($post);
				?>
					<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php } ?>
			</ul>
		</div>
		<div class="recent-tags">
			<h3>Popular Code tags</h3>
			<ul>
				<?php
				$args = array(
					'orderby' => 'count',
					'order' => 'desc',
					'number' => 5
				);
				$terms = get_terms( 'post_tag', $args);
				foreach( $terms as $term ) {
				?>
					<li><a href="<?php get_term_link( $term ); ?>"><?= $term->name; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>