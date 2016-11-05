<?php
/**
 * Admin Custom Fields File
 *
 * This file contains a class which extends the controls class. 
 * This generates the custom fields panel in the post pages
 *
 */
 
class rf_CustomFields extends rf_Controls {
	
	/****************************************************************/
	/*                           Class Setup                        */
	/****************************************************************/
	
	var $post_types;
	var $post_id;
	var $post_url;
	var $page_template;
	var $page_status;
	var $options;
	var $postmeta;
	var $shown_sections; 

	/**
	 * Class Constructor
	 *
	 * Some required actions are hooked into WordPress, the variables are
	 * populated and other actions take place depending on the state
	 *
	 */	
	function __construct( $additional_customfields = false) {	
				
		// Add all hooks needed
		add_action( 'admin_menu', array( &$this, 'create_custom_fields' ) );
		add_action( 'save_post', array( &$this, 'save_custom_fields' ), 1, 2 );
		add_action( 'do_meta_boxes', array( &$this, 'remove_default_fields' ), 10, 3 );
		

		// Populate our variables
		$this->post_types      = $this->set_post_types();
		$this->post_id         = $this->get_post_id();
		$this->post_url        = $this->get_post_url(); 
		$this->page_template   = $this->get_page_template();
		$this->page_status     = $this->get_page_status();
		$this->options         = $this->set_options( $additional_customfields );
		$this->shown_sections  = $this->set_shown_sections();
		$this->sections        = $this->set_sections();
		$this->postmeta        = get_postmeta($this->post_id);

		if ( $this->is_customfield_page() ) {
			add_action( 'admin_print_styles', array( &$this, 'common_styles' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'common_scripts' ) );

		}
		
		add_action( 'admin_print_styles', array( &$this, 'customfields_styles' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'customfields_scripts' ) );
				

	}

	
	/**
	 * Remove default custom fields
	 *
	 * @param string $type The type of page we are on
	 * @param string $context The context in which the fields are
	 * @param object $post The WordPress post object
	 *
	 * @uses is_customfield_page()
	 *
	 */	
	function remove_default_fields( $type, $context, $post ) {
		if( $this->is_customfield_page() ) {
			foreach ( array( 'normal', 'advanced', 'side' ) as $context ) {
				remove_meta_box( 'postcustom', 'post', $context );
				remove_meta_box( 'postcustom', 'page', $context );
				remove_meta_box( 'postcustom', 'rf_product', $context );
			}
		}
	}

	/**
	 * Adds our custom fields meta box
	 *
	 */	
	function create_custom_fields() {
		foreach( $this->post_types as $post_type ) {
			add_meta_box( 'rf-custom-fields', 'Additional settings', array( &$this, 'show_customfields' ), $post_type, 'normal', 'high' );
		}

	}
		
	
	/**
	 * Script Loader
	 *
	 * This function registers and enqueues the needed scripts. The common scripts
	 * are loaded by the parent class
	 *
	 */		
	function customfields_scripts() {
		if($this->is_customfield_page()) {
		
		}
	}

	/**
	 * Style Loader
	 *
	 * This function registers and enqueues the needed styles. The common styles
	 * are loaded by the parent class
	 *
	 * @uses is_customfield_page()
	 *
	 */		
	function customfields_styles() {
		if( $this->is_customfield_page() ) {
		}
	}


