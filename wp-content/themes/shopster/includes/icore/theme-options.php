<?php


function get_revolution_sliders() {
	
	// Get Revolution sliders
	global $wpdb;
	$get_sliders = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'revslider_sliders');
	$revsliders['none']['name'] = 'Select a slider';
	$revsliders['none']['value'] = '--none--';
	if($get_sliders) {
		foreach($get_sliders as $slider) {
			$revsliders[$slider->alias]['name'] = $slider->title;
			$revsliders[$slider->alias]['value'] = $slider->title;
		}
	}
	return $revsliders;
}

function get_layer_sliders() {
 
    // Get WPDB Object
    global $wpdb;

    // Table name
    $table_name = $wpdb->prefix . "layerslider";
 
    // Get sliders
    $sliders = $wpdb->get_results( "SELECT * FROM $table_name WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY date_c ASC LIMIT 100" );
 	
	$layersliders['none']['name'] = 'Select a slider';
	$layersliders['none']['value'] = '--none--';	
	if( $sliders ) {
    	// Iterate over the sliders
    	foreach( $sliders as $slider) {

			$layersliders[$slider->id]['name'] = $slider->name;
			$layersliders[$slider->id]['value'] = $slider->id;
    	}
	}

	return $layersliders;
}


/*-----------------------------------------------------------------------------------*/
/* Register Theme Options */
/*-----------------------------------------------------------------------------------*/
global $theme_options;

$this->sections['general']      = __( 'General', 'icore' );
$this->sections['appearance']   = __( 'Appearance', 'icore' );
$this->sections['colors']   = __( 'Colors', 'icore' );
$this->sections['thumbnails']   = __( 'Thumbnails', 'icore' );
$this->sections['homepage']   = __( 'Homepage', 'icore' );
$this->sections['slider']   = __( 'Homepage Slider', 'icore' );

/* Define Theme Options */

/* General Settings
===========================================*/


$this->settings['favicon'] = array(
    'title'   => __( 'Custom Favicon ', 'icore'),
    'desc'    => __( 'Upload favicon image here.', 'icore'),
    'std'     => '',
    'type'    => 'upload',
    'section' => 'general'
);

$this->settings['logo'] = array(
    'title'   => __( 'Logo Image', 'icore' ),
    'desc'    => __( 'Upload logo image', 'icore' ),
    'std'     => '',
    'type'    => 'upload',
    'section' => 'general'
);

$this->settings['font_headline'] = array(
	'section' => 'general',
	'title'   => __( 'Headlines Font', 'icore' ),
	'desc'    => __( 'Select headlines font', 'icore' ),
	'type'    => 'select',
	'std'     => 'Open Sans:400italic,400,300,700,800',
	'choices' => icore_google_fonts_choices()
);

$this->settings['font_body'] = array(
	'section' => 'general',
	'title'   => __( 'Body Font', 'icore' ),
	'desc'    => __( 'Select body text font', 'icore' ),
	'type'    => 'select',
	'std'     => 'Arvo:400,700',
	'choices' => icore_google_fonts_choices()
);

$this->settings['google_analytics'] = array(
    'title'   => __( 'Google Analytics', 'icore' ),
    'desc'    => __( 'Paste your <a href="http://www.google.com/analytics/" rel="nofollow" target="_blank" >Google Analytics</a> code above.', 'icore' ),
    'std'     => '',
    'type'    => 'textarea',
    'section' => 'general'
);

	

/* Appearance
===========================================*/


$this->settings['catalog'] = array(
	'section' => 'appearance',
	'title'   => __( 'Catalog Mode', 'icore' ),
	'desc'    => __( 'Disable e-commerce. Show products in catalog mode.', 'icore' ),
	'type'    => 'select',
	'std'     => 'no',
	'choices' => array(
		array(
			'name' => 'yes',
			'value' => 'yes'
		),
		array(
			'name' => 'no',
			'value' => 'no'
		)
	)
);

$this->settings['secondary_color'] = array(
	'section' => 'colors',
	'title'   => __( 'Theme Accent Color<br />default: #2abbf2', 'icore' ),
	'desc'    => __( 'select accent color ( buttons, borders, etc ) ( default: #2abbf2 )', 'icore' ),
	'type'    => 'color',
	'std'     => '#2abbf2'
);


$this->settings['sidebar'] = array(
	'section' => 'appearance',
	'title'   => __( 'Sidebar Position', 'icore' ),
	'desc'    => __( 'Select sidebar position', 'icore' ),
	'type'    => 'select',
	'std'     => 'left',
	'choices' => array(
		array(
			'name' => 'right',
			'value' => 'right'
		),
		array(
			'name' => 'left',
			'value' => 'left'
		)
	)
);

