<?php

/****************************************************************/
/*                          Shortcode List                      */
/****************************************************************/

/*
Creates a 1px horizontal line
*/
add_shortcode( 'line', 'rf_line_shortcode' );

/*
Creates a 1px horizontal line with a link inside it.
*/
add_shortcode( 'linelink', 'rf_linelink_shortcode' );

/*
Highlights the selected text
*/
add_shortcode( 'highlight', 'rf_highlight_shortcode' );

/*
Enables the user to put content into columns
*/
add_shortcode( 'column', 'rf_column_shortcode' );

/*
Enables the user to put content into columns
*/
add_shortcode( 'innercolumn', 'rf_innercolumn_shortcode' );

/*
Enables Google Maps Embedding
*/
add_shortcode( 'map', 'rf_map_shortcode' );

/*
Enables Different User Messages
*/
add_shortcode( 'message', 'rf_message_shortcode' );

/*
Creates a toggleable section
*/
add_shortcode( 'toggle', 'rf_toggle_shortcode' );

/*
Enables quick creation of nice buttons
*/
add_shortcode( 'button', 'rf_button_shortcode' );

/*
Enables quick creation of banners
*/
add_shortcode( 'banner', 'rf_banner_shortcode' );


/*
Creates a main title
*/
add_shortcode( 'title', 'rf_title_shortcode' );

/*
Creates a mini post slider
*/
add_shortcode( 'postslider', 'rf_postslider_shortcode' );

/*
Creates states, allows users to display content based on the users logged in state
*/
add_shortcode( 'state', 'rf_state_shortcode' );

/*
Includes a file
*/
add_shortcode( 'include', 'rf_include_shortcode' );

/*
Creates a post list
*/
add_shortcode( 'postlist', 'rf_postlist_shortcode' );
 
/*
Creates a distinct sectioned element
*/
add_shortcode( 'section', 'rf_section_shortcode' );
 
/*
Creates a distinct sectioned element
*/
add_shortcode( 'slider', 'rf_slider_shortcode' );

 
 /**
 * Slider 
 *
 * Creates a slider.
 *
 * @param array $atts The attributes passed to the line 
 *
 * @uses rf_shortcode_info()
 *
 */
function rf_slider_shortcode( $atts ) {

// Extract the attributes into variables
	extract( shortcode_atts( array( 
		'sample' => false,
		'count'  => 5,
		'showtitle' => 'yes',
		'cat' => false,
		'category_slug' => false,
		'height' => 400,
		'delay' => 4500
	 ), $atts ) );
	
	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[slider]';
	$info['description'] = 'Creates a slider from your posts';
	$info['parameters']  = array( 
		'Optional Parameters' => array ( 
			'count' => array( 
				'name'         => 'count',
				'description'  => 'The number of posts in the slide.',
				'default'      => '5',
				'values'       => array( 
					'any number'     => array( 
						'name' => 'any number'
					 ),
				 )
			 ),
			'showtitle' => array( 
				'name'         => 'showtitle',
				'description'  => 'Set the title to be shown or hidden by default or to be shown only on hover',
				'default'      => 'yes',
				'values'       => array( 
					'yes'     => array( 
						'name' => 'yes',
						'default' => true
					 ),
					'no'     => array( 
						'name' => 'no'
					 ),
					'hover'     => array( 
						'name' => 'hover'
					 ),
				 )
			 ),
			'category_slug' => array( 
				'name'         => 'category_slug',
				'description'  => 'Add a comma separated list of category slugs to use in the slider. Please note only category_name or cat can be used. If both are given the category_name will be used',
				'default'      => 'none',
				'values'       => array( 
					'categories'     => array( 
						'name' => 'category slugs, separated by commas',
					 ),
				 )
			 ),
			'cat' => array( 
				'name'         => 'category ids',
				'description'  => 'Add a comma separated list of category ids to use in the slider. Please note only category_name or cat can be used. If both are given the category_name will be used',
				'default'      => 'none',
				'values'       => array( 
					'categories'     => array( 
						'name' => 'category ids, separated by commas',
					 ),
				 )
			 ),
			'height' => array( 
				'name'         => 'height',
				'description'  => 'Add a fixed height to the slider. This is frequently required to make the slider look nice if you have a lot of varying sized images',
				'default'      => '400',
				'values'       => array( 
					'number'     => array( 
						'name' => 'a positive number',
					 ),
				 )
			 ),
			'delay' => array( 
				'name'         => 'delay',
				'description'  => 'The amount of time between slide transitions',
				'default'      => '4500',
				'values'       => array( 
					'number'     => array( 
						'name' => 'a positive number ( in miliseconds )',
					 ),
				 )
			 ),
		 )
	 );
	
	// Documentation
	if ( isset( $sample ) AND !empty( $sample ) ) {
		return rf_shortcode_info( $info, $sample );
	}
	
ob_start();
?>

<script type="text/javascript">
jQuery(window).load(function() {
  jQuery('.flexslider').flexslider({
    animation: "slide",
    slideshowSpeed: <?php echo $delay ?>
  });
});
</script>



<?php

	

	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'posts_per_page' => $count,
	);
	
	if( isset( $category_slug ) AND !empty( $category_slug ) ) {
		$args['category_name'] = $category_slug;
	}
	elseif( ( !isset( $category_name ) OR empty( $category_name ) ) AND isset( $cat ) AND !empty( $cat ) ) {
		$args['cat'] = $cat;
	}
	
	$slider = new WP_Query( $args );
?>

<div class="flexslider">
  <ul class="slides">
	    <?php 
	    	global $post;
	    	while( $slider->have_posts() ) : $slider->the_post();
	    	$image_id = get_post_thumbnail_id( $post->ID );
		    $image = wp_get_attachment_image_src( $image_id, 'full' );
       		$classHover = ( $showtitle == 'hover') ? 'show_on_hover' : '';

	    ?>
       	<li style='height: <?php echo $height ?>px'>
       	<div class='slide-container <?php echo $classHover ?>'>
       	<?php if( $showtitle != 'no') : ?> 
	       	<div class='slide-content'>
	       		<h1> <a href='<?php the_permalink() ?>'><?php the_title() ?></a></h1>
	       	</div>
       	<?php endif ?>
       	<img src="<?php echo $image[0] ?>" alt="" />
       	</div>
       	</li>
        <?php endwhile; ?>
  </ul>
</div>


<?php 

$slider = ob_get_clean();
return $slider;


}



/**
 * Line Creator 
 *
 * Creates a horizontal line to separate sections.
 *
 * @param array $atts The attributes passed to the line 
 *
 * @uses rf_shortcode_info()
 *
 */
function rf_line_shortcode( $atts ) {

	// Extract the attributes into variables
	extract( shortcode_atts( array( 
		'sample' => false,
	 ), $atts ) );
	
	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[line]';
	$info['description'] = 'Creates a horizontal line.';

	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	
	// Display of the shortcode
	else {
		return '<div class="line"></div>';
	}
 }