	/**
	 * Is Custom Field Page
	 *
	 * This function determines weather we are on a page which needs our custom fields
	 * This is mostly used to determine weather we should load the scripts or not 
	 *
	 */	
	function is_customfield_page() {
		if( ( isset( $_GET['post'] ) AND !empty( $_GET['post'] ) AND in_array( get_post_type( $_GET['post'] ), $this->post_types ) AND is_admin() ) OR basename( $_SERVER['SCRIPT_FILENAME'] ) == 'post-new.php' ) {
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
	function set_options( $additional_customfields = false ) {
		global $theme_options;
		
		// Variables for the options
		$sidebar_position = ucfirst( $theme_options['rfoption_sidebar_position'] );
		
		global $wp_registered_sidebars;
		$sidebars = array();
		foreach( $wp_registered_sidebars as $sidebar ) {
			$sidebars[$sidebar['name']] = $sidebar['name'];
		} 
		$options = array(
			'page_structure' => array(
				'slug'    => 'page_structure',
				'name'    => 'Page Structure',
				'template' => array(),
				'template_exclude' => array('template-productpage.php', 'template-gallery.php'),
				'options' => array(
					'title' => array(
						'type'        => 'title',
						'name'        => 'Page Structure',
						'description' => 'Modify the general structure of this page only',
					),
					'rfpostoption_sidebar_position' => array(
						'type'         => 'select',
						'name'         => 'Sidebar Position', 
						'slug'         => 'rfpostoption_sidebar_position',
						'description'  => 'The sidebar position for this page only',
						'example'      => $this->post_url,
						'options'      => array( 'left' => 'Left', 'right' => 'Right', 'default' => 'Global Default - ' . $sidebar_position ),
						'default'      => 'default'
					),
					'rfpostoption_sidebar_custom' => array(
						'type'          => 'select',
						'name'          => 'Custom Sidebar', 
						'slug'          => 'rfpostoption_sidebar_custom',
						'description'   => 'Select a sidebar to use for this page',
						'options'       => $sidebars,
						'example'       => 'http://tastique.org/redfactory/sidebars.mp4',
						'example_video' => 'sidebars',
						'example_text'  => 'view sidebars tutorial',
						'default'       => 'sidebar'
					),
					'rfpostoption_sidebar_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Disable Sidebar', 
						'slug'         => 'rfpostoption_sidebar_disable',
						'description'  => 'Disable the sidebar for this page only',
						'example'      => $this->post_url,
						'default'      => ''
					)
				)
			),

			'slide_settings' => array(
				'slug'    => 'slide_settings',
				'name'    => 'Slide Settings',
				'template' => array( 'rf_slide' ),
				'options' => array(
					'title' => array(
						'type'        => 'title',
						'name'        => 'Slide Settings',
						'description' => 'Change display options for this slide',
					),
					'rfpostoption_slider_title_hide' => array(
						'type'         => 'checkbox',
						'name'         => 'Remove Slide Title', 
						'slug'         => 'rfpostoption_slider_title_hide',
						'description'  => 'Hide the title on this slide',
						'default'      => ''
					),
				)
			),
			'post_content' => array(
				'slug'    => 'post_content',
				'name'    => 'Post Content',
				'template' => array( 'post' ),
				'options' => array(
					'title' => array(
						'type'        => 'title',
						'name'        => 'Post Content',
						'description' => 'Modify elements which are shown on this post',
					),
					'rfpostoption_meta_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Remove Post Meta', 
						'slug'         => 'rfpostoption_meta_disable',
						'description'  => 'Hide the post metadata on this post\'s page',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_thumbnail_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Remove Featured Image', 
						'slug'         => 'rfpostoption_thumbnail_disable',
						'description'  => 'Hide the featured image on this post\'s page',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_title_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Remove Title', 
						'slug'         => 'rfpostoption_title_disable',
						'description'  => 'Hide the title on this post\'s page',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_authorbox_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Remove Author Box', 
						'slug'         => 'rfpostoption_authorbox_disable',
						'description'  => 'If a description is available for an author it is shown below the post. Check this box to never show this box on this post',
						'example'      => $this->post_url,
						'default'      => ''
					)
				)
			),
			'page_content' => array(
				'slug'    => 'page_content',
				'name'    => 'Page Content',
				'template' => array( 'default' ),
				'options' => array(
					'title' => array(
						'type'        => 'title',
						'name'        => 'Post Content',
						'description' => 'Modify elements which are shown on this post',
					),
					'rfpostoption_thumbnail_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Remove Featured Image', 
						'slug'         => 'rfpostoption_thumbnail_disable',
						'description'  => 'Hide the featured image on this page\'s page',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_title_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Remove Title', 
						'slug'         => 'rfpostoption_title_disable',
						'description'  => 'Hide the title on this page\'s page',
						'example'      => $this->post_url,
						'default'      => ''
					)
				)
			),
			'product_settings' => array(
				'slug'     => 'product_settings',
				'name'     => 'Product Page Settings',
				'template' => array( 'template-productpage.php' ),
				'options'  => array(
					'title' => array(
						'type'        => 'title',
						'name'        => 'Product Page Settings',
						'description' => 'Modify how the product page is displayed',
					),
					'rfpostoption_title_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Disable Product Page Title', 
						'slug'         => 'rfpostoption_title_disable',
						'description'  => 'If set, the title of the product page will be removed',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_description_show' => array(
						'type'         => 'checkbox',
						'name'         => 'Show Product Page Content', 
						'slug'         => 'rfpostoption_description_show',
						'description'  => 'If set, the content of the product page will be shown above the posts',
						'example'      => $this->post_url,
						'default'      => ''
					),
				),
			),
			'gallery_settings' => array(
				'slug'     => 'gallery_settings',
				'name'     => 'Gallery Settings',
				'template' => array( 'template-gallery.php' ),
				'options'  => array(
					'title' => array(
						'type'        => 'title',
						'name'        => 'Post Content',
						'description' => 'Modify elements which are shown on this post',
					),
					'rfpostoption_categories' => array(
						'type'         => 'multicategory',
						'name'         => 'Categories', 
						'slug'         => 'rfpostoption_categories',
						'taxonomy'	   => 'category',
						'description'  => 'Select the categories you\'d like to feature in this gallery',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_categories_include_exclude' => array(
						'type'         => 'radio',
						'name'         => 'Exclude or Include?', 
						'slug'         => 'rfpostoption_categories_include_exclude',
						'description'  => 'Select weather you want to show all categories and exclude the ones selected above, or only show the selected categories',
						'options'      => array( 'include' => 'Include the selected categories', 'exclude' => 'Exclude the selected categories' ),
						'example'      => $this->post_url,
						'default'      => 'include'
					),
					'rfpostoption_title_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Disable Gallery Page Title', 
						'slug'         => 'rfpostoption_title_disable',
						'description'  => 'If set, the title of the gallery will be removed',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_description_show' => array(
						'type'         => 'checkbox',
						'name'         => 'Show Gallery Page Content', 
						'slug'         => 'rfpostoption_description_show',
						'description'  => 'If set, the content of the gallery will be shown above the posts',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_filter_hide' => array(
						'type'         => 'checkbox',
						'name'         => 'Hide Gallery Filter', 
						'slug'         => 'rfpostoption_filter_hide',
						'description'  => 'If set, the filter links will be hidden',
						'default'      => ''
					),
					'rfpostoption_columns' => array(
						'type'         => 'select',
						'name'         => 'Gallery Columns', 
						'slug'         => 'rfpostoption_columns',
						'options'	   => array( 2 => '2', 3 => '3', 4 => '4'),
						'description'  => 'Select how many columns you\'d like the gallery to use',
						'default'      => '4'
					),
					'rfpostoption_galler_numberposts' => array(
						'type'         => 'text',
						'name'         => 'Gallery Items To Show', 
						'slug'         => 'rfpostoption_gallery_numberposts',
						'description'  => 'Maximum number of items to show (-1 for no limit)',
						'default'      => '-1',
						'required'    => true
					),
					'rfpostoption_gallery_cycle' => array(
						'type'         => 'checkbox',
						'name'         => 'Cycle Through Items?', 
						'slug'         => 'rfpostoption_gallery_cycle',
						'description'  => 'If checked the content of each item will be cycled through',
						'default'      => 'yes',
						'required'     => true,
					),
					'rfpostoption_gallery_cycle_random' => array(
						'type'         => 'checkbox',
						'name'         => 'Random Order', 
						'slug'         => 'rfpostoption_gallery_cycle_random',
						'description'  => 'If checked the order of the items will become random',
						'default'      => 'no',
						'required'     => true,
					),
					'rfpostoption_gallery_cycle_wait' => array(
						'type'         => 'text',
						'name'         => 'Time spent on each item', 
						'slug'         => 'rfpostoption_gallery_cycle_wait',
						'description'  => 'The number of miliseconds to display each item for',
						'default'      => '4500',
						'required'     => true
					),
					'rfpostoption_gallery_cycle_fadespeed' => array(
						'type'         => 'text',
						'name'         => 'Item Fade Speed', 
						'slug'         => 'rfpostoption_gallery_cycle_fadespeed',
						'description'  => 'The speed - in miliseconds - with which the item detailss fade in and out',
						'default'      => '1000',
						'required'     => true
					),
					/*
					'rfpostoption_post_thumbnail_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Disable Featured Images', 
						'slug'         => 'rfpostoption_post_thumbnail_disable',
						'description'  => 'Hide the featured image for items in this gallery',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_post_title_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Disable Post Titles', 
						'slug'         => 'rfpostoption_post_title_disable',
						'description'  => 'Hide the title for items in this gallery',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_post_date_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Disable Post Dates', 
						'slug'         => 'rfpostoption_post_date_disable',
						'description'  => 'Hide the date for items in this gallery',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_post_content_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Disable Post Content', 
						'slug'         => 'rfpostoption_post_content_disable',
						'description'  => 'Hide the content for items in this gallery',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_post_readmore_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Disable Read More', 
						'slug'         => 'rfpostoption_post_readmore_disable',
						'description'  => 'Hide the read more link for items in this gallery',
						'example'      => $this->post_url,
						'default'      => ''
					),
					*/
				)
			),
			'mashup_settings' => array(
				'slug'     => 'mashup_settings',
				'name'     => 'Mashup Settings',
				'template' => array( 'template-mashup.php' ),
				'options'  => array(
					'title' => array(
						'type'        => 'title',
						'name'        => 'Post Content',
						'description' => 'Modify elements which are shown on this post',
					),
					'rfpostoption_pages_include' => array(
						'type'         => 'multipage',
						'name'         => 'Pages to Include', 
						'slug'         => 'rfpostoption_pages_include',
						'description'  => 'Select the pages you\'d like to include in this mashup. Once selected you can rearrange the list to modify the order of the pages shown',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_title_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Disable Mashup Title', 
						'slug'         => 'rfpostoption_title_disable',
						'description'  => 'If set, the title of this page will be hidden',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_description_show' => array(
						'type'         => 'checkbox',
						'name'         => 'Show Mashup Content', 
						'slug'         => 'rfpostoption_description_show',
						'description'  => 'If set, the content of this page will be shown above the mashup',
						'example'      => $this->post_url,
						'default'      => ''
					),
				)
			),
			'postlist_settings' => array(
				'slug'     => 'postlist_settings',
				'name'     => 'Post List Settings',
				'template' => array( 'template-postlist.php' ),
				'options'  => array(
					'title' => array(
						'type'        => 'title',
						'name'        => 'Post List Settings',
						'description' => 'Define which posts are shown on this page',
					),
					'rfpostoption_categories' => array(
						'type'         => 'multicategory',
						'name'         => 'Categories', 
						'slug'         => 'rfpostoption_categories',
						'taxonomy'	   => 'category',
						'description'  => 'Select some categories',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_posts_per_page' => array(
						'type'         => 'text',
						'name'         => 'Posts Per Page', 
						'slug'         => 'rfpostoption_posts_per_page',
						'description'  => 'Set how many posts are shown on each page',
						'default'      => get_option('posts_per_page'),
					),			
					'rfpostoption_categories_include_exclude' => array(
						'type'         => 'radio',
						'name'         => 'Exclude or Include?', 
						'slug'         => 'rfpostoption_categories_include_exclude',
						'description'  => 'Select weather you want to show all categories and exclude the ones selected above, or only show the selected categories',
						'options'      => array( 'include' => 'Include the selected categories', 'exclude' => 'Exclude the selected categories' ),
						'example'      => $this->post_url,
						'default'      => 'include'
					),
					'rfpostoption_postlayout' => array(
						'type'         => 'fileselect',
						'name'         => 'Post Layout', 
						'slug'         => 'rfpostoption_postlayout',
						'description'  => 'Choose the layout for the posts listed on this page',
						'folder'	   => get_template_directory()  . '/layouts/',
						'default'      => 'default'
					),
					'rfpostoption_title_disable' => array(
						'type'         => 'checkbox',
						'name'         => 'Disable Page Title', 
						'slug'         => 'rfpostoption_title_disable',
						'description'  => 'If set, the title of this page will be hidden',
						'example'      => $this->post_url,
						'default'      => ''
					),
					'rfpostoption_description_show' => array(
						'type'         => 'checkbox',
						'name'         => 'Show Page Content', 
						'slug'         => 'rfpostoption_description_show',
						'description'  => 'If set, the content of this page will be shown above the posts listed',
						'example'      => $this->post_url,
						'default'      => ''
					),
				)
			),
			
		);
	
		if( isset( $additional_customfields ) AND !empty( $additional_customfields ) ) {
			foreach( $additional_customfields as $section_name => $section ) {

				if( !isset( $options[$section_name] ) ) {
					$options[$section_name] = $section;
				}
				elseif( isset( $options[$section_name] ) AND isset( $section ) AND empty( $section ) ) {
					unset( $options[$section_name] );
				}
				else {
	
					foreach( $section['options'] as $name => $option ) {
						if( !isset($option) OR empty( $option ) ) {
							unset($options[$section_name]['options'][$name]);
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
		
	
	function set_post_types() {
		global $custom_post_customfields;
		$custom_post_customfields = ( isset( $custom_post_customfields ) AND !empty( $custom_post_customfields ) ) ? $custom_post_customfields : array();
		$post_types = array_merge( array('post', 'page', 'rf_product'), $custom_post_customfields);
		return $post_types;
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
		if( isset( $this->postmeta[$option['slug']] ) AND !empty( $this->postmeta[$option['slug']] ) ) {
			$value = $this->postmeta[$option['slug']];
		}
		else {
			$value = $option['default'];
		}
		
		return stripslashes( $value );
	}
	
	
	/**
	 * Current post's ID 
	 *
	 * @return int|bool False if not a post, the ID if we are on a post
     *
	 */		
	function get_post_id() {
		if( isset( $_GET['post'] ) AND !empty( $_GET['post'] ) ) {
			return $_GET['post'];
		}	
		else {
			return false;
		}
	}

	/**
	 * Current post's URL
	 *
	 * @return string|bool False if not a post, the URL if we are on a post
     *
	 */			
	function get_post_url() {
		if( $this->post_id !== false ) {
			return get_permalink( $this->post_id );
		}
		else {
			return false;
		}
	}

	/**
	 * The page template for the current post
	 *
	 * @return string|bool False if not a post or there is no template, otherwise the template
     *
	 */			
	function get_page_template() {
		if( $this->post_id !== false ) {
			$template = get_post_meta( $this->post_id, '_wp_page_template', true );
			if( !isset( $template ) OR empty( $template ) ) {
				$template = get_post_type($this->post_id);
			}
			return $template;
		}
		elseif ( $this->post_id == false AND isset( $_GET['post_type']) AND !empty( $_GET['post_type'] ) )  {
			return $_GET['post_type'];
		}
		else {
			return false;
		}
	}

	/**
	 * Current post's status 
	 *
	 * @return string|bool False if not a post, the status if we are on a post
     *
	 */			
	function get_page_status() {
		if($this->post_id !== false) {
			return get_post_status( $this->post_id );
		}
		else {
			return false;
		}	
	}
	
	/**
	 * Find the sections we need to show 
	 *
	 * @return array $sections A list of sections we need to show
     *
	 */				
	function set_shown_sections() {
		$sections = array();
		foreach( $this->options as $name => $section ) {
			$show = true;
			if(isset( $section['template'] ) AND !empty( $section['template'] ) ) {
				if( ( isset( $this->page_template ) AND !empty( $this->page_template ) AND !in_array( $this->page_template, $section['template'] ) ) OR ( !isset( $this->page_template ) OR empty( $this->page_template ) ) ) {
					$show = false;
				}		
			}
			if( isset( $section['template_exclude'] ) AND is_array( $section['template_exclude'] ) ) {				
				if( in_array( $this->page_template, $section['template_exclude'] ) ) {
					$show = false;
				}
			}

			if($show === true) {
				$sections[] = $name;
			}
			
			
				
		}
		
		
		return $sections;
	}
	

	/****************************************************************/
	/*                        Saving Fields                         */
	/****************************************************************/		

	/**
	 * Save the custom field data
     *
	 */		
	function save_custom_fields( $post_id, $post ) {

		if ( isset( $_POST ) AND !empty( $_POST ) ) {
			if (!isset( $_POST['rf-custom-fields_wpnonce'] ) OR !wp_verify_nonce( $_POST[ 'rf-custom-fields_wpnonce' ], 'rf-custom-fields' ) )
				return;
			if ( !current_user_can( 'edit_post', $post_id ) )
				return;
			foreach ( $this->options as $sections ) {
				foreach( $sections['options'] as $option ) {
					if(isset( $option['slug'] ) AND !empty($option['slug'] ) ) {
						if(isset( $_POST[$option['slug']] ) AND !empty( $_POST[$option['slug']] ) ) {
							update_post_meta( $post_id, $option['slug'], $_POST[$option['slug']] );
						}
						else {
							delete_post_meta( $post_id, $option['slug'] );
						}
					}
				}
			}
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
	
			if( in_array( $section['slug'], $this->shown_sections ) ) {
				$classActive = ( $i == 0 ) ? 'active' : '';
				$menu .= '<li><a class="button ' . $classActive . '" href="#' . $section['slug'] . '">' . $section['name'] . '</a></li>';
			}
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
			if( in_array( $name, $this->shown_sections ) ) {
				echo '<div class="container" id="' . $section['slug'] . '">';
				foreach( $section['options'] as $option ) {
					echo '<div class="row">';
					if( method_exists( $this, 'build_element_' . $option['type'] ) ) {
						call_user_func( array( $this, 'build_element_' . $option['type'] ), $option, $name );				
					}
					else {
						call_user_func( 'rf_build_element_' . $option['type'] );
					}
					echo '</div>';
				}
				echo '</div>';
			}
		}
	}

	/****************************************************************/
	/*                        Display Methods                       */
	/****************************************************************/	

	/**
	 * Display Custom Fields 
	 *
	 * This function is responsible for displaying all the custom fields
	 * on the edit post pages
     *
     * @uses rf_CustomFields::build_menu()
     * @uses rf_CustomFields::build_sections()
     *
	 */		
	function show_customfields() {
		echo '<div class="rf_wrapper" id="rf_customfields">';
		echo $this->build_menu();
		echo '<div class="rf_controls">';
		wp_nonce_field( 'rf-custom-fields', 'rf-custom-fields_wpnonce', false, true );
		echo $this->build_sections();	
		echo '</div>';		
		echo '</div>';
	}
			
}



?>