$this->settings['portfolio_layout'] = array(
	'section' => 'appearance',
	'title'   => __( 'Portfolio Layout', 'icore' ),
	'desc'    => __( 'Select layout for portfolio archive pages', 'icore' ),
	'type'    => 'select',
	'std'     => 'two',
	'choices' => array(
	array(
		'name' => 'one column',
		'value' => 'one'
	),
	array(
		'name' => 'two column',
		'value' => 'two'
	),
	array(
		'name' => 'three column',
		'value' => 'three'
	),
	array(
		'name' => 'four column',
		'value' => 'four'
	),
	)
);

$this->settings['search'] = array(
	'section' => 'appearance',
	'title'   => __( 'Search Bar', 'icore' ),
	'desc'    => __( 'display header search bar', 'icore' ),
	'type'    => 'checkbox',
	'std'     => '1'
);

$this->settings['blog_style'] = array(
	'section' => 'appearance',
	'title'   => __( 'Full Post Content', 'icore' ),
	'desc'    => __( 'show full post content instead of excerpt', 'icore' ),
	'type'    => 'checkbox',
	'std'     => ''
);



$this->settings['payment_visa'] = array(
	'section' => 'appearance',
	'title'   => __( 'We accept Visa', 'icore' ),
	'desc'    => __( 'check to show icon under "We Accept" footer area', 'icore' ),
	'type'    => 'checkbox',
	'std'     => '1'
);

$this->settings['payment_mastercard'] = array(
	'section' => 'appearance',
	'title'   => __( 'We accept Mastercard', 'icore' ),
	'desc'    => __( 'check to show icon under "We Accept" footer area', 'icore' ),
	'type'    => 'checkbox',
	'std'     => '1'
);

$this->settings['payment_amex'] = array(
	'section' => 'appearance',
	'title'   => __( 'We accept American Express', 'icore' ),
	'desc'    => __( 'check to show icon under "We Accept" footer area', 'icore' ),
	'type'    => 'checkbox',
	'std'     => '1'
);

$this->settings['payment_paypal'] = array(
	'section' => 'appearance',
	'title'   => __( 'We accept Paypal', 'icore' ),
	'desc'    => __( 'check to show icon under "We Accept" footer area', 'icore' ),
	'type'    => 'checkbox',
	'std'     => '1'
);

$this->settings['payment_checks'] = array(
	'section' => 'appearance',
	'title'   => __( 'We accept Checks', 'icore' ),
	'desc'    => __( 'check to show icon under "We Accept" footer area', 'icore' ),
	'type'    => 'checkbox',
	'std'     => ''
);

$this->settings['custom_css'] = array(
	'title'   => __( 'Custom CSS', 'icore' ),
	'desc'    => __( 'Enter your custom CSS code here.', 'icore' ),
	'std'     => '',
	'type'    => 'textarea',
	'section' => 'appearance',
	'class'   => 'code'
);


$this->settings['shop_sidebar'] = array(
	'section' => 'appearance',
	'title'   => __( 'Shop Sidebar', 'icore' ),
	'desc'    => __( 'display or hide side bar on shop page', 'icore' ),
	'type'    => 'select',
	'std'     => 'show',
	'choices' => array(
	array(
		'name' => 'show',
		'value' => 'show'
	),
	array(
		'name' => 'hide',
		'value' => 'hide'
	)
	)
);

$this->settings['product_sidebar'] = array(
	'section' => 'appearance',
	'title'   => __( 'Single Product Sidebar', 'icore' ),
	'desc'    => __( 'display or hide side bar on product page', 'icore' ),
	'type'    => 'select',
	'std'     => 'show',
	'choices' => array(
	array(
		'name' => 'show',
		'value' => 'show'
	),
	array(
		'name' => 'hide',
		'value' => 'hide'
	)
	)
);

/* Homepage
===========================================*/

$this->settings['call_to_action_enabled'] = array(
			'section' => 'homepage',
			'title'   => __( 'Homepage message', 'icore' ),
			'desc'    => __( 'Display Homepage Message', 'icore'),
			'std'     => '1',
			'type'    => 'checkbox'
		);
		
$this->settings['call_to_action_one'] = array(
			'section' => 'homepage',
			'title'   => __( 'Homepage message line one', 'icore' ),
			'desc'    => __( 'Enter text for line one', 'icore'),
			'std'     => 'Free Shipping on all orders over $199',
			'type'    => 'text',
			'class' => ' pid call_to_action_enabled'
		);
		