/**
 * Line and Link Creator 
 *
 * Creates a horizontal line whith a link floated to the left or right of it
 *
 * @param array $atts The attributes passed to the line 
 *
 * @uses rf_shortcode_info()
 *
 */
function rf_linelink_shortcode( $atts ) {
	
	// Extract the attributes into variables
	extract( shortcode_atts( array( 
		'sample' => false,
		'align'  => 'right',
		'text'   => 'top',
		'url'    => 'top'
	 ), $atts ) );
	
	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[linelink]';
	$info['description'] = 'Creates a horizontal line with a link to the left or right of it.';
	$info['parameters']  = array( 
		'Optional Parameters' => array ( 
			'align' => array( 
				'name'         => 'align',
				'description'  => 'Modifies the alignment of the link.',
				'default'      => 'right',
				'values'       => array( 
					'left'     => array( 
						'name' => 'left'
					 ),
					'right' => array ( 
						'name'    => 'right',
						'default' => true,
					 )
				 )
			 ),
			'text' => array( 
				'name'        => 'text',
				'description' => 'The text of the link',
				'default'     => 'top'
			 ),
			'url' => array ( 
				'name'        => 'url',
				'description' => 'Specify the location where the user should be taken after clicking on the link',
				'default'     => 'top',
				'values'      => array ( 
					'top'             => array ( 
						'name'        => 'top',
						'description' => 'takes the user to the top of the page',
						'default'     => true,
					 ),
					'#section'        => array( 
						'name'        => '#section',
						'description' => 'the id of an HTML element on the page, preceded by a hash will take the user to that area on the page',
					 ),
					'url' => array( 
						'name'        => 'url',
						'description' => 'any regular url can be used as well'
					 )
					
				 )
			 )
		 )
	 );
	
	// Documentation
	if ( isset( $sample ) AND !empty( $sample ) ) {
		return rf_shortcode_info( $info, $sample );
	}
	
	// Display of the shortcode
	else {
		
		$url = ( $url == 'top' ) ? '#' : $url;
		$classPosition = 'align'.$align;
		
		return '<div class="linelink"><a class="' . $classPosition . '" href="' . $url . '">' . $text . '</a><div class="line"></div><div class="clear"></div></div>';
	}
 }


/**
 * Text Highlight Creator 
 *
 * Creates a customizable highlight around the content it is placed
 *
 * @global array $theme_options The options for this theme
 *
 * @param array $atts The attributes passed to the line 
 * @param string $content The text it is wrapped around
 *
 * @uses rf_shortcode_info()
 *
 */
function rf_highlight_shortcode( $atts, $content = null ) {
	global $theme_options; 
	
	// Extract the attributes into variables
   	extract( shortcode_atts( array( 
		'sample'     => false,
		'background' => false,
		"color"      => false,
		'preset'     => false,
		"paragraph"  => false,
	 ), $atts ) );
	

	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[highlight] Write some text here [/highlight]';
	$info['description'] = 'Creates a highlight for the selected text.';
	$info['parameters']  = array( 
		'Optional Parameters' => array ( 
			'preset' => array( 
				'name'        => 'preset',
				'description' => 'There are a number of preset buttons in this theme. If you use a preset you do not need to specify the color, background and text shadow separately to make sure the button looks good.',
				'default'     => 'theme primary color: ' . $theme_options['rfoption_colors_primary'],
				'values' => array( 
					'red' => array( 
						'name'        => 'red',
						'description' => '#D12344',
					 ),	
					'cyan' => array( 
						'name'        => 'cyan',
						'description' => '#24ACD2',
					 ),	
					'yellow' => array( 
						'name'        => 'yellow',
						'description' => '#E3C137',
					 ),	
					'black' => array( 
						'name'        => 'black',
						'description' => '#3C3C3C',
					 ),	
					'blue' => array( 
						'name'        => 'blue',
						'description' => '#458BC0',
					 ),	
					'green' => array( 
						'name'        => 'green',
						'description' => '#77AE42',
					 ),	
					'purple' => array( 
						'name'        => 'purple',
						'description' => '#B244AC',
					 ),	
					'orange' => array( 
						'name'        => 'orange',
						'description' => '#SB8E4E',
					 ),	
							
				 )
			 ),
			'background' => array( 
				'name'        => 'background',
				'description' => 'Specify the background color of the selection.',
				'default'     => 'theme primary color: '.$theme_options['rfoption_colors_primary'],
				'values'      => array( 
					'color value'     => array( 
						'name'        => 'color value',
						'description' => 'Can be hex value: "#ff9900", rgb value: "rgb( 255,0,255 )" or a named color: "red"'
					 ),
				 )
			 ),
			'color' => array ( 
				'name'        => 'color',
				'description' => 'Specify the text color of the selection.',
				'default'     => 'white',
				'values'      => array ( 
					'color value' => array( 
						'name'        => 'color value',
						'description' => 'Can be hex value: "#ff9900", rgb value: "rgb( 255,0,255 )" or a named color: "red"'
					 ),
					
				 )
			 ),
			'paragraph' => array ( 
				'name'        => 'paragraph',
				'description' => 'If the highlighted section is a separate paragraph or line, please set this to true to ensure proper spacing in all conditions',
				'default'     => 'false',
			 )
		 )
	 );
	
	
	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	
	// Display of the shortcode
	else {
		$classes['preset'] = ( isset( $preset ) AND !empty( $preset ) ) ? $preset : '';
		 
		$styles['background'] = ( isset( $background) AND !empty( $background ) ) ? 'background:'.$background : false;
		$styles['color'] = ( isset( $background) AND !empty( $background ) ) ? 'color:'.$color : false;
		
		$html = '<span class="highlight ' . implode( ';', $classes ) . '" style="' . implode( ';', $styles ) . '">';
		$html .= do_shortcode( $content ) . '</span>';
		
		if ( $paragraph == 'true' ) {
			$html = wpautop( $html );
		}
		
		return $html;
	}
 }




/**
 * Column Creator 
 *
 * Enables users to create columns with shortcodes easily
 *
 * @param array $atts The attributes passed to the line 
 * @param string $content The text it is wrapped around
 *
 * @uses rf_shortcode_info()
 *
 */
