<?php

define( 'FRAMEWORK_UPDATE_PATH', 'http://skeleton.tastique.org/stable/framework' );
define( 'THEME_UPDATE_PATH', 'http://skeleton.tastique.org/stable/themes/' . THEMENAME );


/****************************************************************/
/*                 Filters and their Documentation              */
/****************************************************************/

/*
Initializes our session
*/
add_action('init', 'rf_init_sessions');

/* 
Used to modify the caption element
*/
add_filter( 'img_caption_shortcode', 'rf_caption_shortcode', 10, 3 );

/* 
Modifies the contents of text-type widgets
*/  
add_filter( 'widget_text', 'rf_widget_text' );

/* 
Modifies the content of the posts where "the_content" is used
*/
add_filter( 'the_content', 'rf_empty_tags_fix' );

/* 
Used to register menus
*/
add_action( 'init', 'rf_register_menus' );

/* 
Modifies jpeg Quality
*/
add_filter( 'jpeg_quality', 'rf_jpeg_quality' );

/*
Register and Enqueue Scripts
*/
add_action( 'wp_enqueue_scripts', 'rf_frontend_scripts', 1 );
add_action( 'wp_enqueue_scripts', 'rf_backend_scripts' );

/*
Modify Gallery Style
*/
add_action( 'gallery_style', 'rf_gallery_style' );

/*
Add elements to the website footer
*/
add_action( 'wp_footer', 'rf_add_analytics_code' );


/*
Add custom fonts to the site
*/
add_action( 'wp_head', 'rf_set_fonts' );

/*
Modify the post password form
*/
add_filter( 'the_password_form', 'rf_custom_password_form' );

/*
Modify the admin footer to put the version number in
*/
add_filter('admin_footer_text', 'rf_admin_footer');


/*
The AJAX action for checking the framework version against the latest
*/
add_action( 'wp_ajax_rf_check_framework_update', 'rf_check_framework_update' );


/*
The AJAX action for updating the framework
*/
add_action( 'wp_ajax_rf_update_framework', 'rf_update_framework' );


/*
The AJAX action for checking the theme version against the latest
*/
add_action( 'wp_ajax_rf_check_theme_update', 'rf_check_theme_update' );

/*
The AJAX action for updating the theme
*/
add_action( 'wp_ajax_rf_update_theme', 'rf_update_theme' );



function get_rf_option( $option, $default = '' ) {
	global $theme_options;
	if( !isset( $theme_options[$option] ) OR empty( $theme_options ) ) {
		$option = $default;
	}
	else {
		$option = $theme_options[$option];
	}
	
	return $option;
}


/****************************************************************/
/*                  Frontend Display Functions                  */
/****************************************************************/

function rf_canonical_url( $echo = true ) {
	if( is_home() OR is_front_page() ) {
		$url = 	home_url();
	}	
	elseif( is_singular() ) {
		global $post;
		$url = get_permalink( $post->ID );
	}
	else {
		global $wp;
		$url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
	}
	
	if( $echo == true ) {
		echo $url;
	}
	else {
		return $url;
	}
}


function rf_mime_types( $mime_types ) {
	$mime_types['ico'] = 'image/x-icon';

	return $mime_types;
}


add_filter('upload_mimes', 'rf_mime_types');


function rf_site_title( $echo = true ) {

	if( isset( $theme_options['rfoption_meta_title'] ) AND !empty( $theme_options['rfoption_meta_title'] ) ) {
		$title = $theme_options['rfoption_meta_title'];
	}
	else {
		$title = get_bloginfo( 'name' );
	}
	
	if( $echo == true ) {
		echo $title;
	}
	else {
		return $title;
	}
}



function rf_show_price( $amount ) {
	global $theme_options;
	$currency = ( isset( $theme_options['rfoption_product_currency'] ) AND !empty( $theme_options['rfoption_product_currency'] ) ) ? $theme_options['rfoption_product_currency'] : '$';
	$currency_position = ( isset( $theme_options['rfoption_product_currency_position'] ) AND !empty( $theme_options['rfoption_product_currency_position'] ) ) ? $theme_options['rfoption_product_currency_position'] : 'before';
	$price = ( $currency_position == 'before' ) ? $currency.$amount : $amount.' ' . $currency;
	if( isset( $amount ) AND $amount != '' ) {
		echo $price;
	}
}

function rf_meta_title( $echo = true ) {
	global $page, $paged, $theme_options;

	ob_start();	
	
	rf_site_title();
	wp_title();
	
	if( isset( $theme_options['rfoption_meta_tagline'] ) AND !empty( $theme_options['rfoption_meta_tagline'] ) ) {
		$site_description = $theme_options['rfoption_meta_tagline'];
	}
	else {
		$site_description = get_bloginfo( 'description', 'display' );
	}			
	

	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'rf_themes' ), max( $paged, $page ) );
		
	if( $echo == false ) {
		return ob_get_clean();
	}
	else {
		ob_flush();
	}
	
}

function rf_excerpt( $text, $length) {
	if( strlen( $text ) <= $length ) {
		return $text;
	}
	else {
		$excerpt = substr( $text, 0, $length );
		$pos = strrpos( $excerpt, ' ' );
		$excerpt = substr( $excerpt, 0, $pos); 
		return $excerpt.'...';
	}
}


function rf_meta_description( $echo = true ) {
	global $theme_options, $post;
	the_post();
	
	if( is_singular() ) {
		$description = get_the_excerpt();
	}
	else {
		if( isset( $theme_options['rfoption_meta_description'] ) AND !empty( $theme_options['rfoption_meta_description'] ) ) {
			$description = $theme_options['rfoption_meta_description'];
		}
		else {
			$description = get_bloginfo( 'description' );
		}			
	}
	
	rewind_posts();
	
	if( $echo == true ) {
		echo $description;
	}
	else {
		return $description;
	}
	
}