$this->settings['call_to_action_two'] = array(
			'section' => 'homepage',
			'title'   => __( 'Homepage message line two', 'icore' ),
			'desc'    => __( 'Enter text for line two', 'icore'),
			'std'     => 'You can change this text by going to Appearance -> Theme Options -> Homepage',
			'type'    => 'text',
			'class' => ' pid call_to_action_enabled'
		);

$this->settings['homepage_team'] = array(
			'section' => 'homepage',
			'title'   => __( 'Team', 'icore' ),
			'desc'    => __( 'Display team on homepage', 'icore'),
			'std'     => '0',
			'type'    => 'checkbox'
		);
		
$this->settings['homepage_portfolio'] = array(
			'section' => 'homepage',
			'title'   => __( 'Portfolio', 'icore' ),
			'desc'    => __( 'Display portfolio on homepage', 'icore'),
			'std'     => '0',
			'type'    => 'checkbox'
		);

$this->settings['homepage_portfolio_number'] = array(
			'section' => 'homepage',
			'title'   => __( 'Portfolio items', 'icore' ),
			'desc'    => __( 'Select number of items to show in portoflio', 'icore'),
			'std'     => '6',
			'type'    => 'text',
			'class' => ' pid homepage_portfolio'
		);

$this->settings['homepage_page'] = array(
	'section' => 'homepage',
	'title'   => __( 'Homepage content page', 'icore' ),
	'desc'    => __( 'Display selected page content on the homepage', 'icore' ),
	'type'    => 'select',
	'std'     => 'none',
	'choices' => $this->getPages(),
);

$this->settings['homepage_page_2'] = array(
	'section' => 'homepage',
	'title'   => __( 'Homepage content page 2', 'icore' ),
	'desc'    => __( 'Display selected page content on the homepage', 'icore' ),
	'type'    => 'select',
	'std'     => 'none',
	'choices' => $this->getPages(),
);

$this->settings['home_products_featured'] = array(
			'section' => 'homepage',
			'title'   => __( 'Featured Products', 'icore' ),
			'desc'    => __( 'Display featured products on homepage', 'icore'),
			'std'     => '1',
			'type'    => 'checkbox'
		);
		
$this->settings['home_featured_number'] = array(
			'section' => 'homepage',
			'title'   => __( 'Number of Featured Products', 'icore' ),
			'desc'    => __( 'Select number of featured products to show on homepage', 'icore'),
			'std'     => '8',
			'type'    => 'text',
			'class' => ' pid home_products_featured'
		);
			
					
$this->settings['home_products_recent'] = array(
			'section' => 'homepage',
			'title'   => __( 'Recent Products', 'icore' ),
			'desc'    => __( 'Display recent products on homepage', 'icore'),
			'std'     => '1',
			'type'    => 'checkbox'
		);

$this->settings['home_recent_number'] = array(
			'section' => 'homepage',
			'title'   => __( 'Number of Recent Products', 'icore' ),
			'desc'    => __( 'Select number of featured products to show on homepage', 'icore'),
			'std'     => '4',
			'type'    => 'text',
			'class' => ' pid home_products_recent'
		);

			
/* Thumbnails
===========================================*/

$this->settings['front-page_thumb'] = array(
	'section' => 'thumbnails',
	'title'   => __( 'Front page thumbnails', 'icore'),
	'desc'    => __( 'show thumbnails on Front page', 'icore' ),
	'type'    => 'checkbox',
	'std'     => '1'
);

$this->settings['category_thumb'] = array(
	'section' => 'thumbnails',
	'title'   => __( 'Category page thumbnails', 'icore' ),
	'desc'    => __( 'show thumbnails on Category pages', 'icore' ),
	'type'    => 'checkbox',
	'std'     => '1'
);

$this->settings['author_thumb'] = array(
	'section' => 'thumbnails',
	'title'   => __( 'Author page thumbnails', 'icore' ),
	'desc'    => __( 'show thumbnails on Author pages', 'icore' ),
	'type'    => 'checkbox',
	'std'     => '1'
);

$this->settings['tag_thumb'] = array(
	'section' => 'thumbnails',
	'title'   => __( 'Tag page thumbnails', 'icore' ),
	'desc'    => __( 'show thumbnails on Tag pages', 'icore' ),
	'type'    => 'checkbox',
	'std'     => '1'
);

$this->settings['single_thumb'] = array(
	'section' => 'thumbnails',
	'title'   => __( 'Single post thumbnail', 'icore' ),
	'desc'    => __( 'show thumbnails on Single posts', 'icore' ),
	'type'    => 'checkbox',
	'std'     => '1'
);

