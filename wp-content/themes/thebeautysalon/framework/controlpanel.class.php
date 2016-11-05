<?php
/**
 * Admin Controlpanel File
 *
 * This file contains a class which extends the controls class. 
 * This generates the control panel in the Theme Settings
 *
 */
 
class rf_ControlPanel extends rf_Controls {
	
	/****************************************************************/
	/*                           Class Setup                        */
	/****************************************************************/

	
	var $option_name;
	var $options;
	var $theme_options; 
	var $new_options;
	var $sections;
	var $cp_url;
	var $required_options;

	/**
	 * Class Constructor
	 *
	 * Some required actions are hooked into WordPress, the variables are
	 * populated and other actions take place depending on the state
	 * 
	 * @uses rf_ControlPanel::set_options()
	 * @uses rf_ControlPanel::set_sections()
	 * @uses rf_ControlPanel::set_required_options()
	 * @uses rf_ControlPanel::set_cp_url()
	 * @uses rf_ControlPanel::get_defaults()
	 * @uses rf_ControlPanel::get_new_options()
	 * @uses rf_ControlPanel::save_new_options()
	 *
	 */	
	function __construct( $additional_theme_options = false ) {	
		
		// Add all hooks needed
		add_action( 'admin_menu', array( &$this, 'controlpanel_menu' ) );
		
		if ( $this->is_controlpanel_page() ) {
			add_action( 'admin_print_styles', array( &$this, 'common_styles' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'common_scripts' ) );
		}
		
		add_action( 'admin_print_styles', array( &$this, 'controlpanel_styles' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'controlpanel_scripts' ) );

		// Populate our variables
		$this->options             = $this->set_options( $additional_theme_options );
		$this->sections            = $this->set_sections();
		$this->required_options    = $this->set_required_options();
		$this->cp_url              = $this->set_cp_url();
		$this->option_name         = 'rf_options_' . THEMENAME;
		
		// Create/Load options
		$this->theme_options = get_option('rf_options_' . THEMENAME);
		if($this->theme_options === false) {
			$new_options = $this->get_defaults(); 
			add_option( $this->option_name, $new_options );
		}
		
		// New Option Values
		$this->new_options = $this->get_new_options();
		
		// Save New Options
		$this->save_new_options();

	}	
	
	/**
	 * Theme Settings Menu
	 *
	 * This adds the menu entry for the Theme Settings page
	 *
	 */		
	function controlpanel_menu() {
	  	add_theme_page( 'Theme Settings', 'Theme Settings', 'manage_options', 'rf_controlpanel', array( &$this, 'show_controlpanel' ) );
	}
	
	/**
	 * Script Loader
	 *
	 * This function registers and enqueues the needed scripts 
	 *
	 */		
	function controlpanel_scripts() {
		if( $this->is_controlpanel_page() ) {
		}
	}
	
	/**
	 * Style Loader
	 *
	 * This function registers and enqueues the needed styles 
	 *
	 */		
	function controlpanel_styles() {
		if( $this->is_controlpanel_page() ) {			
		}
	}


	/**
	 * Is Control Panel Page
	 *
	 * This function determines weather we are on a page which needs our custom fields
	 * This is mostly used to determine weather we should load the scripts or not 
	 *
	 */		
	function is_controlpanel_page() {
		if( is_admin() AND isset( $_GET['page'] ) AND $_GET['page'] == 'rf_controlpanel' ) {
			return true;
		}
		else {
			return false;
		}
	}
	
		
	/****************************************************************/
	/*                     Getters and Setters                      */
	/****************************************************************/


