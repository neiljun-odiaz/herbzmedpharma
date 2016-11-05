<?php

/****************************************************************/
/*                     Definitions And Setup                    */
/****************************************************************/

define( 'THEMENAME', 'thebeautysalon' );
define( 'RF_PRIMARY_COLOR', '#FF4141');

if ( ! isset( $content_width ) ) $content_width = 1140;

add_theme_support( 'automatic-feed-links' );
add_theme_support( 'post-thumbnails' );

add_image_size( 'rf_tiny_thumb', 180, 180, true );
add_image_size( 'rf_large_thumb', 575, 575, true );
add_image_size( 'rf_medium_thumb', 384, 384, true );
add_image_size( 'rf_small_thumb', 290, 290, true );



$theme_options = get_option( 'rf_options_' . THEMENAME );


/****************************************************************/
/*                      Sidebars And Menus                      */
/****************************************************************/
/* 
	The actual registration of these elements happens in the 
	functions file in the framework. Only the arrays are given here
	for convenience.
*/



$register_sidebars = array( 
	array( 
		'name'          => 'Sidebar',
		'id'            => 'sidebar',
		'before_widget' => '<div class="widget sbwidget">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="widget-title"><h1 class="title-text">',
		'after_title'   => '</h1><div class="title-bg"></div><div class="clear"></div></div>',
 	),
);
 	
$register_menus = array( 
	'main-menu' => 'Main menu',
);


include( 'framework/functions.php' );


?>