function rf_meta_image( $echo = true ) {
	global $theme_options, $post;
	
	$image = ''; 
	if( is_singular() ) {
		$image_id = get_post_thumbnail_id( $post->ID );
		if( isset( $image_id ) AND !empty( $image_id ) ) {
			$image = wp_get_attachment_image_src( $image_id, 'thumbnail' );
			$image = $image[0];
		}
	}
		
	if( isset( $theme_options['rfoption_meta_image'] ) AND !empty( $theme_options['rfoption_meta_image'] ) AND $image == '' ) {
		$image = $theme_options['rfoption_meta_image'];
	}		
		
	if( $echo == true ) {
		echo $image;
	}
	else {
		return $image;
	}
	
}



function rf_show_favicons() {
	global $theme_options;
?>

		<?php if( isset( $theme_options['rfoption_images_favicon'] ) AND !empty( $theme_options['rfoption_images_favicon'] ) ) : ?>
			<link rel='icon' href='<?php echo $theme_options['rfoption_images_favicon'] ?>' type='image/x-icon' />
		<?php endif ?>	
		<?php if( isset($theme_options['rfoption_images_touchicon'] ) AND !empty( $theme_options['rfoption_images_touchicon'] ) ) : ?>
			<link rel='apple-touch-icon' href='<?php echo $theme_options['rfoption_images_touchicon'] ?>' />
		<?php endif ?>	
	<?php
}

function rf_page_menu() {
	wp_page_menu('show_home=1');
}


function rf_site_logo() {
	global $theme_options;
	
	echo '<div id="header-logo">';
	
	if( isset( $theme_options['rfoption_images_logo'] ) AND !empty( $theme_options['rfoption_images_logo'] ) ) {
		echo '<a href="' . home_url() . '"><img alt="' . get_bloginfo( 'name' ) . ' Logo" src="' . $theme_options['rfoption_images_logo'] . '"></a>';
	}
	else {
		echo '<hgroup>';
		echo '<h1>' . get_bloginfo( 'name' ) . '</h1>';
		$tagline = get_bloginfo( 'description' );
		if( !empty( $tagline ) ) {
			echo '<h2>' . $tagline . '</h2>';
		}
		echo '</hgroup>';
	}
	
	echo '</div>';
}


function rf_get_form_error( $name, $errors ) {
	$error_types = array_keys( $errors );
	
	if( in_array( $name, $error_types ) ) {
		$output = '<div class="message error">';
			$output .= $errors[$name];
		$output .= '</div>';
		return $output;
	}
}

/* Modify Caption Display
 *
 * This function is used to remove the default 10px 
 * of hard-coded additional width WordPress adds to
 * captions
 * 
 * @param string $empty And empty parameter
 * @param array $attr Attributes passed to the caption
 * @param string $content The content inside the caption element
 *
 */

function rf_caption_shortcode( $empty, $attr, $content ) {

	extract( shortcode_atts( array( 
		'id'	=> '',
		'align'	=> 'alignnone',
		'width'	=> '',
		'caption' => ''
	 ), $attr ) );

	if ( 1 > ( int ) $width OR empty( $caption ) )
		return $content;

	if ( $id ) $id = 'id="' . esc_attr( $id ) . '" ';

	return '<div ' . $id . 'class="wp-caption ' . esc_attr( $align ) . '" style="width: ' . ( ( int ) $width ) . 'px">'
	. do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';

}

/* Font Settings
 *
 * This function is determines which fonts to use and
 * adds the necessary code to the site
 * 
 * @global array $theme_options The site options set up by the user
 *
 */
function rf_set_fonts() {
	global $theme_options;
	
	$fonts['heading'] = $theme_options['rfoption_fonts_heading'];
	$fonts['body'] = $theme_options['rfoption_fonts_body'];

	// We only need to do something if fonts are set
	if( ( isset( $fonts['heading'] ) AND !empty( $fonts['heading'] ) ) OR ( isset( $fonts['body'] ) AND !empty( $fonts['body'] ) ) ) {
		
		$hfonts = explode( ",",$fonts['heading'] );
		$hfonts = array_map( "trim", $hfonts );
		$hfont_stack = implode( ",", $hfonts );

		$bfonts = explode( ",",$fonts['body'] );
		$bfonts = array_map( "trim", $bfonts );
		$bfont_stack = implode( ",", $bfonts );
		
		$allfonts = array_merge( $hfonts, $bfonts );
		$allfonts = array_unique( $allfonts );
				
		$google_fonts = array();
		foreach( $allfonts as $font ) {
			$font = str_replace( ' ', '%20', $font );
			$google_fonts[] = $font. ":400,700"; 
		}

		$output = '<link href="http://fonts.googleapis.com/css?family=' . implode( '|', $google_fonts ) . '" rel="stylesheet" type="text/css">' . "\n";
		$output .= '<style type="text/css">' . "\n";
	}

	// If the heading font is set, output the correct CSS
	if( isset( $fonts['heading'] ) AND !empty( $fonts['heading'] ) ) {

		$output .= 'h1,h2,h3,h4,h5,h6 {' . "\n";
		$output .= 'font-family: ' . $hfont_stack . "\n";
		$output .=	'}' . "\n";
		
	}

	// If the body font is set, output the correct CSS
	if( isset( $fonts['body'] ) AND !empty( $fonts['body'] ) ) {
		
		$output .= 'body {' . "\n";
		$output .= 'font-family: ' . $bfont_stack . "\n";
		$output .= '}' . "\n";

		$output .= 'h1.bodyfont, h2.bodyfont, h3.bodyfont, h4.bodyfont, h5.bodyfont, h6.bodyfont {' . "\n";
		$output .= 'font-family: ' . $bfont_stack . "\n";
		$output .= '}' . "\n";
		
	}
	
	// If there was any output we need to close the style tag
	if( isset( $output ) AND !empty( $output ) ) {
		$output .= "</style>";
		echo $output;
	}

}


/* Custom Password Form
 *
 * This function outputs the form when a post is password 
 * protected. WordPress does this by default, we just modify
 * the form a bit so we can style it better.
 * 
 * @param string $form The HTML of the original form
 *
 * @global array $theme_options The site options set up by the user
 * @global object $post The WordPress post object
 *
 */

