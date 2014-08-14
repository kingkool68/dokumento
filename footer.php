<?php //Closes div.holder in header.php ?>
</div>

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