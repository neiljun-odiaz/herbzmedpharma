<?php
global $custom_posts_types;

if( isset( $custom_posts_types ) AND in_array( 'rf_slide', $custom_posts_types ) ) {

	add_action( 'init', 'rf_customposts_slide' );
	add_filter( 'post_updated_messages', 'rf_customposts_slide_messages' );
	
	function rf_customposts_slide() {
		$labels = array(
			'name'                => 'Slider',
			'singular_name'       => 'Slide',
			'add_new'             => 'Add New',
			'add_new_item'        => 'Add New Slide',
			'edit_item'           => 'Edit Slide',
			'new_item'            => 'New Slide',
			'all_items'           => 'All Slides',
			'view_item'           => 'View Slide',
			'search_items'        => 'Search Slides',
			'not_found'           => 'No slides found',
			'not_found_in_trash'  => 'No slides found in trash', 
			'parent_item_colon'   => '',
			'menu_name'           => 'Slides'
		);
	
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true, 
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		); 
	
		register_post_type( 'rf_slide', $args );
	}
	
	
	function rf_customposts_slide_messages( $messages ) {
		global $post, $post_ID;
		
		$messages['rf_slide'] = array(
			0  => '',
			1  => sprintf( 'Book updated. <a href="%s">View slide</a>', esc_url( get_permalink($post_ID) ) ),
			2  => 'Custom field updated.',
			3  => 'Custom field updated.',
			4  => 'Slide updated.',
			5  => isset($_GET['revision']) ? sprintf( 'Slide restored to revision from %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( 'Slide published. <a href="%s">View slide</a>', esc_url( get_permalink($post_ID) ) ),
			7  => 'Slide saved.',
			8  => sprintf( 'Slide submitted. <a target="_blank" href="%s">Preview slide</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9  => sprintf( 'Slide scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview slide</a>',
		date_i18n( __( 'M j, Y @ G:i', 'rf_themes' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( 'Slide draft updated. <a target="_blank" href="%s">Preview slide</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);
		
		return $messages;
	}

}


	add_action( 'init', 'rf_customposts_product' );
	add_filter( 'post_updated_messages', 'rf_customposts_product_messages' );
	
	function rf_customposts_product() {
		$labels = array(
			'name'                => 'Products',
			'singular_name'       => 'Product',
			'add_new'             => 'Add New',
			'add_new_item'        => 'Add New Product',
			'edit_item'           => 'Edit Product',
			'new_item'            => 'New Product',
			'all_items'           => 'All Products',
			'view_item'           => 'View Product',
			'search_items'        => 'Search Product',
			'not_found'           => 'No products found',
			'not_found_in_trash'  => 'No products found in trash', 
			'parent_item_colon'   => '',
			'menu_name'           => 'Products'
		);
	
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true, 
			'show_in_menu' => true, 
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'has_archive' => true, 
			'hierarchical' => false,
			'menu_position' => null,
			'taxonomies' => array('rf_product_category'),
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		); 
	
		register_post_type( 'rf_product', $args );
	}
	
	
	function rf_customposts_product_messages( $messages ) {
		global $post, $post_ID;
		
		$messages['rf_product'] = array(
			0  => '',
			1  => sprintf( 'Product updated. <a href="%s">View product</a>', esc_url( get_permalink($post_ID) ) ),
			2  => 'Custom field updated.',
			3  => 'Custom field updated.',
			4  => 'Product updated.',
			5  => isset($_GET['revision']) ? sprintf( 'Product restored to revision from %s', wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => sprintf( 'Product published. <a href="%s">View product</a>', esc_url( get_permalink($post_ID) ) ),
			7  => 'Product saved.',
			8  => sprintf( 'Product submitted. <a target="_blank" href="%s">Preview product</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
			9  => sprintf( 'Product scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview product</a>',
		date_i18n( __( 'M j, Y @ G:i', 'rf_themes' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
			10 => sprintf( 'Product draft updated. <a target="_blank" href="%s">Preview product</a>', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		);
		
		return $messages;
	}




//hook into the init action and call create_book_taxonomies when it fires
add_action( 'init', 'rf_product_taconomy', 0 );

//create two taxonomies, genres and writers for the post type "book"
function rf_product_taconomy() 
{
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name' => _x( 'Product Categories', 'taxonomy general name', 'rf_themes' ),
    'singular_name' => _x( 'Product Category', 'taxonomy singular name', 'rf_themes' ),
    'search_items' =>  __( 'Search Product Categories', 'rf_themes' ),
    'all_items' => __( 'All Product Categories', 'rf_themes' ),
    'parent_item' => __( 'Parent Product Category', 'rf_themes' ),
    'parent_item_colon' => __( 'Parent Product Category:', 'rf_themes' ),
    'edit_item' => __( 'Edit Product Category', 'rf_themes' ), 
    'update_item' => __( 'Update Product Category', 'rf_themes' ),
    'add_new_item' => __( 'Add New Product Category', 'rf_themes' ),
    'new_item_name' => __( 'New Product Category Name', 'rf_themes' ),
    'menu_name' => __( 'Product Categories', 'rf_themes' ),
  ); 	

  register_taxonomy('rf_product_category',array('rf_product'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'rf_product_category' ),
  ));

}





add_action( 'add_meta_boxes', 'rf_product_boxes' );
add_action( 'save_post', 'rf_product_boxes_save' );

/* Adds a box to the main column on the Post and Page edit screens */
function rf_product_boxes() {
    add_meta_box( 
        'rf_product_price',
        'Product Price',
        'rf_product_price_box',
        'rf_product',
        'side',
        'high' 
    );
}

/* Prints the box content */
function rf_product_price_box( $post ) {

  // Use nonce for verification
  wp_nonce_field( 'rf_themes', 'rf_product_price_nonce' );
  $value = get_post_meta( $post->ID, 'rf_product_price', true );
  
  echo '<input type="text" id="rf_product_price_field" name="rf_product_price" placeholder="Enter the price of this product" value="'.$value.'" size="25" />';
}

/* When the post is saved, saves our custom data */
function rf_product_boxes_save( $post_id ) {
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['rf_product_price_nonce'], 'rf_themes' ) )
      return;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }

  update_post_meta( $post_id, 'rf_product_price', $_POST['rf_product_price']);

}




?>