function rf_custom_password_form( $form ) {
	global $post, $theme_options;
	ob_start();
	?>
	
	<form action='<?php echo home_url() ?>/wp-pass.php' method='post' id='password-protected'>
	<p class='message alert text-center'><?php echo $theme_options['rfoption_password_protected_message'] ?></p>
	<div class='merged-input'>
		<input name='post_password' id='pwbox-<?php echo $post->ID ?>' class='input' type='password'>
		<input type='submit' name='Submit' class='submit' id='submit' value='Submit'></p></form>
	</div>
	
	<?php 
	
	$html = ob_get_clean();
	echo $html;
}


/* Remove Gallery Styline
 *
 * Removes the default inline style of galleries
 * 
 * @param string $css The original HTML for the CSS
 *
 */
function rf_gallery_style( $css ) {
	return preg_replace( "#<style type=\'text/css\'>( .*? )</style>#s", "", $css );
}



/* Add Text Widget Wrapper
 *
 * The "post-content" class is added to a wrapper around
 * text type widgets to ensure consistent styling
 * 
 * @param string $text The text of the widget
 *
 */

function rf_widget_text( $text ) {
	return "<div class='post-content'>" .do_shortcode($text). "</div>";
}


/* Empty Paragraph Bug Remover
 *
 * Makes sure that there are no empty paragaphs 
 * when using shortcodes
 * 
 * @param string $content The content passed to the function
 *
 */

function rf_empty_tags_fix( $content )
{   
    $array = array ( 
        '<p>[' => '[', 
        ']</p>' => ']', 
        ']<br />' => ']'
 );

    $content = strtr( $content, $array );

    return $content;
}


/* Modify jpeg Quality
 *
 * Enables us to modify the default WordPress jpeg quality
 * 
 * @param string $quality The original quality
 *
 */
function rf_jpeg_quality( $quality ) { 
	return 100;
}

/* Main Menu Display
 *
 * This is a fallback function for the menus. If there is no menu specified
 * this function kicks in and shows all the pages and the home link
 *
 */
function rf_main_menu() {
	wp_page_menu( "show_home=1" );
}

/* Get Sidebar Position
 *
 * Determines the position of the sidebar. It looks at the global settings
 * as well as the page specific settings. 
 * 
 * @global array $theme_options The theme options set by the user
 * @globsl object $post The WordPress post object
 *
 * @param string $postmeta Parses the sidebar info from the postmeta if given
 *
 */
function rf_get_sidebar_position( $postmeta = false, $force_global = false ) {
	global $theme_options, $post;

	$valid_values = array( 'left', 'right' );
	
	$global_position = $theme_options['rfoption_sidebar_position'];

	if( $force_global == true ) {
		$post_position = $global_position;
	}
	else {
		$post_position = ( isset( $postmeta['rfpostoption_sidebar_position'] ) AND !empty( $postmeta['rfpostoption_sidebar_position'] ) ) ? $postmeta['rfpostoption_sidebar_position'] : get_post_meta( $post->ID, "rfpostoption_sidebar_position", true );
		$post_position = ( !isset( $post_position ) OR empty( $post_position ) OR $post_position == "default" ) ? $global_position : $post_position;
	
		if( !isset( $post_position ) OR empty( $post_position ) OR $post_position == 'default') {
			$post_position = $global_position;
		}		
	}

	return $post_position;
}


/* Get Sidebar
 *
 * Determines which sidebar should be shown. Default is 'Sidebar' but this can
 * be changed in the page specific settings.
 * 
 * @global array $postmeta The metadata of the page in question
 * @globsl object $post The WordPress post object
 *
 */
function rf_get_sidebar( $products = false ) {
	global $postmeta, $post, $theme_options;		

	$sidebar = ( isset( $postmeta['rfpostoption_sidebar_custom'] ) AND !empty( $postmeta['rfpostoption_sidebar_custom'] ) ) ? $postmeta['rfpostoption_sidebar_custom'] : get_post_meta( $post->ID, "rfpostoption_sidebar_custom", true );	
	
	$sidebar = ( !isset( $sidebar ) OR empty( $sidebar ) ) ? 'Sidebar' : $sidebar;

	if( $products == true ) {
		$sidebar = ( isset( $theme_options['rfoption_sidebar_custom'] ) AND !empty( $theme_options['rfoption_sidebar_custom'] ) ) ? $theme_options['rfoption_sidebar_custom'] : $sidebar;
	}

	
	return $sidebar;
	
}

/* Get Content Position
 *
 * Determines the position of the content by looking at the position of 
 * the sidebar. 
 * 
 * @uses rf_get_sidebar_position()
 *
 */
function rf_get_content_position( $force_global = false ) {
	$sidebar_position = rf_get_sidebar_position( false, $force_global );
	
	if( $sidebar_position == "left" ) {
		$content_position = "right";
	}
	else {
		$content_position = "left";
	}
	
	return $content_position;
}

/* Has Sidebar
 *
 * Determines weather the post in question has a sidebar or not
 * 
 * @global array $postmeta The metadata of the page in question
 * @globsl object $post The WordPress post object
 *
 */
function rf_has_sidebar() {
	global $post, $postmeta; 

	$disable_sidebar = ( isset( $postmeta['rfpostoption_sidebar_disable'] ) AND !empty( $postmeta['rfpostoption_sidebar_disable'] ) ) ? $postmeta['rfpostoption_sidebar_disable'] : get_post_meta( $post->ID, 'rfpostoption_sidebar_disable', true );
	
	if( isset( $disable_sidebar ) AND $disable_sidebar == 'yes' ) {
		return false;
	}
	else {
		return true;
	}
} 

/* Has Meta
 *
 * Determines weather the postmeta should be shown for the post in question
 * 
 * @global array $postmeta The metadata of the page in question
 * @globsl object $post The WordPress post object
 *
 */
