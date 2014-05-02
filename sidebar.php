<?php
$file = '';
if( is_attachment() ) {
	$file = 'attachment';
} else if( is_single() ) {
	$file = 'single';
} 

if( !$file ) {
	return; //This works because it's an include... maybe?
}

$folder = 'sidebars/sidebar'
?>

<div id="sidebar" class="sidebar" role="complementary">
	<?php get_template_part( $folder, $file ); ?>
</div>