function rf_column_shortcode( $atts, $content = null ) {
	
	// Extract the attributes into variables
   	extract( shortcode_atts( array( 
		'size'   => '12',
		'last'   => false,
		'first'  => false,
		'sample' => false,
		'align'	 => false,
	 ), $atts ) );


	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[column] The content of the column [/column]';
	$info['description'] = 'Creates a column of content';
	$info['parameters']  = array( 
		'Required Parameters' => array ( 
			'first'           => array( 
				'name'        => 'first',
				'description' => 'Must be set to true if this is the first column in the row',
				'default'     => 'false',
				'values' => array( 
					'true'     => array( 
						'name' => 'true',
					 ),
					'false' => array( 
						'name'    => 'false',
						'default' => true
					 )
				 )
			 ),
			'last' => array( 
				'name'        => 'last',
				'description' => 'Must be set to true if this is the last column in the row',
				'default'     => 'false',
				'values'      => array( 
					'true' => array( 
						'name' => 'true',
					 ),
					'false' => array( 
						'name'    => 'false',
						'default' => true
					 )
				 )
			 ),
		 ),
		'Optional Parameters' => array ( 
			'size' => array( 
				'name'        => 'size',
				'description' => 'The space the column should span across. The total size of the columns
					in a row must add up to 12. You can have two 6 column or three 4 sized columns. You can also
					have a 10 sized and a 2 sized for example.',
				'default'     => '6',
				'values'      => array( 
					'1 - 12' => array( 
						'name'        => '1 - 12',
						'description' => 'Remember to make all columns in a row add up to 12'
					 ),
				 )
			 ),
			'align' => array ( 
				'name'        => 'align',
				'description' => 'Specify the alignment of the text inside a column',
				'default'     => 'left',
				'values'      => array ( 
					'left' => array( 
						'name'    => 'left',
						'default' => true
					 ),
					'right' => array ( 
						'name' => 'right',
					 ),
					'center' => array( 
						'name' => 'center'
					 )
				 )
			 )
		 )
	 );
	
	
	
	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	
	// Display of the shortcode
	else {
		$column_names = array( 
			'1'  => 'one', 
			'2'  => 'two', 
			'3'  => 'three', 
			'4'  => 'four', 
			'5'  => 'five', 
			'6'  => 'six', 
			'7'  => 'seven', 
			'8'  => 'eight', 
			'9'  => 'nine', 
			'10' => 'ten', 
			'11' => 'eleven', 
			'12' => 'twelve', 
		 );
	
		$class['column'] = $column_names[$size] . 'col';
		$class['last']   = ( $last === 'true' ) ? 'last' : '';
		$class['align']  = ( $align === false ) ? '' : 'text-' . $align;
				
		$html = '';
		
		if( isset( $first ) AND $first == true ) {
			$html = '<div class="inner-row rf-section">';
		}
		
		$html .= '<div class="' . implode( ' ', $class ) . '">';
		$html .= do_shortcode( $content );	
		$html .= '</div>';
		
		if( isset( $last ) AND $last == true ) {
			$html .= '</div>';
		}
				
		return $html;
	}
	
}




/**
 * Ineer Column Creator 
 *
 * Enables users to create columns within columns with shortcodes easily
 *
 * @param array $atts The attributes passed to the line 
 * @param string $content The text it is wrapped around
 *
 * @uses rf_shortcode_info()
 *
 */
function rf_innercolumn_shortcode( $atts, $content = null ) {
	
	// Extract the attributes into variables
   	extract( shortcode_atts( array( 
		'size'   => '12',
		'last'   => false,
		'first'  => false,
		'sample' => false,
		'align'	 => false,
	 ), $atts ) );


	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[column] The content of the column [/column]';
	$info['description'] = 'Creates a column of content';
	$info['parameters']  = array( 
		'Required Parameters' => array ( 
			'first'           => array( 
				'name'        => 'first',
				'description' => 'Must be set to true if this is the first column in the row',
				'default'     => 'false',
				'values' => array( 
					'true'     => array( 
						'name' => 'true',
					 ),
					'false' => array( 
						'name'    => 'false',
						'default' => true
					 )
				 )
			 ),
			'last' => array( 
				'name'        => 'last',
				'description' => 'Must be set to true if this is the last column in the row',
				'default'     => 'false',
				'values'      => array( 
					'true' => array( 
						'name' => 'true',
					 ),
					'false' => array( 
						'name'    => 'false',
						'default' => true
					 )
				 )
			 ),
		 ),
		'Optional Parameters' => array ( 
			'size' => array( 
				'name'        => 'size',
				'description' => 'The space the column should span across. The total size of the columns
					in a row must add up to 12. You can have two 6 column or three 4 sized columns. You can also
					have a 10 sized and a 2 sized for example.',
				'default'     => '6',
				'values'      => array( 
					'1 - 12' => array( 
						'name'        => '1 - 12',
						'description' => 'Remember to make all columns in a row add up to 12'
					 ),
				 )
			 ),
			'align' => array ( 
				'name'        => 'align',
				'description' => 'Specify the alignment of the text inside a column',
				'default'     => 'left',
				'values'      => array ( 
					'left' => array( 
						'name'    => 'left',
						'default' => true
					 ),
					'right' => array ( 
						'name' => 'right',
					 ),
					'center' => array( 
						'name' => 'center'
					 )
				 )
			 )
		 )
	 );
	
	
	
	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	
	// Display of the shortcode
	else {
		$column_names = array( 
			'1'  => 'one', 
			'2'  => 'two', 
			'3'  => 'three', 
			'4'  => 'four', 
			'5'  => 'five', 
			'6'  => 'six', 
			'7'  => 'seven', 
			'8'  => 'eight', 
			'9'  => 'nine', 
			'10' => 'ten', 
			'11' => 'eleven', 
			'12' => 'twelve', 
		 );
	
		$class['column'] = $column_names[$size] . 'col';
		$class['last']   = ( $last === 'true' ) ? 'last' : '';
		$class['align']  = ( $align === false ) ? '' : 'text-' . $align;
		
		$html = '';
		$html .= '<div class="' . implode( ' ', $class ) . '">';
		$html .= do_shortcode( $content );	
		$html .= '</div>';
		
		if( isset( $last ) AND $last == true ) {
			$html .= '<div class="clear"></div>';
		}
				
		return $html;
	}
	
}




/**
 * Map Creator 
 *
 * Enables users to embed customizable Google Maps easily
 *
 * @param array $atts The attributes passed to the line 
 *
 * @uses rf_shortcode_info()
 *
 */