function rf_has_meta() {
	global $post, $postmeta;
	
	$disable_meta = ( isset( $postmeta['rfpostoption_meta_disable'] ) AND !empty( $postmeta['rfpostoption_meta_disable'] ) ) ? $postmeta['rfpostoption_meta_disable'] : get_post_meta( $post->ID, 'rfpostoption_meta_disable', true );
	
	if( isset( $disable_meta ) AND $disable_meta == 'yes' ) {
		return false;
	}
	else {
		return true;
	}
}

/**
 * Initialize a session
 *
 */
function rf_init_sessions() {
    if ( session_id() == '' ) {
        session_start();
    }
}


/* Has Thumbnail
 *
 * Determines weather the featured image should be shown for the post in question
 * 
 * @global array $postmeta The metadata of the page in question
 * @globsl object $post The WordPress post object
 *
 */
function rf_has_thumbnail() {
	global $post, $postmeta;

	$disable_thumbnail = ( isset( $postmeta['rfpostoption_thumbnail_disable'] ) AND !empty( $postmeta['rfpostoption_thumbnail_disable'] ) ) ? $postmeta['rfpostoption_thumbnail_disable'] : get_post_meta( $post->ID, 'rfpostoption_thumbnail_disable', true );
	
	if( ( isset( $disable_thumbnail ) AND $disable_thumbnail == 'yes') OR has_post_thumbnail() == false ) {
		return false;
	}
	else {
		return true;
	}
}

/* Has Title
 *
 * Determines weather the title should be shown for the post in question
 * 
 * @global array $postmeta The metadata of the page in question
 * @globsl object $post The WordPress post object
 *
 */
function rf_has_title( $default = true ) {
	global $post, $postmeta;
	
	$has_title = $default;
	
	$disable_title = ( isset( $postmeta['rfpostoption_title_disable'] ) AND !empty( $postmeta['rfpostoption_title_disable'] ) ) ? $postmeta['rfpostoption_title_disable'] : get_post_meta( $post->ID, 'rfpostoption_title_disable', true );
	
	if( isset( $disable_title ) AND !empty( $disable_title ) ) {
		$has_title = ( isset( $disable_title ) AND $disable_title == 'yes' ) ? false : true;
	}

	$show_title = ( isset( $postmeta['rfpostoption_title_show'] ) AND !empty( $postmeta['rfpostoption_title_show'] ) ) ? $postmeta['rfpostoption_title_show'] : get_post_meta( $post->ID, 'rfpostoption_title_show', true );	

	if( isset( $show_title ) AND !empty( $show_title ) ) {	
		$has_title = ( isset( $show_title ) AND $show_title == 'yes' ) ? true : false;
	}

	return $has_title;

}

/* Content Classes
 *
 * Determines the classes to add to the content for correct display.
 * Since the content container is a column in the CSS grid we need
 * to give it the correct class
 * 
 * @global array $postmeta The metadata of the page in question
 * @globsl object $post The WordPress post object
 *
 */
function rf_content_classes( $force_global = false ) {
	global $post, $postmeta;

	$disable_sidebar = ( isset( $postmeta['rfpostoption_sidebar_disable'] ) AND !empty( $postmeta['rfpostoption_sidebar_disable'] ) ) ? $postmeta['rfpostoption_sidebar_disable'] : get_post_meta( $post->ID, 'rfpostoption_sidebar_disable', true );

	if( isset( $disable_sidebar ) AND $disable_sidebar == 'yes' ) {	
		$classes[] = 'twelvecol';
	}
	else {
		$classes[] = 'eightcol';	
	}
	
	$classes[] = rf_get_content_position( $force_global );
	
	if( in_array( 'right', $classes ) ) {
		$classes[] = 'last';
	}
	
	$classes = implode( ' ', $classes );
	
	return $classes;
}


function rf_sidebar_classes( $force_global = false ) {
	global $post, $postmeta;
	
	$classes[] = 'fourcol'; 
	$classes[] = rf_get_sidebar_position( false, $force_global );
	
	if( in_array( 'right', $classes ) ) {
		$classes[] = 'last';
	}
	
	$classes = implode( ' ', $classes );
	
	return $classes;
}



/* Add Analytics Code
 *
 * Adds the analytics code if it is given
 * 
 * @global array $theme_options The options set by the user
 *
 */
function rf_add_analytics_code() {
	global $theme_options;
	if( isset( $theme_options['rfoption_analytics_code'] ) AND !empty( $theme_options['rfoption_analytics_code'] ) ) {
		echo stripcslashes( $theme_options['rfoption_analytics_code'] );
	}


}



/* Shortcode Documentation
 *
 * Outputs the documentation of the shortcode in question. The data used
 * is set up in the shortcode's code in shortcodes.php
 * 
 * @param array $data The data describing the shortcode
 * @globsl string $type The type of display (short or long)
 *
 */
function rf_shortcode_info( $data, $type = 'long' ) {
	ob_start(); ?>
   		<div class='rf-shortcode-sample'>
   			<h4>Shortcode: <span class='shortcode-tag'><?php echo $data['title'] ?></span></h4>
 			<div class='inner'>
 				<p class='description'><?php echo $data['description'] ?></p>
 				<?php if( isset( $data['parameters'] ) AND !empty( $data['parameters'] ) AND $type != 'short' ) : ?>
	 				<?php foreach ( $data['parameters'] as $paramtype => $parameters ) : ?> 
		 				<h5><?php echo $paramtype ?></h5>
			   			<ul class='parameters'>
			   				<?php foreach( $parameters as $parameter ) : ?>
			   				<li>
			   					<h6><?php echo $parameter['name'] ?> <small>( default: <?php echo $parameter['default'] ?> )</small></h6>
			   					<?php echo $parameter['description'] ?>
			   					<?php if ( isset( $parameter['values'] ) AND !empty( $parameter['values'] ) ) : ?>
				   					<br>Possible Values:
				   					<ul class='values'>
				   						<?php foreach( $parameter['values'] as $value ) : ?>
				   						<li>
				   							<strong><?php echo $value['name'] ?></strong> 
					   							<?php if( isset( $value['default'] ) AND $value['default'] === true ) : ?>
					   								<small>( default )</small>
					   							<?php endif ?>
	
					   							<?php if( isset( $value['description'] ) AND !empty( $value['description'] ) ) : ?>
						   							- <?php echo $value['description'] ?>
					   							<?php endif ?>
				   						</li>
				   						<?php endforeach ?>
				   					</ul>
								<?php endif ?>
			   				</li>
			   				<?php endforeach; ?>
			   			</ul>
			   		<?php endforeach ?>
		   		<?php endif ?>
   			</div>
   		</div>
   	<?php	
   	
   	$info = ob_get_clean();
   	return $info;
}

