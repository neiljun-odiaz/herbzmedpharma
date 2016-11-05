<?php

add_action( 'admin_init', 'rf_product_page_enqueue' );
add_action( 'admin_menu', 'rf_product_page_creator_page' );

function rf_product_page_enqueue() {
   /* Register our stylesheet. */
  wp_register_style( 'controls', get_template_directory_uri() . '/framework/css/controls.css' );
  wp_register_style( 'product_builder', get_template_directory_uri() . '/framework/css/product_builder.css' );
	wp_register_script( 'rf-controls', get_template_directory_uri() . '/framework/js/rf_controls.js', array( 'jquery' ) );
	wp_register_script( 'rf-page-builder', get_template_directory_uri() . '/framework/js/rf_page_builder.js', array( 'jquery' ) );

}

function rf_product_page_creator_page() {
   /* Register our plugin page */
	$page = add_submenu_page( 'edit.php?post_type=rf_product', 'Product Page Builder', 'Product Page Builder', 'manage_options', 'rf-profuct-page-bulder', 'rf_product_page_bulder' ); 

   /* Using registered $page handle to hook stylesheet loading */
   add_action( 'admin_print_styles-' . $page, 'rf_page_creator_styles' );
}

function rf_page_creator_styles() {
   /*
    * It will be called only on your plugin admin page, enqueue our stylesheet here
    */
   wp_enqueue_style( 'controls' );
    wp_enqueue_style( 'product_builder' );
 		wp_enqueue_script( 'jquery' );
 		wp_enqueue_script( 'jquery-ui-core' );
 		wp_enqueue_script( 'jquery-ui-mouse' );
 		wp_enqueue_script( 'jquery-ui-widget' );
 		wp_enqueue_script( 'jquery-ui-draggable' );
 		wp_enqueue_script( 'jquery-ui-droppable' );
 		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'rf-page-builder' );

}

function rf_product_page_bulder() {
	include( 'product_builder_page.php' );
}



add_action('wp_ajax_rf_save_product_build', 'rf_save_product_build');
function rf_save_product_build() {
	
	echo stripslashes($_POST['positions']);
	

	
	$positions = json_decode( stripslashes($_POST['positions']), true );


	update_option( 'rf_product_page', $positions );
	
	die();
	
}



?>