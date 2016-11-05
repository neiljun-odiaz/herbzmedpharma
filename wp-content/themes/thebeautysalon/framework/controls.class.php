<?php
/**
 * Admin Controls File
 *
 * This file contains a class which builds some common controllers. 
 * The sole purpose of this class is to create these controllers, 
 * they are used by sub classes such as the control panel and 
 * custom fields. 
 *
 */
class rf_Controls {
	
	/****************************************************************/
	/*                           Class Setup                        */
	/****************************************************************/
	
	/**
	 * Class Constructor
	 *
	 */
 
	function __construct() {	

	}	

	/**
	 * Script Loader
	 *
	 * This function registers and enqueues the needed scripts 
	 *
	 */		
	function common_scripts() {
		wp_register_script( 'jquery-formalize', get_template_directory_uri() . '/framework/js/jquery.formalize.min.js', array( 'jquery' ) );
		wp_register_script( 'jquery-asmselect', get_template_directory_uri() . '/framework/js/jquery.asmselect.min.js', array( 'jquery', 'jquery-ui-sortable' ) );
		wp_register_script( 'jquery-colorpicker', get_template_directory_uri() . '/framework/js/colorpicker.min.js', array( 'jquery' ) );
		wp_register_script( 'jquery-colorbox', get_template_directory_uri() . '/framework/js/jquery.colorbox.min.js', array( 'jquery' ) );
		wp_register_script( 'html5media', 'http://api.html5media.info/1.1.4/html5media.min.js' );
		wp_register_script( 'rf-controls', get_template_directory_uri() . '/framework/js/rf_controls.js', array( 'jquery' ) );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'rf-controls' );
		wp_enqueue_script( 'jquery-asmselect' );
		wp_enqueue_script( 'jquery-colorpicker' );
		wp_enqueue_script( 'jquery-formalize' );
		wp_enqueue_script( 'jquery-colorbox' );
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_script( 'videojs' );
	}

	/**
	 * Style Loader
	 *
	 * This function registers and enqueues the needed styles 
	 *
	 * @uses is_customfield_page()
	 *
	 */		
	function common_styles() {
		wp_register_style( 'controls', get_template_directory_uri() . '/framework/css/controls.css' );
		wp_register_style( 'rf_custom', get_template_directory_uri() . '/css/custom-admin.css', array( 'controls' ) );

		wp_enqueue_style( 'rf_custom' );		
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_style( 'controls' );
	}

		
		
	/****************************************************************/
	/*                     Getters and Setters                      */
	/****************************************************************/

	/**
	 * Section Definitions
	 *
	 * Retrieves a raw list of sections from the options list
	 *
	 * @return array $sections The sections of options (option groups)
     *
	 */		
	function set_sections() {
		$sections = array();
		$i=0; 
		foreach( $this->options as $option ) {
			$sections[$i]['slug'] = $option['slug'];
			$sections[$i]['name'] = $option['name'];
			$i++;
		}
		
		return $sections;
	}
		


	/**
	 * Default Options 
	 *
	 * Retrieves a raw list of defaults from the options list
	 *
	 * @return array defaults A list of default options
     *
	 */	
	function get_defaults() {
		$defaults = array();
		
		foreach( $this->options as $section ) {
			foreach ( $section['options'] as $option ) {
				if( isset( $option['slug'] ) AND !empty( $option['slug'] ) ) {
					$option['default'] = ( isset( $option['default'] ) AND !empty( $option['default'] ) ) ? $option['default'] : '';
					$defaults[$option['slug']] = $option['default'];
				}
			}
		}
		
		return $defaults;
	}

	/**
	 * Get Example Link 
	 *
	 * Retrieves the correct example link. It can handle video or simple URL links
	 *
	 * @param array $option The details of the option in question
	 * @param string $before String to insert before the link
	 * @param string $after String to insert after the link
	 * @param bool $video Defines weather a video link should be generated or not
	 *
	 * @return string $output The HTML code necessary for the example link
     *
	 */			
	function get_example_link( $option, $before = "", $after = "", $video = false ) {
		
		$output = false; 
		
		if( isset( $option['example'] ) AND !empty( $option['example'] ) ) {
			$option['example_text'] = ( !isset( $option['example_text'] ) OR empty( $option['example_text'] ) ) ? "view example" : $option['example_text'];
	
			if( isset( $option['example_video'] ) AND !empty( $option['example_video'] ) ) {

				$output = '
					<div style="display:none">
							
						<video id="video_' . $option['slug'] . '" poster="http://tastique.org/redfactory/' . $option['slug'] . ' . jpg" style="width:auto; height:auto; max-width:100%" controls> 
					    	<source src="http://tastique.org/redfactory/' . $option['example_video'] . ' . mp4" media="only screen and (min-device-width: 960px)"></source> 
					   		<source src="http://tastique.org/redfactory/' . $option['example_video'] . ' . iphone.mp4" media="only screen and (max-device-width: 960px)"></source> 
					    	<source src="http://tastique.org/redfactory/' . $option['example_video'] . ' . webm"></source> 
						</video>
					</div>

				';
				
				$output .= $before . '<a class="example video" href="video_' . $option['slug'] . '">' . $option['example_text'] . '</a>' . $after;			
			
			}
			else {
				$output = $before . '<a target="_blank" class="example" href="' . $option['example'] . '">' . $option['example_text'] . '</a> ' . $after;
			}
		}
		
		//return $output;
	}
	
	
	/****************************************************************/
	/*                        Builder Methods                       */
	/****************************************************************/	


	/**
	 * Title Element
	 *
	 * The title element is shown on the top of an options section. 
	 * It isially contains the title, a description and a save button
	 *
	 * @param array $option An array containing the current options details
	 * @param string $section The name of the section the option is in 
	 *
	 */	
	function build_element_title( $option, $section ) {
		echo '<div class="twelvecol">';

		if ( !isset( $this->options[$section]['nosave'] ) OR  $this->options[$section]['nosave'] != true ) {
			echo '<input type="submit" class="title-save" value="Save Changes">';
		}
		
		echo '<h1>' . $option['name'] . '</h1>';
		echo '<div class="info sixcol">';
		echo $option['description'];
		echo '</div>';
		echo '</div>';	
	}	

	/**
	 * Multipage Selector Element
	 *
	 * This element allows the selection of multiple pages
	 *
	 * @param array $option An array containing the current options details
	 * @param string $section The name of the section the option is in 
	 * 
	 * @uses get_example_link()
	 *
	 */	
	function build_element_multipage( $option, $section ) {
		global $wpdb;
		echo '<h2>';
		echo $option['name'];		
		echo '</h2>';		
		echo '<div class="sixcol">';
				
		$pages = get_pages( );
		
		$current = $this->get_value( $option, $section );
		$current = ( is_array( $current ) ) ? $current : unserialize( $current );
		if( isset( $current ) AND !empty( $current ) ) {
			$selection = implode(',', $current);
		}

		echo '<select title="Select a Page" data-selection="' . $selection . '" style="height:auto" multiple="multiple" name="' . $option['slug'] . '[]" id="' . $option['slug'] . '">';
			
			foreach( $pages as $page ) {
				$classSelected = ( in_array( $page->ID, $current ) ) ? 'selected="selected"' : '';
				echo '<option ' . $classSelected . ' value="' . $page->ID . '">' . $page->post_title . '</option>';
			}
		echo '</select>';
		echo '</div>';
		echo '<div class="sixcol last">';
		echo '<div class="info">';
		echo $option['description'];
		echo $this->get_example_link( $option, '<br />' );
		echo '</div>';
		echo '</div>';			
	}

	/**
	 * Radio Button Element
	 *
	 * This element allows for the selection of one - and only one - value 
	 * from a list of options. 
	 *
	 * @param array $option An array containing the current options details
	 * @param string $section The name of the section the option is in 
	 * 
	 * @uses get_example_link()
	 *
     */		
	function build_element_radio( $option, $section ) {
		echo '<h2>';
		echo $option['name'];		
		echo '</h2>';		
		echo '<div class="sixcol radios">';

		foreach( $option['options'] as $value => $name ) {
			$checked = ( $this->get_value( $option, $section ) == $value ) ? 'checked="checked"' : '';
			$status = ( $this->get_value( $option, $section ) == $value ) ? 'status-on' : 'status-off';

			$status = ( isset( $checked ) AND $checked == 'checked="checked"' ) ? 'status-on' : 'status-off';
			echo '<div class="radio ' . $status . '"><input ' . $checked . ' type="radio" name="' . $option['slug'] . '" id="' . $option['slug'] . '" value="' . $value . '"><label class="info">' . $name . '</label></div>';
		}	
				
		echo '</div>';
		echo '<div class="sixcol last">';
		echo '<div class="info">';
		echo $option['description'];
		echo $this->get_example_link( $option, '<br />' );
		echo '</div>';
		echo '</div>';	
	}

	/**
	 * Checkbox Element
	 *
	 * The checkbox element is used for selecting multiple values from 
	 * a given list. 
	 *
	 * @param array $option An array containing the current options details
	 * @param string $section The name of the section the option is in 
	 *
	 * @uses get_example_link()
	 *
	 */		
	function build_element_checkbox( $option, $section ) {
		$checked = ( $this->get_value( $option, $section ) == 'yes' ) ? 'checked="checked"' : '';
		$status = ( $this->get_value( $option, $section ) == 'yes' ) ? 'status-on' : 'status-off';
		echo '<h2>';
		echo $option['name'];
		echo '</h2>';
		echo '<div class="sixcol">';
		echo '<div class="checkbox ' . $status . '"><input ' . $checked . ' type="checkbox" name="' . $option['slug'] . '" id="' . $option['slug'] . '" value="yes"><label class="info">' . $option['description'] . '</label></div>';
		echo '</div>';
		echo '<div class="sixcol last">';
		echo '<div class="info">';
		echo $this->get_example_link( $option );
		echo '</div>';
		echo '</div>';	
	}

	/**
	 * Text Element
	 *
	 * Creates a simple text input element
	 *
	 * @param array $option An array containing the current options details
	 * @param string $section The name of the section the option is in 
	 *
	 * @uses get_example_link()
	 *
	 */			
	function build_element_text( $option, $section ) {
		echo '<h2>';
		echo $option['name'];
		echo '</h2>';
		echo '<div class="sixcol">';
		echo '<input type="text" name="' . $option['slug'] . '" id="' . $option['slug'] . '" value="' . esc_html(stripslashes($this->get_value( $option, $section ))) . '">';
		echo '</div>';
		echo '<div class="sixcol last">';
		echo '<div class="info">';
		echo $option['description'];
		echo $this->get_example_link( $option, '<br />' );
		echo '</div>';
		echo '</div>';	
	}

	/**
	 * Textarea Element
	 *
	 * Creates a simple textarea element
	 *
	 * @param array $option An array containing the current options details
	 * @param string $section The name of the section the option is in 
	 *
	 * @uses get_example_link()
	 *
	 */		
	function build_element_textarea( $option, $section ) {
		echo '<h2>';
		echo $option['name'];	
		echo '</h2>';		
		echo '<div class="sixcol">';
		echo '<textarea name="' . $option['slug'] . '" id="' . $option['slug'] . '">' . stripslashes($this->get_value( $option, $section )) . '</textarea>';
		echo '</div>';
		echo '<div class="sixcol last">';
		echo '<div class="info">';
		echo $option['description'];
		echo $this->get_example_link( $option, '<br />' );
		echo '</div>';
		echo '</div>';	
	}

	/**
	 * Multi Category Selector Element
	 *
	 * This element enables the user to select multiple categories from a list
	 *
	 * @param array $option An array containing the current options details
	 * @param string $section The name of the section the option is in 
     *
	 * @uses get_example_link()
	 *
	 */		
	function build_element_multicategory( $option, $section ) {
		echo '<h2>';
		echo $option['name'];		
		echo '</h2>';		
		echo '<div class="sixcol">';
		$terms = get_terms( $option['taxonomy'], array( 'hide_empty' => 0 ) );
		$current = $this->get_value( $option, $section );
		$current = ( is_array( $current) ) ? $current : unserialize( $current );

		echo '<select title="Select a Category" style="height:auto" multiple="multiple" name="' . $option['slug'] . '[]" id="' . $option['slug'] . '">';
			foreach( $terms as $term ) {
				$classSelected = ( in_array( $term->term_id, $current ) ) ? 'selected="selected"' : '';
				echo '<option ' . $classSelected . ' value="' . $term->term_id . '">' . $term->name . '</option>';
			}
		echo '</select>';
		echo '</div>';
		echo '<div class="sixcol last">';
		echo '<div class="info">';
		echo $option['description'];
		echo $this->get_example_link( $option, '<br />' );
		echo '</div>';
		echo '</div>';			
	}

	/**
	 * Select Element
	 *
	 * Generates a simple dropdown list
	 *
	 * @param array $option An array containing the current options details
	 * @param string $section The name of the section the option is in 
     *
	 * @uses get_example_link()
	 *
	 */					
	function build_element_select( $option, $section ) {
		echo '<h2>';
		echo $option['name'];		
		echo '</h2>';		
		echo '<div class="sixcol">';
		echo '<select name="' . $option['slug'] . '" id="' . $option['slug'] . '">';
			foreach( $option['options'] as $value => $name ) {
				$current = $this->get_value( $option, $section );
				$classSelected = ( $value == $current ) ? 'selected="selected"' : '';
				echo '<option ' . $classSelected . ' value="' . $value . '">' . $name . '</option>';
			}
		echo '</select>';
		echo '</div>';
		echo '<div class="sixcol last">';
		echo '<div class="info">';
		echo $option['description'];
		echo $this->get_example_link( $option, '<br />' );
		echo '</div>';
		echo '</div>';	
	}
	
	/**
	 * Color Selector Element
	 *
	 * This element generates a text input and a color display. Clicking
	 * on the text input will show a colorpicker which you can choose from/
	 *
	 * @param array $option An array containing the current options details
	 * @param string $section The name of the section the option is in 
     *
	 * @uses get_example_link()
	 *
	 */			
	function build_element_color( $option, $section ) {
		echo '<h2>';
		echo $option['name'];
		echo '</h2>';
		echo '<div class="sixcol">';
		echo '<div class="swatch" style="background-color:' . $this->get_value( $option, $section ) . '"></div>';
		echo '<input class="swatch-input" type="text" name="' . $option['slug'] . '" id="' . $option['slug'] . '" value="' . $this->get_value( $option, $section) . '">';
		echo '</div>';
		echo '<div class="sixcol last">';
		echo '<div class="info">';
		echo $option['description'];
		echo $this->get_example_link( $option, '<br />' );
		echo '</div>';
		echo '</div>';	
	}

	/**
	 * Uploader Element
	 *
	 * This generates a text input which will trigger the upload dialog
	 * when clicked. The user must click 'insert into post' to add the
	 * image url into the text input
	 *
	 * @param array $option An array containing the current options details
	 * @param string $section The name of the section the option is in 
     *
	 * @uses get_example_link()
	 *
	 */	
	function build_element_upload( $option, $section ) {
		echo '<h2>';
		echo $option['name'];
		echo '</h2>';
		echo '<div class="sixcol">';
		echo '<input class="upload" type="text" name="' . $option['slug'] . '" id="' . $option['slug'] . '" value="' . $this->get_value( $option, $section) . '">';
		echo '<button type="button" data-id="' . $option['slug'] . '" class="upload-button">browse</button>';
		echo '</div>';
		echo '<div class="sixcol last">';
		echo '<div class="info">';
		echo $option['description'];
		echo $this->get_example_link( $option, '<br />' );
		echo '</div>';
		echo '</div>';	
	}
	
	

	/**
	 * Update Framework Controls
	 *
	 * This enables the user to update the framework
	 *
	 */	
	function build_element_update_framework() {
	?>

	<?php
		echo '<div class="sixcol">';
		echo '<span class="button primary loading" id="check_framework_update" data-original="check for updates">check for framework updates</span>';
		echo '<div class="clear"></div>';
		echo '<div id="framework_update_check_results"></div>';
		echo '</div><div class="sixcol last"></div>';

	}
	

	/**
	 * Update Theme Controls
	 *
	 * This enables the user to update the theme itself
	 *
	 */	
	function build_element_update_theme() {
		echo '<div class="sixcol">';
		echo '<span class="button primary loading" id="check_theme_update" data-original="check for theme updates">check for theme updates</span>';
		echo '<div class="clear"></div>';
		echo '<div id="theme_update_check_results"></div>';
		echo '</div><div class="sixcol last"></div>';
	}
	

	/**
	 * Select from a list of flies
	 *
	 * Enables the flexile selection of files for templates and whatnot
	 *
	 */	
	function build_element_fileselect( $option, $section ) {
		echo '<h2>';
		echo $option['name'];		
		echo '</h2>';		
		echo '<div class="sixcol">';
		
		$values = array();
		$files = scandir( $option['folder'] );
		foreach( $files as $file ) {
			if( !in_array( $file, array( '.', '..' ) ) ) {
				$handle = @fopen( $option['folder'] . '/' . $file, "r");
				if ($handle) {
				    $line = fgets( $handle );
				    preg_match( "/\/\* Format Name: (.*)\*\//", $line, $matches );
				    $filename = str_replace( '.php', '', $file );
				    if( trim($matches[1]) != 'Internal' ) {
					    $values[$filename] = $matches[1];
				    }
				    fclose($handle);
				}
			}
		}
					
		echo '<select name="' . $option['slug'] . '" id="' . $option['slug'] . '">';
		foreach( $values as $value => $name ) {
			$current = $this->get_value( $option, $section );
			$selected = ( 'layout-' . $current == $value ) ? 'selected="selected"' : '';
			$value = str_replace( 'layout-', '', $value );
			echo '<option ' . $selected . ' value="' . $value . '">' . $name . '</option>';
		}
		echo '</select>';
		echo '</div>';
		echo '<div class="sixcol last">';
		echo '<div class="info">';
		echo $option['description'];
		echo '</div>';
		echo '</div>';	
	}	
	

	/**
	 * Update Disabled Display
	 *
	 * If Auto Updating is not possible the following is shown
	 *
	 */	
	
	function build_element_update_disabled( $option, $section ) {
	/*
		$errorCount = count($option['errors']);
		$errorCountText = ( $errorCount == 1 ) ? 'there is <strong>1 error</strong> ' : 'there are <strong>' . $errorCount . ' errors</strong> ';
		
		echo '<div class="sixcol">';
		echo '
			<div class="message alert">
				Some problems are preventing you from updating your theme automatically
			</div>
			
			<h2>How do I update my theme?</h2>
			<p>
				Never fear, you can always update your theme the regular way! Log in to your
				<a href="http://themeforest.net/">ThemeForest</a> account and download the 
				latest version of the theme. 
			</p>
			
			<p>
				Once you have the .zip file containing the theme you can do two things. If you have 
				FTP access to your site you can upload all the files into the theme folder, overwriting the
				old files. 
			</p>
			
			<p>
				A slightly simpler way of doing it is going to the Appearance tab in WordPress and activating a different theme. Once
				done you should be able to delete this theme. Once deleted you can go to Install->Upload, upload the zip file and activate
				the theme.
			</p>
			
			<p>Beware, as both methods will overwrite all your files so changes you have made to the theme files will
			be lost</p>
			
			<h2>How can I get the auto update feature to work?</h2>
			<p>
				Based on our checks ' . $errorCountText . ' preventing you from auto updating. Please
				read the following report and some directions on how to resolve the problem(s).
			</p>
			
			';		
			
			if( isset( $option['errors']['ZipArchive'] ) AND $option['errors']['ZipArchive'] === true ){
				echo '
					<h4>ZipArchive Class Missing</h4>
					<p>
						In order to update your theme we need to unzip some files. To do this we use a PHP class called
						ZipArchive. This class is available from PHP 5.2.0 and up (released in November 2007). In custom server
						installations it is possible that this class is not available. If so, please contact your web host and
						ask them about it. 
					</p>
					';
			}
			
			if( isset( $option['errors']['filepermissions'] ) AND $option['errors']['filepermissions'] === true ){
				echo '
					<h4>Directory Folder Is Not Writable</h4>
					<p>
						In order to install the new theme we need to be able to delete the old files and add the new ones.
						This requires you to have write permission to the theme folder. Please refer to the <a href="http://codex.wordpress.org/Changing_File_Permissions">WordPress Guide on File Permissions</a> for help on what permissions you may need to give. 
					</p>
					';
			}
			
		echo '</div><div class="sixcol last"></div>';
		*/
	}	
	
	
	
	/**
	 * Display Documentation
	 *
	 * Show the Theme Documentation
	 *
	 */	
	function build_element_documentation() {
		ob_start();
		include( get_template_directory()  . '/readme.html' );
		$documentation = ob_get_clean();
		$start = strpos( $documentation, '<body class="normal">' );
		$end = strpos( $documentation, '</body>' );
		$documentation = substr( $documentation, $start, $end);
		
		echo $documentation;
	}
		
		
	function faux() {
		wp_link_pages();
		
	}
	
	
	

			
}



?>