/****************************************************************/
/*             Front/Backend Functionality Functions            */
/****************************************************************/

/* Register Menus
 * 
 * Defines the custom menu positions used in this theme
 *
 */

function rf_register_menus() {
	global $register_menus;
	if( isset( $register_menus ) AND !empty( $register_menus ) ) { 
	register_nav_menus( 
		$register_menus
	);
	}
}

/* Register and enqueue frontend scripts
 *
 */
	function rf_frontend_scripts() {
		if ( !is_admin() ) {
			wp_register_script( 'rf-frontend', get_template_directory_uri(). '/framework/js/rf_frontend.js', array( 'jquery' ) );
			wp_register_script( THEMENAME, get_template_directory_uri(). '/js/' . THEMENAME . '.js', array( 'jquery', 'rf-frontend' ) );
			wp_register_script( 'jquery-flexslider', get_template_directory_uri(). '/framework/js/jquery.flexslider-min.js', array( 'jquery' ) );

			wp_register_script( 'jquery-scrollto', get_template_directory_uri(). '/framework/js/jquery.scrollTo-1.4.2.js', array( 'jquery' ) );
			
			if ( is_singular() AND get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
			
			wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'rf-frontend' );
			wp_enqueue_script( 'jquery-formalize' );				
				wp_enqueue_script( 'jquery-scrollto' );				
		wp_enqueue_script( THEMENAME );
		
		wp_enqueue_script('jquery-flexslider');

		}
	}


/* Register and enqueue backend scripts
 *
 */
	function rf_backend_scripts() {
		if ( is_admin() ) {
			
		}
	}	


/* Register sidebars
 *
 */
global $register_sidebars;
if ( isset( $register_sidebars ) AND !empty( $register_sidebars ) ) { 
	foreach( $register_sidebars as $sidebar ) {
		register_sidebar( $sidebar );
	}
}

if( isset( $theme_options['rfoption_sidebars_custom'] ) AND !empty( $theme_options['rfoption_sidebars_custom'] ) ) {

	$sidebars = explode( ',', $theme_options['rfoption_sidebars_custom'] );
	$sidebars = array_map( 'trim', $sidebars );
	
	foreach( $sidebars as $sidebar ) {
		register_sidebar( array( 
			'name'           => $sidebar,
			'id'             => sanitize_title_with_dashes( $sidebar ),
			'before_widget'  => '<div class="widget">',
			'after_widget'   => '</div>',
			'before_title'   => '<div class="widget-title"><h1 class="title-text">',
			'after_title'    => '</h1><div class="title-bg"></div><div class="clear"></div></div>',
		 ) );		
	}

}

	
		
		
/* Comment Display
 *
 * Handles the display of all the comments on the site
 *
 * @param object $comment The WordPress comment object
 * @param array $args The arguments passed to the comment list
 * @param int $depth The allowed depth for comment replies 
 */
if( !function_exists( 'rf_comment' ) ) {
function rf_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	
	<li class="pingback" id="comment-<?php comment_ID(); ?>">
			<span class='pingback-edit'><?php edit_comment_link(); ?></span>
			<div class='pingback-content'>Pingback: <?php comment_author_link(); ?></div>
			
	<?php
			break;
		default :
	?>
	
	
	<?php if ( $comment->comment_approved == '0' ) : ?>
		<div class='message alert'>
			Your comment is awaiting moderation		
		</div>
	<?php endif; ?>
						
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-container">
			
			<div class='threecol'>
				<div class='image'>
					<?php echo get_avatar( $comment, 200 ); ?>
				</div>
			</div>
			
			<div class='ninecol last'>
				<div class='comment-main'>
					<div class='comment-header primary-links'>
						<span class='comment-author'><?php echo get_comment_author_link() ?></span>
						<span class='comment-date'><?php echo get_comment_date() ?> <?php echo get_comment_time() ?></span>
						<span class='comment-edit'><?php edit_comment_link( 'edit', '| ' ) ?></span>
						<span class='comment-reply'>
							<?php 
								comment_reply_link( array_merge( $args, array( 
									'reply_text' => 'reply', 
									'depth'      => $depth, 
									'max_depth'  => $args['max_depth'] ) ) 
								 ); 
							?>	
					</div>
					<div class='comment-content content'>
						<?php comment_text(); ?>
					</div>
				</div>
			</div>
			
		</div>
	<?php
			break;
	endswitch;
}
}
 
 
function rf_get_meta_position( $postmeta = false, $sidebar = false ) {
	if ($postmeta == false) {
	
	}
	else {
		$sidebar = rf_get_sidebar_position( $postmeta );	
	}
	$position = ( isset( $sidebar ) AND $sidebar == 'left' ) ? 'right' : 'left';
	return $position;
}