function rf_map_shortcode( $atts ) {

	// Extract the attributes into variables
   	extract( shortcode_atts( array( 
		'location' => '',
		'zoom'     => '10',
		'popup'    => 'no',
		'height'   => '400px',
		'width'    => '100%',
		'sample'   => false,
	 ), $atts ) );
	
	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[map]';
	$info['description'] = 'Embeds a Google Map into your content';
	$info['parameters']  = array( 
		'Required Parameters' => array ( 
			'location'        => array( 
				'name'        => 'location',
				'description' => 'An address which the map should be centered on',
				'default'     => 'none',
			 ),
		 ),
		'Optional Parameters' => array ( 
			'zoom' => array( 
				'name'        => 'zoom',
				'description' => 'The zoom setting for the map, the higher number the higher the zoom',
				'default'     => '10',
				'values'      => array( 
					'0 - 24' => array( 
						'name' => '0 - 24',
					 ),
				 )
			 ),
			'popup' => array ( 
				'name'        => 'popup',
				'description' => 'If set to true a white popup box will show the address in the map',
				'default'     => 'false',
				'values'      => array ( 
					'true' => array ( 
						'name' => 'true',
					 ),
					'false' => array( 
						'name' => 'false'
					 )
				 )
			 ),
			'height' => array ( 
				'name'        => 'height',
				'description' => 'Modify the height of the map',
				'default'     => '400px',
				'values'      => array ( 
					'height value' => array ( 
						'name'        => 'height value',
						'description' => 'Any valid CSS value ( px is recommended )'
					 )
				 )
			 ),
			'width' => array ( 
				'name'        => 'width',
				'description' => 'Modify the width of the map',
				'default'     => '100%',
				'values'      => array ( 
					'width value' => array ( 
						'name'        => 'width value',
						'description' => 'Any valid CSS value, 100% is strongly recommended though. If you want to put a map in a column it will still scale to the column width.'
					 )
				 )
			 )
		 )
	 );	
	
	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	 
	// Display of the shortcode
	else {
		$location = str_replace( ' ', '+',$location );
		$popup = ( $popup === 'true' ) ? 'A' : 'B';

		$source = htmlentities( 'http://maps.google.com/maps?f=q&source=s_q&q='. $location . '&ie=UTF8&z=' . $zoom . '&iwloc=' . $popup . '&output=embed' );
		
		$html = '<iframe width="' . $width . '" height="' . $height . '" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="' . $source . '"></iframe><br /><br />';
		
		return $html;
	 }
 }


/**
 * Message Creator 
 *
 * Enables users to create customizable formatted messages
 *
 * @param array $atts The attributes passed to the line 
 * @param string $content The text it is wrapped around
 *
 * @uses rf_shortcode_info()
 * @uses rf_css_gradient()
 *
 */
function rf_message_shortcode( $atts, $content = null ) {

	// Extract the attributes into variables
   	extract( shortcode_atts( array( 
		'title'      => '',
		'preset'     => false,
		'sample'     => false,
		'background' => false,
		'border'     => false,
		'color'      => false,
		'rounded'    => '5px',
		'customstyle' => false,
		'align'      => 'left'
	 ), $atts ) );

	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[message] Your message goes here [/message]';
	$info['description'] = 'Enables the creation of messages, mostly used to make important snippets stand out';
	$info['parameters']  = array( 
		'Optional Parameters' => array (
			'title' => array( 
				'name'        => 'title',
				'description' => 'Add a title if needed',
				'default'     => 'none',
			 ),		
			 
			'preset' => array( 
				'name'        => 'preset',
				'description' => 'There are a number of preset buttons in this theme. If you use a preset you do not need to specify the color, background and text shadow separately to make sure the button looks good.',
				'default'     => 'theme primary color: ' . $theme_options['rfoption_colors_primary'],
				'values' => array( 
					'red' => array( 
						'name'        => 'red',
						'description' => '#D12344',
					 ),	
					'cyan' => array( 
						'name'        => 'cyan',
						'description' => '#24ACD2',
					 ),	
					'yellow' => array( 
						'name'        => 'yellow',
						'description' => '#E3C137',
					 ),	
					'black' => array( 
						'name'        => 'black',
						'description' => '#3C3C3C',
					 ),	
					'blue' => array( 
						'name'        => 'blue',
						'description' => '#458BC0',
					 ),	
					'green' => array( 
						'name'        => 'green',
						'description' => '#77AE42',
					 ),	
					'purple' => array( 
						'name'        => 'purple',
						'description' => '#B244AC',
					 ),	
					'orange' => array( 
						'name'        => 'orange',
						'description' => '#SB8E4E',
					 ),	
							
				 )
			 ),
			'color' => array( 
				'name'        => 'color',
				'description' => 'Defines the text color inside the message box',
				'default'     => 'none',
				'values'      => array( 
					'color' => array( 
						'name'        => 'color',
						'description' => 'Any valid CSS color',
					 ),								
				 )
			 ),
			'background' => array( 
				'name'        => 'background',
				'description' => 'Defines the background color of the message box',
				'default'     => 'none',
				'values' => array( 
					'color' => array( 
						'name'        => 'color',
						'description' => 'Any valid CSS color',
					 ),								
				 )
			 ),	
			'border' => array( 
				'name'        => 'border',
				'description' => 'Defines the color of the message box border',
				'default'     => 'none',
				'values' => array( 
					'color' => array( 
						'name'        => 'color',
						'description' => 'Any valid CSS color',
					 ),								
				 )
			 ),	
			'align' => array( 
				'name' => 'align',
				'description' => 'Defines the alignment of the text inside the message box',
				'default' => 'left',
				'values' => array( 
					'left' => array( 
						'name'    => 'left',
						'default' => true
					 ),								
					'right' => array( 
						'name' => 'right',
					 ),	
					'center' => array( 
						'name' => 'center',
					 )		
				 )
			 ),	
			'rounded' => array( 
				'name'        => 'rounded',
				'description' => 'Add a rounded corner',
				'default'     => 'yes',
				'values'      => array( 
					'yes' => array( 
						'name'        => 'yes',
						'description' => 'Show an outer box shadow',
						'default'     => true
					 ),
					'no' => array( 
						'name'        => 'no',
						'description' => 'Do not show the outer box shadow'
					 ),		
					'number' => array( 
						'name'        => 'number',
						'description' => 'Use any valid CSS values (eg: 11px)'
					 ),												
				 )
			 ),		
			'customstyle' => array( 
				'name'        => 'customstyle',
				'description' => 'Allows you to add custom css styles to this element',
				'default'     => 'none',
				'values'      => array( 
					'cssstyle' => array (
						'name'        => 'css styles',
						'description' => 'write css styles as if you were writing them inline into the HTML element.
							For example [button customstyle="margin:0 20px 0 0"]'
					)
				 )
			 ),
		 )
	 );	
	
	
	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	 
	// Display of the shortcode
	else {
	
		$allowed_presets = array( 'red', 'cyan', 'yellow', 'black', 'blue', 'green', 'purple', 'orange' );
		
		$class['preset'] = ( isset( $preset ) AND in_array( $preset, $allowed_presets ) ) ? $preset : 'primary';
		$class['textshadow'] = ( isset( $textshadow ) AND in_array( $textshadow, array( 'inner', 'outer' ) ) ) ? 'text-shadow-' . $textshadow : '';

		$style['border'] = ( isset( $border ) AND !empty( $border ) ) ? 'border:1px solid ' . $border : '';
		$style['background'] = ( isset( $background ) AND !empty( $background ) ) ? 'background: ' . $background : '';
		$style['color'] = ( isset( $color ) AND !empty( $color ) ) ? 'color: ' . $color : '';
		$style['color'] = ( $color == false AND $background == true ) ? 'color: ' . rf_css_text_color( $background ) : $style['color'];
		$style['textshadow'] = ( isset( $background ) AND !empty( $background ) AND isset( $textshadow ) AND in_array( $textshadow, array( 'inner', 'outer' ) ) ) ? 'text-shadow: ' . rf_css_text_shadow( $background ) : '';

		if( isset( $rounded ) AND !empty( $rounded ) ) {
			$style['radius'] = ( $rounded == 'yes' ) ? rf_css_radius( '5px' ) : rf_css_radius( $rounded );
		}
		
		
		$style['customstyle'] = ( isset( $customstyle ) AND !empty( $customstyle) ) ? $customstyle : '';

				
		$classes = implode(' ', $class);
		$styles = implode(';', $style);


		$html  = '<div class="section section-small"><div class="message '.$classes.'" style="'.$styles.'">';
		if( isset( $title ) AND !empty( $title ) ) {
			$html .= '<h5>' . $title . '</h5>';
		}
		$html .= do_shortcode( $content );
		$html .= '</div></div>';				
		return $html;
	}
}


