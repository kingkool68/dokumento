<?php
$revisions = get_revisions();
$contributors = array();
foreach( $revisions as $rev ) {
	$id = $rev->post_author;
	if( !isset( $contributors[ $id ] ) ) {
		$contributors[ $id ] = 0;
	}
	
	$contributors[ $id ]++;
}

if( count( $contributors ) > 1 ) {
	?>
	<div id="contributors">
		<h2>Contributors</h2>
		<ul>
		<?php foreach( $contributors as $id => $count ): ?>
			<li><?php echo get_dokumento_user($id, 'display_name'); ?> (<?php echo $count; ?>)</li>
		<?php endforeach; ?>
		</ul>
	</div>
	<?php
}
?>