function rf_post_pagination( $query = false ) {
	if( $query == false ) {
		global $wp_query;
		$query = $wp_query;
	}
	
	
	$pagination = array(
		'base'       => str_replace( 99999, '%#%', get_pagenum_link( 99999 ) ),
		'format'     => '?paged=%#%',
		'current'    => max( 1, get_query_var( 'paged' ) ),
		'total'      => $query->max_num_pages,
		'next_text'  => "next",
		"prev_text"  => "previous"			
	);
	echo '<div class="pagination">';

	ob_start();
	echo paginate_links( $pagination ); 
	$html = ob_get_clean();
	$html = str_replace( ">next<", '><img src="' . get_template_directory_uri() . '/images/arrow_white_right.png"><', $html );	
	$html = str_replace( ">previous<", '><img src="' . get_template_directory_uri() . '/images/arrow_white_left.png"><', $html );	

	echo $html;
	echo '</div>';

}


function rf_breadcrumb() {
	global $post, $wpdb, $in_mashup;

	if( !isset( $in_mashup ) OR $in_mashup != true ) {
		if( is_category() OR is_tag() OR is_year() OR is_month() OR is_day() OR is_search() OR is_singular( 'post' ) ) {
			$output = '<a href="'. home_url().'">Home</a> /';	
		}
	
			

		elseif( is_singular( 'rf_product' ) ) {	
			$product_page = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE ID IN ( SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_wp_page_template' AND meta_value = 'template-productpage.php') AND post_status = 'publish' ORDER BY post_date DESC LIMIT 0,1 " );		
			if( isset( $product_page ) AND !empty( $product_page ) ) {
				$products = '<a href="' . get_permalink( $product_page ) . '">' . get_the_title( $product_page ) . '</a>';
			}
			else {
				$products = '<span>Products</span>'; 
			}
			
			$output =  '<a href="'. home_url() .'">Home</a> / ' . $products . ' /';		
		}
			
		elseif( is_singular( 'page' ) ) {
			$parent = $post->post_parent;
			$hierarchy = array();
			$hierarchy[] = $post->post_parent;
			while( $parent > 0 ) {
				$parent = $wpdb->get_var( "SELECT post_parent FROM $wpdb->posts WHERE post_id = $parent " );
				$hierarchy[] = $parent;		
			}
	
			foreach( $hierarchy as $page ) {
				if( !empty( $page ) ) {
					$pages[] = '<a href="' . get_permalink( $page ) . '">' . get_the_title( $page ) . '</a>';
				}
			}
			
			
			$pages = ( isset( $pages ) AND !empty( $pages ) ) ? implode( ' / ', $pages) . ' / ' : '';
	
			$output =  '<a href="'. home_url() .'">Home</a> / ' . $pages;		
		}
	
	
		if( isset( $output ) AND !empty( $output ) AND !is_home() ) {
			echo '<div class="breadcrumb">';
			echo $output;
			echo '</div>';
		}
	}
}

 
/****************************************************************/
/*                       Backend  Functions                     */
/****************************************************************/
 
	add_editor_style( "/framework/css/editor-style.css" );
	


	
	/* Framework Version Number
	 *
	 * Adds the Red Facotry framework version number to the admin footer
	 * 
	 * @uses rf_get_framework_current_version()
	 *
	 */		
	function rf_admin_footer () {
		//$themedata = get_theme_data(get_template_directory()  . '/style.css');

		echo 'Thank you for creating with <a href="http://wordpress.org">WordPress</a>';
	}

	/* Show user the framework update state
	 *
	 * Used by an AJAX call to show the user weather an update is available or not
	 * 
	 * @uses rf_is_framework_update_needed()
	 *
	 */	
	function rf_check_framework_update() {

		$update_needed = rf_is_framework_update_needed();
		
		if( $update_needed === true) {
			$result['update'] = 'yes';
			$result['output'] = '
				<h2>A new version of the framework is available</h2>
				<p>
				If you would like to update now please click on the button below. 
				The framework directory and the readme.txt file in your theme\'s folder will be 
				updated, any changes made to these files will be overwritten. 
				</p>
				<a href="" id="update_framework" data-original="update now" class="button loading primary">update now</a>
			';
		}
		else {
			$result['update'] = 'no';
			$result['output'] = '<h2>You are running the latest version of the framework (' . rf_get_framework_current_version() . ')</h2>';
		}
		
		echo json_encode( $result );

		die();
	}
	

	/* Show user the theme update state
	 *
	 * Used by an AJAX call to show the user weather an update is available or not
	 * 
	 * @uses rf_is_theme_update_needed()
	 *
	 */	
	function rf_check_theme_update() {

		$update_needed = rf_is_theme_update_needed();
		
		if( $update_needed === true) {
			$result['update'] = 'yes';
			$result['output'] = '
				<h2>A new version of the theme is available</h2>
				<p>
				If you would like to update now please click on the button below. 
				Your theme files will all be deleted and replaced by the new files. Even if something goes
				wrong the data on your website is safe but if you\'ve made changes to the files in the theme
				they will be lost.  
				</p>
				<a href="#" id="update_theme" data-original="update now" class="button loading primary">update now</a>
			';
		}
		else {
			$result['update'] = 'no';
			$result['output'] = '<h2>You are running the latest version of the theme (' . rf_get_theme_current_version() . ')</h2>';
		}
		
		echo json_encode( $result );

		die();
	}


	/* Framework Updater
	 *
	 * This function takes a look at the most recent version, downlods
	 * it, unzips it and replaces the framework with it. 
	 * 
	 * @uses rf_get_framework_latest_version()
	 * @uses rf_delete_directory()
	 *
	 */		
	function rf_update_framework() {
		
		$error = false; 
		
		$latest_version = rf_get_framework_latest_version();
		$tempfile = download_url( FRAMEWORK_UPDATE_PATH . '/' . $latest_version . '.zip' );
				
		rf_delete_directory( get_template_directory()  . '/framework/' );
		$zip = new ZipArchive;
		$res = $zip->open( $tempfile );
		$zip->extractTo( get_template_directory()  );
		$zip->close();
		
		rf_delete_directory( get_template_directory()  . '/__MACOSX/' );
		
		unlink( $tempfile );
		
		die();
	}
	


	/* Theme Updater
	 *
	 * This function takes a look at the most recent version, downlods
	 * it, unzips it and replaces the theme with it. 
	 * 
	 * @uses rf_get_theme_latest_version()
	 * @uses rf_delete_directory_files()
	 *
	 */		
	function rf_update_theme() {
		$error = false; 
		
		$latest_version = rf_get_theme_latest_version();
		$tempfile = download_url( THEME_UPDATE_PATH . '/' . $latest_version . '.zip' );
				
		rf_delete_directory( get_template_directory()  , false );
		$zip = new ZipArchive;
		$res = $zip->open( $tempfile );
		$zip->extractTo( get_template_directory()  );
		$zip->close();
		
		rf_delete_directory( get_template_directory()  . '/__MACOSX/' );
		
		unlink( $tempfile );
		
		die();
	}
	
	
	/* Get latest framework version
	 *
	 */		
	function rf_get_framework_latest_version() {
		return file_get_contents( FRAMEWORK_UPDATE_PATH . '/version.php' );
	}
	
	
	/* Framework Version
	 *
	 * Determines the framework version used
	 * 
	 * @return string The framework version
	 *
	 */		
	function rf_get_framework_current_version() {
		$path = get_template_directory()  . '/framework/version.txt';
		//$file = fopen( $path, 'r' );
		//$data = fread( $file, filesize( $path ) );
		fclose($file);	
		
		return trim($data);
	}
	
	
	/* Get latest theme version
	 *
	 */		
	function rf_get_theme_latest_version() {
		return file_get_contents( THEME_UPDATE_PATH . '/version.php' );
	}	
	
	/* Thene Version
	 *
	 * Determines the theme version used
	 * 
	 * @return string The theme version
	 *
	 */		
	function rf_get_theme_current_version() {
		//$themedata = get_theme_data(get_template_directory()  . '/style.css');
		return $themedata['Version'];
	}
	
	
	/* Is a new framewrok needed?
	 *
	 * Determines weather a new framework version is needed
	 * 
	 * @uses rf_get_framework_current_version()
	 * @uses rf_get_framework_latest_version()
	 * 
	 * @return bool true if it is, false if it isn't
	 *
	 */	
	function rf_is_framework_update_needed() {

		$current_version = rf_get_framework_current_version();
		$latest_version = rf_get_framework_latest_version();

		$update_needed = ( version_compare( $current_version, $latest_version, '>=' ) ) ? false : true;
		
		return $update_needed;			
	}
 
 
	/* Is a new theme needed?
	 *
	 * Determines weather a new theme version is needed
	 * 
	 * @uses rf_get_theme_current_version()
	 * @uses rf_get_theme_latest_version()
     *
	 * @return bool true if it is, false if it isn't
	 *
	 */	
	function rf_is_theme_update_needed() {

		$current_version = rf_get_theme_current_version();
		$latest_version = rf_get_theme_latest_version();

		$update_needed = ( version_compare( $current_version, $latest_version, '>=' ) ) ? false : true;
		
		return $update_needed;			
	}
	
	
/****************************************************************/
/*                            Widgets                           */
/****************************************************************/

	include_once( 'widgets/rf_widgets_latest_posts.php' );
	include_once( 'widgets/rf_widgets_featured_item.php' );
	include_once( 'widgets/rf_widgets_contact.php' );
	include_once( 'widgets/rf_widgets_map.php' );
	include_once( 'widgets/rf_widgets_twitter.php' );


/****************************************************************/
/*                        Helper Functions                      */
/****************************************************************/


/**
 * Checks if AJAX is happennning
 *
 * Checks server variables to see if there is an AJAX call
 * going on
 *
 * return bool true if this is an AJAX call
 *
 */	
function rf_is_ajax() {
	if( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) AND strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
		return true;
	}
	else {
		return false;
	}
	
}