$this->settings['page_thumb'] = array(
	'section' => 'thumbnails',
	'title'   => __( 'Single page thumbnail', 'icore' ),
	'desc'    => __( 'show thumbnails on Single page', 'icore' ),
	'type'    => 'checkbox',
	'std'     => '1'
);

$this->settings['search_thumb'] = array(
	'section' => 'thumbnails',
	'title'   => __( 'Search page thumbnail', 'icore' ),
	'desc'    => __( 'show thumbnails on search page', 'icore' ),
	'type'    => 'checkbox',
	'std'     => '1'
);


/* Slider
===========================================*/

$this->settings['slider_enabled'] = array(
    'section' => 'slider',
    'title'   => __( 'Homepage Slider', 'icore' ),
    'desc'    => __( 'Enable Homepage Slider', 'icore' ),
    'type'    => 'checkbox',
    'std'     => ''
);

$this->settings['slider_size'] = array(
	'section' => 'slider',
	'title'   => __( 'Slider size', 'icore' ),
	'desc'    => __( 'select slider size', 'icore' ),
	'type'    => 'select',
	'std'     => 'full',
	'class' => ' pid slider_enabled',
	'choices' => array(
	array(
		'name' => 'full width',
		'value' => 'full'
	),
	array(
		'name' => 'boxed',
		'value' => 'boxed'
	),
	)
);

$this->settings['slider_type'] = array(
	'section' => 'slider',
	'title'   => __( 'Slider Type', 'icore' ),
	'desc'    => __( 'select slider type', 'icore' ),
	'type'    => 'select',
	'std'     => 'flexslider',
	'class' => ' pid slider_enabled slider_type',
	'choices' => array(
	array(
		'name' => 'flexslider',
		'value' => 'flexslider'
	),
	array(
		'name' => 'flexslider + thumbnails',
		'value' => 'flexthumbs'
	),
	array(
		'name' => 'laptop slider',
		'value' => 'flexlaptop'
	),
	array(
		'name' => 'revolution slider',
		'value' => 'revslider'
	),
	array(
		'name' => 'layer slider',
		'value' => 'layerslider'
	),
	)
);

$this->settings['rev_slider'] = array(
	'section' => 'slider',
	'title'   => __( 'Revolution Slider', 'icore' ),
	'desc'    => __( 'Select slider', 'icore' ),
	'type'    => 'select',
	'std'     => '',
	'choices' => get_revolution_sliders(),
	'class' => ' pid revslider slider_type_select'
);

$this->settings['layer_slider'] = array(
	'section' => 'slider',
	'title'   => __( 'Layer Slider', 'icore' ),
	'desc'    => __( 'Select slider', 'icore' ),
	'type'    => 'select',
	'std'     => '',
	'choices' => get_layer_sliders(),
	'class' => ' pid layerslider slider_type_select'
);

$this->settings['slider_auto'] = array(
    'section' => 'slider',
    'title'   => __( 'Automatic Animation', 'icore' ),
    'desc'    => __( 'Animate slider automatically', 'icore' ),
    'type'    => 'checkbox',
    'std'     => '1',
	'class' => ' pid flexslider flexthumbs flexlaptop slider_type_select'
	
);

$this->settings['slider_animation'] = array(
	'section' => 'slider',
	'title'   => __( 'Slider Effect', 'icore' ),
	'desc'    => __( 'Select slider animation effect', 'icore' ),
	'type'    => 'select',
	'std'     => 'fade',
	'choices' => array(
		array(
		'name' => 'fade',
		'value' => 'fade'
	),
	array(
		'name' => 'slide',
		'value' => 'slide'
	),
	),
	'class' => ' pid flexslider flexthumbs flexlaptop slider_type_select'
);

$this->settings['slider_speed'] = array(
	'section' => 'slider',
	'title'   => __( 'Slideshow Speed', 'icore' ),
	'desc'    => __( 'Set the speed of the slideshow cycling, in milliseconds. 1 second = 1000 milliseconds.', 'icore' ),
	'type'    => 'text',
	'std'     => '7000',
	'class' => ' pid flexslider flexthumbs flexlaptop slider_type_select'
);


$this->settings['slider'] = array(
    'section' => 'slider',
    'title'   => __( 'Slideshow Images', 'icore' ),
    'desc'    => __( 'Upload slider Images. Drag and drop to reorganize.', 'icore'),
    'type'    => 'slide',
    'std'     => '',
	'class' => ' pid flexslider flexthumbs flexlaptop slider_type_select'
);

?>