	/**
	 * Options Setup
	 *
	 * The options are defined here. A lot of data is added to each to ensure
	 * flexibility and modular use.  
	 *
	 * @return array $options The array of options and their data 
	 *
	 */				
	function set_options( $additional_theme_options = false ) {
		
		// Variables needed for the options array
		
		$blog_page_uri = get_option( 'page_for_posts' ); $blog_page_uri = ( !empty( $blog_page_uri ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url();
		
		if( !defined( 'RF_PRIMARY_COLOR' ) ) {
			define( 'RF_PRIMARY_COLOR', '#0A749E' );
		}
		
		if( !defined( 'RF_SECONDARY_COLOR' ) ) {
			define( 'RF_SECONDARY_COLOR', '#d73539' );
		}
		
		global $wp_registered_sidebars;
		$sidebars = array();
		foreach( $wp_registered_sidebars as $sidebar ) {
			$sidebars[$sidebar['name']] = $sidebar['name'];
		} 
				
		$options = array(
			'general' => array(
				'slug'    => 'general',
				'name'    => 'General',
				'options' => array(
					'title' => array(
						'type' => 'title',
						'name' => 'General',
						'description' => 'General options for this theme',
					),
					'rfoption_sidebar_position' => array(
						'type'         => 'select',
						'name'         => 'Sidebar Position', 
						'slug'         => 'rfoption_sidebar_position',
						'description'  => 'The default position of the sidebar (this can be modified on each post/page)',
						'example'      => $blog_page_uri,
						'options'      => array( 'left' => 'Left', 'right' => 'Right' ),
						'default'      => 'left',
						'required'	   => true
					),
					'rfoption_sidebars_custom' => array(
						'type'         => 'text',
						'name'         => 'Custom Sidebars', 
						'slug'         => 'rfoption_sidebars_custom',
						'description'  => 'Create additional sidebars for your website',
						'example'      => 'http://tastique.org/redfactory/sidebars.mp4',
						'example_video' => 'sidebars',
						'example_text' => 'view sidebars tutorial',
						'default'      => ''
					),
					'rfoption_footer_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Disable Footer', 
						'slug'         => 'rfoption_footer_disable',
						'description'  => 'Hide the footer from view completely',
						'default'      => ''
					),	
					'rfoption_title_post' => array(
						'type'         => 'text',
						'name'         => 'Post Top Titles', 
						'slug'         => 'rfoption_title_post',
						'description'  => 'Enter the title to show on the very top of each single post page (under the breadcrumb)',
						'default'      => 'Our Blog',
						'required'     => true
					),				
					'rfoption_readmore_text' => array(
						'type'         => 'text',
						'name'         => 'Read More Text', 
						'slug'         => 'rfoption_readmore_text',
						'description'  => 'The text shown on "read more" links',
						'example'      => get_permalink( get_option( 'page_for_posts' ) ),
						'default'      => 'read more',
						'required'     => true
					),
					'rfoption_footer_text' => array(
						'type'         => 'text',
						'name'         => 'Footer Text', 
						'slug'         => 'rfoption_footer_text',
						'description'  => 'The text shown on "read more" links',
						'default'      => 'Copyright &copy; ' . date( 'Y' ) . ' The Beauty Salon - Made by Red Factory',
						'required'     => true
					),
					'rfoption_error_404_title' => array(
						'type'         => 'text',
						'name'         => '404 Error Title', 
						'slug'         => 'rfoption_error_404_title',
						'description'  => 'The title displayed on a 404 error page',
						'example'      => home_url() . '/hur2838247r8hrr82r/',
						'default'      => 'Something seems to be wrong!',
						'required'     => true
					),
					'rfoption_error_404_message' => array(
						'type'         => 'textarea',
						'name'         => '404 Error Message', 
						'slug'         => 'rfoption_error_404_message',
						'description'  => 'The text displayed under the 404 error title',
						'example'      => home_url() . '/hur2838247r8hrr82r/',
						'default'      => 'You have run into a 404 error which means that there is no content on this page',
						'required'     => true,
					),
					'rfoption_no_searchresults_title' => array(
						'type'         => 'text',
						'name'         => 'No Search Results Title', 
						'slug'         => 'rfoption_no_searchresults_title',
						'description'  => 'The title shown when there are no search results on a search page',
						'example'      => home_url() . '?s=2328h829292hdhdhUUd8322',
						'default'      => 'There were no results for your search',
						'required'	   => true
					),
					'rfoption_no_searchresults_message' => array(
						'type'         => 'textarea',
						'name'         => 'No Search Results Message', 
						'slug'         => 'rfoption_no_searchresults_message',
						'description'  => 'The message shown when there are no search results on a search page',
						'example'      => home_url() . '/hur2838247r8hrr82r/',
						'default'      => 'Why not try a different search?',
						'required'     => true,
					),
					'rfoption_password_protected_message' => array(
						'type'         => 'text',
						'name'         => 'Password Protected Post Message', 
						'slug'         => 'rfoption_password_protected_message',
						'description'  => 'This message is shown to users above the form where they can enter the post password',
						'default'      => 'This post is password protected please enter your password to view it',
						'required'	   => true
					),
					'rfoption_error_noposts_title' => array(
						'type'         => 'text',
						'name'         => 'No Posts Error Title', 
						'slug'         => 'rfoption_error_noposts_title',
						'description'  => 'The title displayed when a page has no posts. This happens in rare circumstances, if there are bugs in the code, or on the main page if you have no posts at all for example. It can also occur if you create a post list which doesn\'t contain posts (no posts in the chosen categories)',
						'default'      => 'There are no posts here!',
						'required'     => true
					),
					'rfoption_error_noposts_message' => array(
						'type'         => 'textarea',
						'name'         => 'No Posts Error Message', 
						'slug'         => 'rfoption_error_noposts_message',
						'description'  => 'The message displayed when a page has no posts. This happens in rare circumstances, if there are bugs in the code, or on the main page if you have no posts at all for example. It can also occur if you create a post list which doesn\'t contain posts (no posts in the chosen categories)',
						'default'      => 'This page should have content, but we seem to have run out. Please check back later, or let us know if you think something is wrong . ',
						'required'     => true,
					),
					'rfoption_analytics_code' => array(
						'type'         => 'textarea',
						'name'         => 'Website Analytics Code', 
						'slug'         => 'rfoption_analytics_code',
						'description'  => 'Paste your website analytics tracking code here',
						'default'      => ''
					)
				)
			),
			'seo' => array(
				'slug'    => 'seo',
				'name'    => 'SEO',
				'options' => array(
					'title' => array(
						'type' => 'title',
						'name' => 'Search Engine Optimization',
						'description' => 'Some options to set up which will make your website more information rich. This information will be used by Google while indexing and also social sites like Facebook and Twitter when people share things from your site',
					),
					'rfoption_meta_title' => array(
						'type'         => 'text',
						'name'         => 'Website Title', 
						'slug'         => 'rfoption_meta_title',
						'description'  => 'Usually the name of your website. It shows up in the browser tab as well as some social services when sharing.',
						'default'      => get_bloginfo( 'name' ),
						'required'     => true
					),
					'rfoption_meta_tagline' => array(
						'type'         => 'text',
						'name'         => 'Website Tagline', 
						'slug'         => 'rfoption_meta_tagline',
						'description'  => 'An additional subtitle for your website, usually displayed in the browser tab on the frontpage and in the header',
						'default'      => get_bloginfo( 'description' ),
						'required'     => true
					),
					'rfoption_meta_description' => array(
						'type'         => 'text',
						'name'         => 'Website Description', 
						'slug'         => 'rfoption_meta_description',
						'description'  => 'A longer description for your website. This will not show up on your website but it will be part of the meta-information. It will also show up on Facebook if visitors like your site. ',
						'default'      => get_bloginfo( 'description' ),
					),
					'rfoption_meta_image' => array(
						'type'         => 'upload',
						'name'         => 'Website Image', 
						'slug'         => 'rfoption_meta_image',
						'description'  => 'The Meta Image is an image associated to a page on your website. Facebook uses this image when users share links. For your posts the featured image is used. The image specified here is used for all pages which don\'t have featured images specified.',
					),
				)
			),
			'design' => array(
				'slug'    => 'design',
				'name'    => 'Design',
				'options' => array(
					'title' => array(
						'type' => 'title',
						'name' => 'Design',
						'description' => 'Change the look and feel of theme elements'
					),
					
					'rfoption_images_logo' => array(
						'type'         => 'upload',
						'name'         => 'Website Logo', 
						'slug'         => 'rfoption_images_logo',
						'description'  => 'This image will be used in the header',
						'example'      => home_url(),
						'default'      => ''
					),
					'rfoption_images_logo_push' => array(
						'type'         => 'text',
						'name'         => 'Logo Displacement', 
						'slug'         => 'rfoption_images_logo_push',
						'description'  => 'In some cases the design of logos may cause them to look out of balance when centered vertically. You can add an offset here to correct the problem',
						'default'      => ''
					),
					'rfoption_images_favicon' => array(
						'type'          => 'upload',
						'name'          => 'Favicon', 
						'slug'          => 'rfoption_images_favicon',
						'description'   => 'This 16x16 .ico file will be shown in browser bars and bookmarks',
						'example'       => 'http://tastique.org/redfactory/favicons.mp4',
						'example_video' => 'favicons',
						'example_text'  => 'view favicons tutorial',
						'default'       => ''
					),
					'rfoption_images_touchicon' => array(
						'type'          => 'upload',
						'name'          => 'Apple Touch Icon', 
						'slug'          => 'rfoption_images_touchicon',
						'description'   => 'This 144x144 .png file will be shown on apple devices on the home screen',
						'example'       => 'http://tastique.org/redfactory/favicons.mp4',
						'example_video' => 'favicons',
						'example_text'  => 'view favicons tutorial',
						'default'       => ''
					),

					'rfoption_colors_primary' => array(
						'type'         => 'color',
						'name'         => 'Primary Website Color', 
						'slug'         => 'rfoption_colors_primary',
						'description'  => 'The primary color of the website',
						'example'      => home_url(),
						'default'      => RF_PRIMARY_COLOR,
						'required'     => true
					),
	

					'rfoption_colors_background_color' => array(
						'type'         => 'color',
						'name'         => 'Background Color', 
						'slug'         => 'rfoption_colors_background_color',
						'description'  => 'The color of the backround',
						'example'      => home_url(),
						'default'      => '#FFFFFF',
						'required'     => true
					),
					'rfoption_colors_background_image' => array(
						'type'         => 'upload',
						'name'         => 'Background Image', 
						'slug'         => 'rfoption_colors_background_image',
						'description'  => 'This image will be tiled and used as a background',
						'example'      => home_url(),
						'default'      => ''
					),
					'rfoption_fonts_heading' => array(
						'type'          => 'text',
						'name'          => 'Heading Font', 
						'slug'          => 'rfoption_fonts_heading',
						'description'   => 'All headings will use the given font',
						'example'       => 'http://tastique.org/redfactory/font_selection.mp4',
						'example_video' => 'font_selection',
						'example_text'  => 'view fonts tutorial',
						'default'       => 'Helvetica Neue, Helvetica, Arial, sans-serif',
						'required'      => true

					),
					'rfoption_fonts_body' => array(
						'type'          => 'text',
						'name'          => 'Body Font', 
						'slug'          => 'rfoption_fonts_body',
						'description'   => 'All non-heading text will use this font',
						'example'       => 'http://tastique.org/redfactory/font_selection.mp4',
						'example_video' => 'font_selection',
						'example_text'  => 'view fonts tutorial',
						'default'       => 'Helvetica Neue, Helvetica, Arial, sans-serif',
						'required'      => true

					),
				)
			),
			'products' => array(
				'slug'    => 'products',
				'name'    => 'Product Settings',
				'options' => array(
					'title' => array(
						'type'        => 'title',
						'name'        => 'Product Settings',
						'description' => 'Modify site-wide product settings',
					),
					'rfoption_title_products' => array(
						'type'         => 'text',
						'name'         => 'Product Top Titles', 
						'slug'         => 'rfoption_title_products',
						'description'  => 'Enter the title to show on the very top of each single product page (under the breadcrumb)',
						'default'      => 'Our Products',
						'required'     => true
					),	
					'rfoption_product_currency' => array(
						'type'         => 'text',
						'name'         => 'Product Currency', 
						'slug'         => 'rfoption_product_currency',
						'description'  => 'This is the currency symbol that will be used on this page. This can be the currency sign or the three letter abbreviation',
						'default'      => '$',
						'required'     => true
					),	
					'rfoption_product_currency_position' => array(
						'type'         => 'radio',
						'name'         => 'Currency Position', 
						'slug'         => 'rfoption_product_currency_position',
						'description'  => 'Select weather the currency symbol or abbreviation should appear before or after the amount',
						'options'      => array('before' => 'Before', 'after' => 'After'),
						'default'      => 'before',
						'required'     => true
					),
					'rfoption_sidebar_custom' => array(
						'type'          => 'select',
						'name'          => 'Custom Sidebar', 
						'slug'          => 'rfoption_sidebar_custom',
						'description'   => 'Select a sidebar to use for all single product pages (this can be overridden in the product specific settings)',
						'options'       => $sidebars,
					),	
					'rfoption_related_product_count' => array(
						'type'         => 'text',
						'name'         => 'Related Products', 
						'slug'         => 'rfoption_related_product_count',
						'description'  => 'Select the number of related products to show on single product post pages. Set to 0 if none, set to -1 to show all',
						'default'      => '3',
						'required'     => true
					),
					'rfoption_related_product_columns' => array(
						'type'         => 'select',
						'name'         => 'Related Product Columns', 
						'slug'         => 'rfoption_related_product_columns',
						'description'  => 'Select the number of columns to use for related products',
						'options'      => array('2' => '2', '3' => '3', '4' => '4'),
						'default'      => '3',
						'required'     => true
					),
					'rfoption_related_product_title' => array(
						'type'         => 'text',
						'name'         => 'Related Products Title', 
						'slug'         => 'rfoption_related_product_title',
						'description'  => 'The title of the related products secion',
						'default'      => 'Related Products',
						'required'     => true
					),					
					'rfoption_images_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Disable Product Images', 
						'slug'         => 'rfoption_images_disable',
						'description'  => 'Disable product images on the product pages',
						'default'      => 'no'
					),
					'rfoption_links_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Disable Links', 
						'slug'         => 'rfoption_links_disable',
						'description'  => 'Disable links on single product pages',
						'default'      => 'no'
					),
				)
			),
			'social' => array(
				'slug'    => 'social',
				'name'    => 'Social Sites',
				'options' => array(
					'title' => array(
						'type' => 'title',
						'name' => 'Social Site Settings ',
						'description' => 'Tie your Social Profiles to your website'
					),
					'rfoption_social_facebook_url' => array(
						'type'          => 'text',
						'name'          => 'Facebook Profile', 
						'slug'          => 'rfoption_social_facebook_url',
						'description'   => 'The URL of your Facebook profile page',
						'default'       => '',
					),
					'rfoption_social_twitter_username' => array(
						'type'          => 'text',
						'name'          => 'Twitter Username', 
						'slug'          => 'rfoption_social_twitter_username',
						'description'   => 'Your Twitter username',
						'default'       => '',
					),
				)
			),
			'rf_documentation' => array(
				'slug'    => 'rf_documentation',
				'name'    => 'Documentation',
				'nosave'  => true,
				'options' => array(
					'rfoption_documentation' => array(
						'type'   => 'documentation',
					),
				)
			),
		);
		
		
			