/**
 * Deletes a directory 
 *
 * This function deletes the contents of a directory and the 
 * directory itself. It is used only to update the framework and
 * should not be used for anything else
 *
 * @param string $dir The path to the directory to delete
 *
 */	
function rf_delete_directory( $dir, $dirdelete = true ) {
	if ( is_dir( $dir ) ) {
		$objects = scandir( $dir );
		foreach ( $objects as $object ) {
			if ( $object != '.' AND $object != '..' ) {
				if ( filetype( $dir . '/' . $object ) == 'dir') {
					rf_delete_directory( $dir . '/' . $object );
				} 
				else { 
					unlink( $dir . '/' . $object );
				}
			}
		}
		reset($objects);
		
		if( $dirdelete === true ) {
			rmdir($dir);
		}
	}
}

 
/**
 * Retrieve all postmeta 
 *
 * Pulls all the metadata of a post into
 *
 * @global object $wpdb The WordPress database class
 * @global object $post THe WordPress post class
 *
 * @param int $post_id The ID of the psot, or 0 if pulled locally
 *
 * @return array $postmeta All the postmeta for a post
 *
 */			
function get_postmeta( $post_id = 0 ) {
	global $wpdb, $post;

	$post_id = ( isset( $post_id ) AND !empty( $post_id ) ) ? $post_id : 0;
	$post_id = ( ( !isset( $post_id ) OR empty( $post_id ) ) AND ( isset( $post->ID ) AND !empty( $post->ID ) ) ) ? $post->ID : $post_id;

	if( isset( $post_id ) AND !empty( $post_id ) ) {
		$metas = $wpdb->get_results( "SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id = $post_id" );
		
		foreach( $metas as $meta ) {
			$key = $meta->meta_key;
							
			$array = @unserialize( $meta->meta_value );
			if ( $array === false AND $meta->meta_value !== 'b:0;' ) {
				$value = $meta->meta_value;
			}
			else {
				$value = unserialize( $meta->meta_value );
			}

			$postmeta[$key] = $meta->meta_value;
		}
	}
	else {
		$postmeta = false;
	}

	return $postmeta;
}

	
	