/**
 * Toggle Creator 
 *
 * Enables users to create toggleable sections
 *
 * @param array $atts The attributes passed to the line 
 * @param string $content The text it is wrapped around
 *
 * @uses rf_shortcode_info()
 *
 */
 
function rf_toggle_shortcode( $atts, $content = null ) {

	// Extract the attributes into variables
   	extract( shortcode_atts( array( 
		'title'   => '',
		'default' => 'closed',
		'effect'  => 'slide',
		'sample'  => false,
	 ), $atts ) );
	
	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[toggle] Your toggleable content [/toggle]';
	$info['description'] = 'Enables you to create content which you can show/hide by clicking on its title';
	$info['parameters']  = array( 
		'Required Parameters' => array ( 
			'title' => array( 
				'name'        => 'title',
				'description' => 'The title of the toggleable section. The users will need to click this text to toggle the section',
				'default'     => 'none',
			 ),
		 ),
		'Optional Parameters' => array ( 

			'default' => array( 
				'name'        => 'default',
				'description' => 'The default state of the toggleable section',
				'default'     => 'closed',
				'values'      => array( 
					'open' => array( 
						'name' => 'open',
					 ),						
					'closed' => array( 
						'name'    => 'closed',
						'default' => true,
					 )		
				 )
			 ),
			'effect' => array( 
				'name' => 'effect',
				'description' => 'The effect to use when toggling the section',
				'default'     => 'slide',
				'values'      => array( 
					'slide' => array( 
						'name'        => 'slide',
						'description' => 'The content will open and close using a sliding effect',
						'default'     => true,
					 ),	
					'fade' => array( 
						'name'        => 'fade',
						'description' => 'The content will fade in and out when it is opened/closed',
					 ),
					'none' => array( 
						'name'        => 'none',
						'description' => 'No effect will be applied to the showing and hiding of content',
					 ),							
				 )
			 ),	
		 )
	 );		
	
	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	
	// Display of the shortcode
	else {
		
		$allowed_effects = array( 'fade', 'slide', 'none' );
		$effect = ( !isset( $effect ) OR empty( $effect ) OR !in_array( $effect, $allowed_effects ) ) ? 'slide' : $effect;
		
		$classStatus = ( isset( $default ) AND $default == 'open' ) ? 'open' : 'closed';
	
		$html = '<div class="toggle ' . $classStatus . '" data-effect="' . $effect . '">';
		
		if( isset( $title ) AND !empty( $title ) ) {
			$html .= '<h4>' . $title . '</h4>';
		}
		
		$html .= '<div class="toggle-content">' . do_shortcode( $content ) . '</div>';
		$html .= '</div>';
		
		return $html;
	}
}


/**
 * Buttons Creator 
 *
 * Enables users to create customizable nice looking CSS3 Buttons
 *
 * @global array $theme_options The options for this theme
 *
 * @param array $atts The attributes passed to the line 
 *
 * @uses rf_shortcode_info()
 * @uses rf_css_gradient()
 * @uses rf_hcm()
 *
 */
