<?php
function dokumento_get_image_sizes( $post_id = FALSE ) {
	global $post;
	
	$imgs = array();
	
	if( !$post_id ) {
		$post_id = $post->ID;
	}
	
	if( !$post->ID ) {
		return $imgs;
	}
	
	if( !wp_attachment_is_image($post_id) ) {
		return $imgs;
	}
	$sizes = get_intermediate_image_sizes();

	foreach( $sizes as $size ):
		$vals = wp_get_attachment_image_src( $post_id, $size);
		//$vals[3] is a boolean indicating if it has an image for the given size. If it's false, the img src is the original sized image. 
		if( $vals[3] ) {
			$vals[3] = $size;
			$area = $vals[1] * $vals[2]; //Used for sorting the images by size...
			$imgs[$area] = $vals;
		}
	endforeach;

	//Add the original image size into the mix.
	$orig = wp_get_attachment_image_src( $post_id, 'full' );
	$orig[3] = 'original';
	$area = $orig[1] * $orig[2];
	$imgs[$area] = $orig;
	
	ksort($imgs);
	
	$output = array();
	foreach( $imgs as $img ) {
		$src = $img[0];
		$width = $img[1];
		$height = $img[2];
		$label = $img[3];
		
		$output[] = (object) array(
			'src' => $src,
			'width' => $width,
			'height' => $height,
			'label' => $label
		);
	}
	
	return $output;
}

$imgs = dokumento_get_image_sizes();

if( $imgs ):
	?>
	<h2>Image Sizes</h2>
	<ul>
		<?php foreach( $imgs as $img ): ?>
		<li><a href="<?php echo $img->src ;?>" title="Download the <?php esc_attr_e( $img->label ); ?> size image"><?php echo $img->width . ' x ' . $img->height; ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php
endif;