/* Hex Color Modifier
 *
 * Enables easy lightness manipulation of hex colors. The function
 * takes a color, a direction to modify and a level of modification 
 * which is a two character string. The function will convert the 
 * HEX color to RGB, modify it, and convert it back to RGB
 *
 * @param string $color The HEX color to modify
 * @param string $direction The direction to modify the color (+ to lighten, - to darken)
 * @param string $modify The amount to modify, as a two character HEX value (eg.: 22 or 55 or aa)
 *
 */
function rf_hcm( $color, $direction, $modify ) {
	
	if( substr( $color, 0, 1 ) == "#" ) {
		$color = substr( $color, 1 );
	}
	
	$rgb = str_split( $color, 2 );
	$modify = hexdec( $modify );
			
	foreach( $rgb as $value ) {
		$newcolor_value = "";
		$newcolor_value = ( $direction == "+" ) ? hexdec( $value ) + $modify : hexdec( $value ) - $modify;
		if( $newcolor_value < 0 ) {
			$newcolor_value = "00";
		}
		elseif( $newcolor_value > 0 AND $newcolor_value < 16 ) {
			$newcolor_value = "0" .dechex( $newcolor_value );
		}
		elseif( $newcolor_value > 255 ) {
			$newcolor_value	= "ff";
		}
		else {
			$newcolor_value = dechex( $newcolor_value );
		}
		
		$newcolor[] = $newcolor_value;
	}

	return implode( $newcolor );

}


/* Color Gradient Maker
 *
 * This functon outputs the styles necessary to make a gradient. This is usually
 * handled by stylesheets but for the dynamic coloring we need to be able to 
 * do it in PHP as well. As in the stylehseet files, the gradient is generated
 * by taking a bas color, subtracting #151515 to get the darker color and adding
 * #151515 to get the lighter color. 
 *
 * @param string $color The base color to use as the gradient
 * 
 */
function rf_css_gradient( $color ) {
	if( strlen( $color ) == 4 ) {
		$c = substr( $color, 1 );
		$c = str_split( $c );
		$color = $c[0].$c[0].$c[1].$c[1].$c[2].$c[2];
	} 
	

	$start = rf_hcm( $color, "+", "15" );
	$end = rf_hcm( $color, "-", "15" );

	$gradient = array();
	$gradient[] = 'background: $color';
	$gradient[] = 'background-image: -moz-linear-gradient( top,  #' . $start . ' 0%, #' . $end . ' 100% )';
	$gradient[] = 'background-image: -webkit-gradient( linear, left top, left bottom, color-stop( 0%, #' . $start . ' ), color-stop( 100%, #' .$end. ' ) )';
	$gradient[] = 'background-image: -webkit-linear-gradient( top,  #' . $start . ' 0%, #' . $end . ' 100% )';
	$gradient[] = 'background-image: -o-linear-gradient( top, #' . $start . ' 0%, #' . $end . ' 100% )';
	$gradient[] = 'background-image: -ms-linear-gradient( top,  #' . $start . ' 0%, #' . $end . ' 100% )'; 
	$gradient[] = 'background-image: linear-gradient( top,  #' . $start . ' 0%, #' . $end . ' 100% )';
	$gradient[] = 'filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=&quot;#' . $start . '&quot;, endColorstr=&quot;#' . $end . '&quot;,GradientType=0 )';
	
	$style = implode( ';', $gradient );
	
	return $style;
}




function rf_css_text_shadow( $hex ) {

	$base_color = substr( $hex, 1 );	
	if( strlen( $base_color ) == 3 ) {
		$c = str_split( $base_color );
		$base_color = $c[0].$c[0].$c[1].$c[1].$c[2].$c[2];
	} 
	
	$r = hexdec(substr($base_color,0,2));
	$g = hexdec(substr($base_color,2,2));
	$b = hexdec(substr($base_color,4,2));
		
	if($r + $g + $b > 382){
	   $shadow = '0px -1px 0px #' . rf_hcm( '#'.$base_color , '-', 22 );
	}else{
	   $shadow = '-1px -1px 0px rgba(255,255,255, 0.3)';
	}

	return $shadow;
	
}

function rf_css_text_color( $hex ) {

	$base_color = substr( $hex, 1 );	
	if( strlen( $base_color ) == 3 ) {
		$c = str_split( $base_color );
		$base_color = $c[0].$c[0].$c[1].$c[1].$c[2].$c[2];
	} 
	
	$r = hexdec(substr($base_color,0,2));
	$g = hexdec(substr($base_color,2,2));
	$b = hexdec(substr($base_color,4,2));
		
	if($r + $g + $b > 497){
	   $color = '#' . rf_hcm( '#'.$base_color , '-', 88 ) ;
	}else{
	   $color = '#ffffff';
	}

	return $color;
}


/* Radius Maker
 *
 * This functon outputs the styles necessary to make a border radius. This is usually
 * handled by stylesheets but for the dynamic options we need to be able to 
 * do it in PHP as well. 
 *
 * @param int $size The radius of the border radius in pixels
 * 
 */
function rf_css_radius( $radius ) {
	
	$radius = '
		-moz-border-radius: '.$radius.';
		-webkit-border-radius: '.$radius.';
		-ie-border-radius: '.$radius.';
		-o-border-radius: '.$radius.';
		border-radius: '.$radius.'; 
	';

	return $radius;
}

/****************************************************************/
/*                         Shortcodes                           */
/****************************************************************/

include( 'shortcodes.php' );


/****************************************************************/
/*                        Post Types                            */
/****************************************************************/

include( 'post_types.php' );
include( 'product_builder/product_builder.php' );


/****************************************************************/
/*                          Controls                            */
/****************************************************************/

if( is_admin() ) {
	global $additional_theme_options, $additional_customfields;
	include( 'controls.class.php' );
	
	include( 'controlpanel.class.php' );
	$controlpanel = new rf_ControlPanel( $additional_theme_options );

	if ( in_array( basename( $_SERVER['SCRIPT_FILENAME'] ), array( 'post-new.php', 'post.php' ) ) ) {	
		include( 'customfields.class.php' );
		$customfields = new rf_CustomFields( $additional_customfields );
	}

}

?>