function rf_button_shortcode( $atts ) {
	global $theme_options;
	
	// Extract the attributes into variables
   	extract( shortcode_atts( array( 
		'title'       => 'Read more',
		'color'       => false,
		'url'         => false,
		'preset'      => false,
		'background'  => false,
		'border'      => false,	
		'boxshadow'	  => 'yes',
		'sample'      => false,
		'textshadow'  => false,
		'customstyle' => false,
		'arrow'		  => false,
		'rounded'     => '5px'
	 ), $atts ) );


	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[button]';
	$info['description'] = 'Create buttons easily using this shortcode and some of its parameters';
	$info['parameters']  = array( 
		'Optional Parameters' => array ( 
			'title' => array( 
				'name'        => 'title',
				'description' => 'The text which will be shown on the button',
				'default'     => 'Read more',
			 ),
			'url' => array( 
				'name'        => 'url',
				'description' => 'The URL where the button takes the user',
				'default'     => '# ( top of the page )',
			 ),		
			'preset' => array( 
				'name'        => 'preset',
				'description' => 'There are a number of preset buttons in this theme. If you use a preset you do not need to specify the color, background and text shadow separately to make sure the button looks good.',
				'default'     => 'theme primary color: ' . $theme_options['rfoption_colors_primary'],
				'values' => array( 
					'red' => array( 
						'name'        => 'red',
						'description' => '#D12344',
					 ),	
					'cyan' => array( 
						'name'        => 'cyan',
						'description' => '#24ACD2',
					 ),	
					'yellow' => array( 
						'name'        => 'yellow',
						'description' => '#E3C137',
					 ),	
					'black' => array( 
						'name'        => 'black',
						'description' => '#3C3C3C',
					 ),	
					'blue' => array( 
						'name'        => 'blue',
						'description' => '#458BC0',
					 ),	
					'green' => array( 
						'name'        => 'green',
						'description' => '#77AE42',
					 ),	
					'purple' => array( 
						'name'        => 'purple',
						'description' => '#B244AC',
					 ),	
					'orange' => array( 
						'name'        => 'orange',
						'description' => '#SB8E4E',
					 ),	
							
				 )
			 ),

			'color' => array( 
				'name'        => 'color',
				'description' => 'Defines the text color inside the button',
				'default'     => 'none',
				'values'      => array( 
					'color' => array( 
						'name'        => 'color',
						'description' => 'Any valid CSS color',
					 ),								
				 )
			 ),
			'background' => array( 
				'name'        => 'background',
				'description' => 'Defines the background color of the button',
				'default'     => 'none',
				'values'      => array( 
					'color' => array( 
						'name'        => 'color',
						'description' => 'Any valid CSS color',
					 ),								
				 )
			 ),	
			'border' => array( 
				'name'        => 'border',
				'description' => 'Defines the color of the button\'s border',
				'default'     => 'none',
				'values'      => array( 
					'color' => array( 
						'name'        => 'color',
						'description' => 'Any valid CSS color',
					 ),								
				 )
			 ),	
			'arrow' => array( 
				'name'        => 'arrow',
				'description' => 'Adds a white arrow to the right of the button',
				'default'     => 'none',
				'values'      => array( 							
					'yes' => array( 
						'name'        => 'yes',
						'description' => 'An arrow pointing right on the right side of the button',
					 ),		
				 )
			 ),				 	

			'rounded' => array( 
				'name'        => 'rounded',
				'description' => 'Add a rounded corner',
				'default'     => 'yes',
				'values'      => array( 
					'yes' => array( 
						'name'        => 'yes',
						'description' => 'Show an outer box shadow',
						'default'     => true
					 ),
					'no' => array( 
						'name'        => 'no',
						'description' => 'Do not show the outer box shadow'
					 ),		
					'number' => array( 
						'name'        => 'number',
						'description' => 'Use any valid CSS values (eg: 11px)'
					 ),												
				 )
			 ),	
			'customstyle' => array( 
				'name'        => 'customstyle',
				'description' => 'Allows you to add custom css styles to this element',
				'default'     => 'none',
				'values'      => array( 
					'cssstyle' => array (
						'name'        => 'css styles',
						'description' => 'write css styles as if you were writing them inline into the HTML element.
							For example [button customstyle="margin:0 20px 0 0"]'
					)
				 )
			 ),	
		 )
	 );	


	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	
	// Display of the shortcode
	else {
		$allowed_presets = array( 'red', 'cyan', 'yellow', 'black', 'blue', 'green', 'purple', 'orange' );
		
		$class['preset'] = ( isset( $preset ) AND in_array( $preset, $allowed_presets ) ) ? $preset : '';
		$class['textshadow'] = ( isset( $textshadow ) AND in_array( $textshadow, array( 'inner', 'outer' ) ) ) ? 'text-shadow-' . $textshadow : '';
		$class['custombackground'] = ( isset( $background ) AND !empty( $background ) ) ? 'custombackground' : '';

		$style['border'] = ( isset( $border ) AND !empty( $border ) ) ? 'border:1px solid ' . $border : '';
		$style['background'] = ( isset( $background ) AND !empty( $background ) ) ? 'background: ' . $background : '';
		$style['color'] = ( isset( $color ) AND !empty( $color ) ) ? 'color: ' . $color : '';
		$style['color'] = ( $color == false AND $background == true ) ? 'color: ' . rf_css_text_color( $background ) : $style['color'];
		$style['textshadow'] = ( isset( $background ) AND !empty( $background ) AND isset( $textshadow ) AND in_array( $textshadow, array( 'inner', 'outer' ) ) ) ? 'text-shadow: ' . rf_css_text_shadow( $background ) : '';
		$style['customstyle'] = ( isset( $customstyle ) AND !empty( $customstyle) ) ? $customstyle : '';
		
		if( isset( $rounded ) AND !empty( $rounded ) ) {
			$style['radius'] = ( $rounded == 'yes' ) ? rf_css_radius( '5px' ) : rf_css_radius( $rounded );
		}
		

		
		$arrowstyle = ( isset( $color ) AND !empty( $color ) ) ? 'border-left-color: ' . $color : '';
		$arrowstyle = ( $color == false AND $background == true ) ? 'border-left-color: ' . rf_css_text_color( $background ) : $arrowstyle;
		
		$classes = implode(' ', $class);
		$styles = implode(';', $style);


		$html  = '<div class="button ' . $box_shadow . ' inline-block">';
		if( isset( $url ) AND !empty( $url ) ) {
			$html .= '<a href="' . $url . '"  style="'.$styles.'" class="button-inner ' . $preset . ' overlay">';
			$html .= $title;
			
			if( isset( $arrow ) AND $arrow == 'yes' ) {
				$html .= '<span ' . $arrowstyle . ' class="buttonarrow"></span>';
			}
			
			$html .= '</a>';
		}
		else {
			$html .= '<div style="'.$styles.'" class="button-inner ' . $preset . ' overlay">';
			$html .= $title;
						if( isset( $arrow ) AND $arrow == 'yes' ) {
				$html .= '<span ' . $arrowstyle . ' class="buttonarrow"></span>';
			}
			$html .= '</div>';		
		}
		$html .= '</div>';


		return $html;
	}
}



function rf_banner_shortcode( $atts, $content ) {
	global $theme_options;
	
	// Extract the attributes into variables
   	extract( shortcode_atts( array( 
		'title'       => 'Read more',
		'preset'      => false,
		'displaced'   => 'yes',
		'centered'    => 'yes'
	 ), $atts ) );


	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[button]';
	$info['description'] = 'Create buttons easily using this shortcode and some of its parameters';
	$info['parameters']  = array( 
		'Optional Parameters' => array ( 
			'title' => array( 
				'name'        => 'title',
				'description' => 'The text which will be shown on the button',
				'default'     => 'Read more',
			 ),
			'displaced' => array( 
				'name'        => 'displaced',
				'description' => 'Set to true if the banner should be pushed up. Otherwise it will appear inline with other elements',
				'default'     => 'yes',
			 ),
			'centered' => array( 
				'name'        => 'centered',
				'description' => 'Set to true if it should be centered inside the section it\'s in',
				'default'     => 'yes',
			 ),
			'preset' => array( 
				'name'        => 'preset',
				'description' => 'There are a number of preset buttons in this theme. If you use a preset you do not need to specify the color, background and text shadow separately to make sure the button looks good.',
				'default'     => 'theme primary color: ' . $theme_options['rfoption_colors_primary'],
				'values' => array( 
					'red' => array( 
						'name'        => 'red',
						'description' => '#D12344',
					 ),	
					'cyan' => array( 
						'name'        => 'cyan',
						'description' => '#24ACD2',
					 ),	
					'yellow' => array( 
						'name'        => 'yellow',
						'description' => '#E3C137',
					 ),	
					'black' => array( 
						'name'        => 'black',
						'description' => '#3C3C3C',
					 ),	
					'blue' => array( 
						'name'        => 'blue',
						'description' => '#458BC0',
					 ),	
					'green' => array( 
						'name'        => 'green',
						'description' => '#77AE42',
					 ),	
					'purple' => array( 
						'name'        => 'purple',
						'description' => '#B244AC',
					 ),	
					'orange' => array( 
						'name'        => 'orange',
						'description' => '#SB8E4E',
					 ),	
							
				 )
			 ),
		 )
	 );	


	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	
	// Display of the shortcode
	else {
		
		$preset = ( ! isset( $preset ) OR empty( $preset) ) ? 'primary' : $preset;
		$displaced = ( isset( $displaced ) AND $displaced == 'yes' ) ? 'displaced' : '';

		$html = '';
		
		if( isset( $centered ) AND $centered == 'yes' ) {
			$html = '<div class="text-center">';
		}
		
		$html .= "
			<div class='banner " . $preset . " " . $displaced . "'>
				<div class='flaps'><div class='flap-left'></div><div class='flap-right'></div></div>
				<div class='button shadowed-element inline-block'>
					<div class='border border-" . $preset . "'>
						<div class='button-inner " . $preset . " overlay text-shadow-inner'>
							" . do_shortcode( $content ) . "
						</div>
					</div>
				</div>
			</div>
		";
		
		if( isset( $centered ) AND $centered == 'yes' ) {
			$html .= '</div>';
		}
		
		return $html;
	}
}



