<?php
//Register CSS
function register_dokumento_styles() {
	wp_enqueue_script( 'jquery' );
	
	wp_register_style( 'merriweather', 'http://fonts.googleapis.com/css?family=Merriweather:400,700,400italic,300,300italic,700italic,900,900italic', false, NULL, 'all' );
	wp_register_style( 'reset', get_template_directory_uri() . '/css/reset.css', false, NULL, 'all' );
	wp_register_style( 'dokumento', get_template_directory_uri() . '/css/dokumento.css', array('reset', 'merriweather'), NULL, 'all' );
	
	wp_enqueue_style( 'dokumento' );
}
add_action( 'wp_enqueue_scripts', 'register_dokumento_styles' );


function print_html5_shiv() {
	//This need to come after any CSS files are loaded in the <head> in order to work properly. See https://github.com/aFarkas/html5shiv
?>
<!--[if lt IE 9]><script src="<? echo get_template_directory_uri();?>/js/html5shiv-printshiv.min.js"></script><![endif]-->
<?php
}
add_action( 'wp_head', 'print_html5_shiv' );