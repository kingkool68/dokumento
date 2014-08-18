<?php
//Pretty much identicla to the edit_term_link() function in core except I wanted to add a class to the HTML. 
function dokumento_edit_term_link( $link = '', $before = '', $after = '', $echo = true ) {
	$term = get_queried_object();

	if ( ! $term ) {
		return;
	}

	$tax = get_taxonomy( $term->taxonomy );
	if ( ! current_user_can( $tax->cap->edit_terms ) ) {
		return;
	}

	if ( empty( $link ) ) {
		$link = 'Edit';
	}

	$link = '<a href="' . get_edit_term_link( $term->term_id, $term->taxonomy ) . '" class="edit" title="Edit"><i class="icon-pencil"></i> <span class="hidden">' . $link . '</span></a>';

	/**
	 * Filter the anchor tag for the edit link of a term.
	 *
	 * @since 3.1.0
	 *
	 * @param string $link    The anchor tag for the edit link.
	 * @param int    $term_id Term ID.
	 */
	$link = $before . apply_filters( 'edit_term_link', $link, $term->term_id ) . $after;

	if ( $echo )
		echo $link;
	else
		return $link;
}

get_header();
?>

<div id="content" role="main">
	<h1 class="page-title"><?php single_term_title();?> <?php dokumento_edit_term_link('Edit'); ?></h1>
	<p class="freshness"></p>
	<?php if( $term_description = term_description() ) : ?>
		<div class="description">
			<?php echo $term_description; ?>
		</div>
	<?php endif; ?>
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<?php endwhile; else: ?>
	
	<?php endif; ?>
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>