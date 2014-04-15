<?php //Closes div.holder in header.php ?>
</div>

<footer>
	<div class="holder">
		<div class="widget">
			<h2>Recent Updates</h2>
			<ul>
			<?php
				$args = array(
					'orderby' => 'modified'
				);
				$recent_edits = get_posts( $args );
				
				foreach( $recent_edits as $recent ) {
				?>
				<li><a href="<?php echo get_permalink( $recent->ID );?>"><?php echo $recent->post_title;?></a></li>
				<?php
				}
			?>
			</ul>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body></html>