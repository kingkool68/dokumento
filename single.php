<?php get_header(); ?>
<div id="content">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <h1><?php the_title(); ?></h1>
  <?php the_content(); ?>
  <div class="meta">
  <p>Updated <?= human_time_diff( strtotime($post->post_modified_gmt) ); ?> ago by <?php the_modified_author(); ?>.</p>
  </div>
  <?php endwhile; else: ?>
  <?php endif; ?>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>