/**
 * Page Title Creator 
 *
 * Enables users to create page titles
 *
 * @param array $atts The attributes passed to the line 
 * @param string $content The text it is wrapped around
 *
 */
function rf_title_shortcode( $atts, $content = null ) {

	// Extract the attributes into variables
	extract( shortcode_atts( array( 
		'sample' => false,
	 ), $atts ) );
	
	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[title] Write your title here [/title]';
	$info['description'] = 'Creates a main title.';

	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	 
	// Display of the shortcode
	else {
		return '<div class="page-title"><h1>' . do_shortcode( $content ) . '</h1><div class="line"></div><div class="clear"></div></div>';
	}
}


/**
 * Mini Post Slider 
 *
 * Enables users to create a mini post slider
 *
 * @param array $atts The attributes passed to the line 
 * @param string $content The text it is wrapped around
 *
 */
function rf_postslider_shortcode( $atts, $content = null ) {
	global $post;
	$temp_post = $post;
	// Extract the attributes into variables
	extract( shortcode_atts( array( 
		'sample' => false,
		'title'  => 'Latest News',
		'category' => '',
		'postcount' => 5,
		'showimage' => 'true',
	 ), $atts ) );
	
	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[postslider]';
	$info['description'] = 'Creates a paginated post slider.';
	$info['parameters']  = array( 
		'Optional Parameters' => array ( 
			'title' => array( 
				'name'        => 'title',
				'description' => 'The text which will be shown at the top of the post slider',
				'default'     => 'Latest News',
			 ),
			'category' => array( 
				'name'        => 'category',
				'description' => 'Restrict the posts shown to a given category or list of categories (comma separated)',
				'default'     => '',			 
			 ),
			'postcount' => array( 
				'name'        => 'postcount',
				'description' => 'Set the number of posts to be shown',
				'default'     => '5',
			 ),
			'showimage' => array( 
				'name'        => 'showimage',
				'description' => 'Enable or disable images',
				'default'     => 'true',
			 ),
		)
	);

	// Documentation
	if ( $sample ) {
		$post = $temp_post;
		return rf_shortcode_info( $info, $sample );
	}
	 
	// Display of the shortcode
	else {
		$posts = get_posts( 'post_type=post&post_status=publish&posts_per_page=' . $postcount .'&category=' . $category  );
		
		if( isset( $posts ) AND !empty( $posts ) ) {
			$classImage = ( $showimage == 'true' ) ? 'has-image' : 'no-image';

			$output = '<div class="postslider rf-section ' . $classImage . '">';
			if ( isset( $title ) AND !empty( $title ) ) {
				$output .= '<div class="postslider-title"><h1>' . $title . '</h1></div>';
			}
			$output .= '<div class="postslider-slides">';
			foreach($posts as $post) {
				setup_postdata($post);
				ob_start();
				?>
				<div class='slide'>
					<?php if( $showimage == 'true' ) : ?>
						<?php if( has_post_thumbnail() ) : ?> 
							<div class='slide-image'><?php the_post_thumbnail( array(65, 65) ) ?></div>
						<?php else : ?>
							<div class='slide-image'></div>
						<?php endif ?>
					<?php endif ?>
					<div class='slide-content'>
						<h2 class='slide-title'><a href='<?php the_permalink() ?>'><?php the_title() ?></a></h2>
						<div class='slide-text'><?php echo substr( get_the_excerpt(), 0, 200 ) ?></div>
					</div>
				</div>
				<?php 
				$output .= ob_get_clean(); 
			}
			$output .= '<div class="clear"></div></div>';
			$output .= '<div class="pagination">';
				for( $i=1; $i <= count( $posts ); $i++ ) {
					$classFirst = ( $i == 1 ) ? 'current' : '';
					$output .= '<span class="page ' . $classFirst . '" data-target="' . $i . '">' . $i . '</span>';
				}
			$output .= '</div>';
			$output .= '</div>';
		}
		$post = $temp_post;
		return $output;
	}
}



/**
 * State Creator 
 *
 * Enables users to show content based on the user's state
 *
 * @param array $atts The attributes passed to the line 
 * @param string $content The text it is wrapped around
 *
 */
add_shortcode( 'state', 'rf_state_shortcode' );


function rf_state_shortcode( $atts, $content ) {
	global $post;
	$temp_post = $post;
	// Extract the attributes into variables
	extract( shortcode_atts( array( 
		'sample' => false,
		'type' => 'guest'
	 ), $atts ) );
	
	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[state] User login state specific content here[/state]';
	$info['description'] = 'Enables the creation of content targeted at guest/logged in users.';
	$info['parameters']  = array( 
		'Optional Parameters' => array ( 
			'type' => array( 
				'name'        => 'type',
				'description' => 'The type of state the content is displayed for',
				'default'     => 'guest',
				'values'      => array( 
					'guest' => array (
						'name'        => 'guest',
						'description' => 'This type shows the content for only non-logged in users',
						'default'     => 'true'
					),
					'loggedin' => array(
						'name'        => 'loggedin',
						'description' => 'This type will show the content for only logged in users'
					)
				 )
			 ),
		)
	);
	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	
	// Display of the shortcode
	else {
		
		if( ( $type == 'guest' AND !is_user_logged_in() ) OR ( $type == 'loggedin' AND is_user_logged_in() ) ) {
			return do_shortcode( $content );
		}
		else {
			return '';
		}
		
	}
	
}




/**
 * Include a file 
 *
 * Enables users to include a file
 *
 * @param array $atts The attributes passed to the line 
 *
 */
function rf_include_shortcode( $atts ) {

	extract( shortcode_atts( array( 
		'sample' => false,
		'file'   => false
	 ), $atts ) );
	
	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[include]';
	$info['description'] = 'Allows you to include the contents of files';
	$info['parameters']  = array( 
		'Required Parameters' => array ( 
			'file' => array( 
				'name'        => 'file',
				'description' => 'The file to include, relative to the theme folder',
				'default'     => 'none',
			 ),
		)
	);

	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	
	// Display of the shortcode
	else {
		
		if( $file != false AND file_exists( get_template_directory()  . '/' . $file ) ) {
			ob_start();
			include( get_template_directory()  . '/' . $file );
			$output = ob_get_clean();
		}
		else {
			$output = '';
		}
				
		return $output;
		
	}
	
}


/**
 * Include a post list 
 *
 * Enables users to display a list of posts in side any content
 *
 * @param array $atts The attributes passed to the line 
 *
 */
