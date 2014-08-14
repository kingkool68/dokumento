<?php //Closes div.holder in header.php ?>
</div>

<?php wp_enqueue_script( 'jquery' ); ?>
<script>
jQuery(document).ready(function($) {
	$('body').addClass('js');
	$('#menu').click(function(e) {
		$('body').toggleClass('nav-open');
	});
});
</script>

<?php wp_footer(); ?>
</body></html>