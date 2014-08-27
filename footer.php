	<?php //Closes div.holder in header.php ?>
	</div>
	
<?php //Closes div.wrapper in header.php ?>
</div>

</div>

<nav id="nav">
	<section>
		<h2>This Post</h2>
		<ul>
			<li><a href="#tags">Tags</a></li>
			<li><a href="<?php echo get_permalink(); ?>revisions/">History</a></li>
			<li><a href="<?php echo get_permalink(); ?>backlinks/">What links here?</a></li>
		</ul>
	</section>
	<section>
		<h2>Nav</h2>
		<?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
	</section>
	<section>
		<h2></h2>
		<ul>
			<li>
			<?php if( is_user_logged_in() ) { ?>
				<a href="<?php echo wp_logout_url( get_site_url() ); ?>">Log Out</a>
			<?php } else { ?>
				
		<?php }
		?>
		</li>
	</section>
</nav>

<script>
jQuery(document).ready(function($) {
	$('body').addClass('js').find('form.search').before('<div id="magic-shield" />');
	$('#menu').click(function(e) {
		e.preventDefault();
		$('body').toggleClass('nav-open');
	});
	$('#magic-shield').on('click', function(e) {
		$('body').toggleClass('nav-open');
	});
});
</script>

<?php wp_footer(); ?>
</body></html>