function rf_postlist_shortcode( $atts ) {
	global $post;
	$temp_post = $post;
	// Extract the attributes into variables
	extract( shortcode_atts( array( 
		'sample' => false,
		'category' => '',
		'postcount' => 6,
		'columns' => 6,
		'only_with_images' => 'true',
		'showimage' => 'true',
		'showtitle' => 'true',
		'showexcerpt' => 'false',
		'forcesquare' => true,
		'align' => 'left',
		'excerpt_length' => 55,
	 ), $atts ) );
	$columns =  intval($columns);
	$colnames = array(
		1 => 'twelvecol',
		2 => 'sixcol',
		3 => 'fourcol',
		4 => 'threecol',
		6 => 'twocol',
		12 => 'onecol',
	);
			
	if( array_key_exists( $columns, $colnames ) ) {
		$colname = $colnames[$columns];
	}
	else {
		$colname = 'fourcol';
	}
		
	
	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[postlist]';
	$info['description'] = 'Creates a postlist with four columns.';
	$info['parameters']  = array( 
		'Optional Parameters' => array ( 
			'category' => array( 
				'name'        => 'category',
				'description' => 'Restrict the post list to a given category or a list of comma separated categories',
				'default'     => 'none',
			 ),
			'postcount' => array( 
				'name'        => 'postcount',
				'description' => 'The number of posts to show',
				'default'     => 'none',
			 ),
			'columns' => array( 
				'name'        => 'columns',
				'description' => 'The columns to split the posts into. Can be 1,2,3,4,6 or 12',
				'default'     => 'none',
			 ),
			'showimage' => array( 
				'name'        => 'showimage',
				'description' => 'Enable or disable the post images',
				'default'     => 'true',
			 ),
			'forcesquare' => array( 
				'name'        => 'forcesquare',
				'description' => 'Forces the image thumbnails to be square',
				'default'     => 'true',
			 ),			 
			'showtitle' => array( 
				'name'        => 'showtitle',
				'description' => 'Enable or disable the post titles',
				'default'     => 'true',
			 ),
			'showexcerpt' => array( 
				'name'        => 'showexcerpt',
				'description' => 'Enable or disable the post excerpts',
				'default'     => 'false',
			 ),
			 'excerpt_length' => array( 
				'name'        => 'excerpt_length',
				'description' => 'Modify the length of the excerpt. The maximum length is 55 words',
				'default'     => '55',
			 ),
			'only_with_images' => array( 
				'name'        => 'only_with_images',
				'description' => 'If set to true only images with featured images are retrieved',
				'default'     => 'true',
			 ),
			'align' => array( 
				'name'        => 'align',
				'description' => 'set the text alignment to left, center or right',
				'default'     => 'center',
			 ),
			 
		)
	);
	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	
	// Display of the shortcode
	else {
		
		$only_with_images = ( $only_with_images == 'true' ) ? '&meta_key=_thumbnail_id' : '';
		$category = ( isset( $category ) AND !empty( $category ) ) ? '&category=' . $category : '';
		$posts = get_posts( 'posts_per_page=' . $postcount . $category . $only_with_images );
		$output = '';
		$i=1;
		$output .= '<div class="inner-row"><div class="postlist primary-links">';
		foreach( $posts as $post ) {
			setup_postdata($post);
			$classLast = ( $i % $columns == 0 ) ? 'last' : '';
			$output .= '<div class="' . $colname . ' ' . $classLast . '">';
			ob_start();	
			?>
				<div class='text-<?php echo $align ?> item <?php echo $align ?>' id="post-<?php the_ID() ?>">
					<?php 
						$thumbnail_id = get_post_thumbnail_id();
						if( $showimage == 'true' AND $thumbnail_id ) : 
					?>
						<div class="item-image">
							<?php 
								if( isset( $thumbnail_id ) AND !empty( $thumbnail_id ) ) :
									$imagesize = ( isset( $forcesquare ) AND $forcesquare == true ) ? 'rf_large_thumb' : 'rf_large_thumb';
									$image = wp_get_attachment_image_src( $thumbnail_id, $imagesize );
							?>
								<a title='<?php the_title( 'Read "'. '"' ) ?>' href='<?php the_permalink() ?>' class='hoverfade'>
									<img src='<?php echo $image[0] ?>'>
								</a>
							<?php endif ?>
						</div>
					<?php endif ?>
					
					<?php if( $showtitle == 'true' ) : ?>
						<h1 class="item-title"><a href="<?php echo get_permalink( $post->ID ) ?>"><?php the_title() ?></a></h1>
					<?php endif ?>

					<?php if( $showexcerpt == 'true' ) : 
						ob_start();
						the_excerpt();
						$excerpt = ob_get_clean();
						if( $excerpt_length < 55 ) {
							$excerpt = explode( ' ', $excerpt );
							$excerpt = array_splice( $excerpt, 0, $excerpt_length );
							$excerpt = implode( ' ', $excerpt ) . '...';
						}
					?>
						
						<div class='item-content'>
							<?php echo $excerpt ?>
						</div>
					<?php endif ?>					
					
				</div>
			<?php 

			$items = ob_get_clean();
			$output .= $items;
				
			$output .= '</div>';
			
			if( $i % $columns == 0 ) {
				$output .= '<div class="clear"></div>';
			}
			
			$i++;
		}
		$output .= '</div><div class="clear"></div></div>';
		
		return $output;
		
	}
	
	$post = $temp_post;
 }



/**
 * Create sectioned content 
 *
 * Creates a distinct section
 *
 * @param array $atts The attributes passed to the line 
 * @param string $content The text it is wrapped around
 *
 */
function rf_section_shortcode( $atts, $content ) {
	// Extract the attributes into variables
	extract( shortcode_atts( array( 
		'sample' => false,
		'titled' => false,
		'type'   => 'light'
	 ), $atts ) );
	
	// Array describing the shortcode, used to output the documentation
	$info['title']       = '[section]';
	$info['description'] = 'Creates sectioned content.';
	$info['parameters']  = array( 
		'Optional Parameters' => array ( 
			'type' => array( 
				'name'        => 'type',
				'description' => 'Choose from a dark or light section',
				'default'     => 'light',
				'values'       => array( 
					'light' => array ( 
						'name'    => 'light',
						'default' => true,
					),
					'dark' => array(
						'name' => 'dark'
					)
				 )			
			 ),
		)
	);
	// Documentation
	if ( $sample ) {
		return rf_shortcode_info( $info, $sample );
	}
	
	// Display of the shortcode
	else {		
		$classes['type'] = ( isset( $type ) AND $type == 'dark' ) ? 'dark' : '';
		
		return '
			<div class="section ' . implode( ' ', $classes ) . '">
				<div class="border border-section">
					<div class="section-inner">
						' . do_shortcode( $content ) . '
					</div>
				</div>
			</div>			
		';
	}
	
}


?>