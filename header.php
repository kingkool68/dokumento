<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
<title>
<?php if ( is_tag() ) {
			echo 'Tag Archive for &quot;'.$tag.'&quot; | '; bloginfo( 'name' );
		} elseif ( is_archive() ) {
			wp_title(); echo ' Archive | '; bloginfo( 'name' );
		} elseif ( is_search() ) {
			echo 'Search for &quot;'.wp_specialchars($s).'&quot; | '; bloginfo( 'name' );
		} elseif ( is_home() ) {
			bloginfo( 'name' ); echo ' | '; bloginfo( 'description' );
		}  elseif ( is_404() ) {
			echo 'Error 404 Not Found | '; bloginfo( 'name' );
		} else {
			echo wp_title( ' | ', false, right ); bloginfo( 'name' );
		} ?>
</title>
<meta charset="<?php bloginfo( 'charset' ); ?>" >
<meta name="viewport" content="width=device-width">
<link rel="index" title="<?php bloginfo( 'name' ); ?>" href="<?php echo get_option('home'); ?>/" >
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<a id="top" href="#content">Skip to Content</a>
<div id="scrollbar-catcher">
<header>
	<div class="holder">
		<h1 class="site-title"><a href="<?php echo get_site_url(); ?>"><?php bloginfo('name'); ?></a></h1>
		<?php if( $tagline = get_bloginfo('description') ): ?>
			<p class="tagline"><?php echo $tagline; ?></p>
		<?php endif; ?>
		
		<a href="#nav" id="menu"><i class="icon-menu"></i><span>Menu</span></a>
	</div>
</header>



<div class="wrapper">
	<div class="holder clearfix">
		<form action="<?php echo get_site_url();?>" class="search">
			<label for="s" class="hidden">Search</label>
			<input type="search" name="s" id="s" value="<?php echo the_search_query();?>" placeholder="Search">
			<!--input type="submit" value="Search"-->
		</form>