		if( isset( $additional_theme_options ) AND !empty( $additional_theme_options ) ) {
			foreach( $additional_theme_options as $section_name => $section ) {
				
				if( !isset( $options[$section_name] ) ) {
					$options[$section_name] = $section;
				}
				elseif( isset( $options[$section_name] ) AND isset( $section ) AND empty( $section ) ) {
					unset( $options[$section_name] );
				}
				else {
	
					foreach( $section['options'] as $name => $option ) {
						if( !isset( $option ) OR empty( $option ) ) {
							unset( $options[$section_name]['options'][$name] );
						}
						else {
							$options[$section_name]['options'][] = $option;
						}
					}
				
				}
			}
		}
			
		return $options;
	}

	/**
	 * Control Panel URL
	 *
	 * Finds the URL of the control panel
	 *
	 * @return string $url The url of the control panel
	 *
	 */		
	function set_cp_url() {
		$url = admin_url( 'themes.php?page=rf_controlpanel' );
		return $url;
	}



	/**
	 * Retrieve Option Value
	 *
	 * Retrieves the option value or gives the default if no value exists
	 *
	 * @param array $option The details of the option
	 * @param $section strong The name of the section the option is in
	 * 
	 * @return string $value The value of the option in question
	 *
	 */	
	function get_value( $option, $section ) {
		$required = array_keys( $this->required_options );
		
		if (
			isset( $option['slug'] )
			AND ( !isset( $this->theme_options[$option['slug']] ) OR empty( $this->theme_options[$option['slug']] ) )
			AND isset( $option['default'] ) 
			AND !empty( $option['default'])
			AND in_array( $option['slug'], $required )
			) {
				$value = $option['default'];
		}
		elseif (isset( $this->theme_options[$option['slug']] ) AND !empty( $this->theme_options[$option['slug']] ) ) {
			$value = $this->theme_options[$option['slug']];
		}
		else {
			$value = '';
		}
		return ( $value );
	}

	/**
	 * Get New Options 
	 *
	 * Retrieves the list of new options. If an option value is not set
	 * it makes sure required options receive their defaut value
	 *
	 * @return bool|array Returns the new options if any, or false if none
	 *
	 */		
	function get_new_options() {
		$required = array_keys( $this->required_options );
		if( isset( $_POST ) AND !empty( $_POST ) ) {
			$new_options = $_POST;
			
			foreach( $new_options as $key => $value ) {
				if(substr_count( $key, 'rfoption_' ) == 0) {
					unset( $new_options[$key] );
				}
				else {
					if( in_array( $key, $required ) AND empty( $value ) ) {
						$new_options[$key] = $this->required_options[$key];
					}
				}
								
			}
			
			$empties = array_diff_key( $this->theme_options, $new_options );

			foreach ( $empties as $key => $value ) {
				if(substr_count( $key, 'rfoption_' ) == 0) {
					unset( $new_options[$key] );
				}
				if( in_array( $key, $required ) ) {
					$new_options[$key] = $this->required_options[$key];
				}
				else {
					$new_options[$key] = '';
				}
			}
		
			$new_options = wp_parse_args( $new_options, $this->theme_options );
			
			foreach ( $new_options as $key => $value ) {
				if( is_string( $value ) ) {
					$new_options[$key] =  $value ;
				}
			}
			
			return $new_options;
		}
		else {
			return false;
		}
	}

	/**
	 * Set Required Options 
	 *
	 * Retrieves a list of all the required options
	 *
	 * @return array $required A list of required options and their default values
     *
	 */	
	function set_required_options() {
		$required = array();
		foreach( $this->options as $section ) {
			foreach( $section['options'] as $option ) {
				if( isset($option['required'] ) AND $option['required'] === true ) {
					$required[$option['slug']] = $option['default'];
				}
			}
		}
		
		return $required;
	}
	

	/****************************************************************/
	/*                        Saving Options                        */
	/****************************************************************/		

	/**
	 * Save Options 
	 *
	 * Saves the options to the database
	 *
	 */		
	function save_new_options(){	
		if( isset( $_GET['page'] ) AND $_GET['page'] == 'rf_controlpanel' ) {	
			if( isset( $this->new_options ) AND !empty( $this->new_options ) ) {
				update_option( $this->option_name, $this->new_options );
			}
		
			$this->theme_options = get_option( 'rf_options_' . THEMENAME );
		}
	}
	
	/****************************************************************/
	/*                        Builder Methods                       */
	/****************************************************************/	


	/**
	 * Menu Builder 
	 *
	 * Builds the tabbed menu used to navigate through option sections
	 *
	 * @return string $menu The HTML code for the tabbed menu
     *
	 */		
	function build_menu() {
		$menu = '<ul class="menu">'; 
		$i = 0; 
		foreach ( $this->sections as $section ) {
			$classActive = ( $i == 0 ) ? 'active' : '';
			$menu .= '<li><a class="button ' . $classActive . '" href="#' . $section['slug'].'">' . $section['name'] . '</a></li>';
			$i++;
		}
		$menu .= '<div class="clear"></div></ul>';
		
		return $menu;
	}

	/**
	 * Section Builder 
	 *
	 * Builds the option sections. It calls a function for each control type, so if
	 * a new control is needed it must be added to the controls class
     *
	 */		
	function build_sections() {
		foreach( $this->options as $name => $section ) {
			echo '<div class="container" id="' . $section['slug'] . '">';
			foreach( $section['options'] as $option ) {
				echo "<div class='row'>";
					if( method_exists( $this, 'build_element_' . $option['type'] ) ) {
						call_user_func( array( $this, 'build_element_' . $option['type'] ), $option, $name );				
					}
					else {
						call_user_func( 'rf_build_element_' . $option['type'] );
					}
				echo '</div>';
			}
			
			if ( !isset( $section['nosave'] ) OR  $section['nosave'] != true ) {
				echo '<input type="submit" value="Save Changes">';
			}
			
			echo '</div>';
		}
	}

	/****************************************************************/
	/*                        Display Methods                       */
	/****************************************************************/	
	
	/**
	 * Show Control Panel 
	 *
	 * This function shows the whole control panel
	 *
	 * @uses rf_ControlPanel::build_menu()
	 * @uses rf_ControlPanel::build_sections()
	 *
	 */			
	function show_controlpanel() {
		echo '<div class="wrap rf_wrapper" id="rf_controlpanel">';
			echo '<div id="icon-themes" class="icon32"><br /></div>';
			echo '<h2>Theme Settings</h2>';
			echo $this->build_menu();
			
			echo '<form class="rf_controls" method="post" action="">';
				echo $this->build_sections();
			echo '</form>';
			
			
		echo '</div>';
	}
			
}



?>