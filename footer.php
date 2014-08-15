	<?php //Closes div.holder in header.php ?>
	</div>
	
<?php //Closes div.wrapper in header.php ?>
</div>

</div>

<nav id="nav">
	<section>
	<h2>Nav</h2>
	<?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
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