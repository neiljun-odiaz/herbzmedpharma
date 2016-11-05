<?php
/**
 * Contact Widget
 * 
 * This file handles the admin and the frontend display of the custom 
 * contact widget. It allows you to set a number of contact points such
 * as RSS, Twitter, Facebok, FLickr, Linkedin, Phone, Email and Location. 
 *
 * @author Daniel Pataki <daniel@redfactory.nl>
 * @package Skeleton
 *
 */


/**
 * Hook the widget into WordPress
 */
add_action( 'widgets_init', 'rf_widget_contact' );

/**
 * Register the contact widget
 */
 
function rf_widget_contact() {
	register_widget( 'rf_widget_contact' );
}

/**
 * Contact widget admin and frontend controller
 */
class rf_widget_contact extends WP_Widget {

	/**
	 * Basic widget setup 
	 */
	function rf_widget_contact() {
		$widget_options = array( 
			'classname'   => 'rf_widget_contact', 
			'description' => 'A widget that displays social icons and contact info.'
		);
		$control_options = array( 
			'width'   => 200, 
			'height'  => 250, 
			'id_base' => 'rf_widget_contact' 
		);
		
		$this->WP_Widget( 'rf_widget_contact', 'Custom Contact Widget', $widget_options, $control_options );
	}


	/**
	 * Widget frontend display 
	 *
	 * @param array $args The arguments passed to the widget
	 * @param array $instance The instance data for this instance of the widget
	 *
	 */
	function widget( $args, $instance ) {
		extract( $args );

		$hasSocial = (
			( isset( $instance['rss'] )      AND !empty( $instance['rss'] ) )      OR
			( isset( $instance['twitter'] )  AND !empty( $instance['twitter'] ) )  OR
			( isset( $instance['flickr'] )   AND !empty( $instance['flickr'] ) )   OR
			( isset( $instance['facebook'] ) AND !empty( $instance['facebook'] ) ) OR
			( isset( $instance['linkedin'] ) AND !empty( $instance['linkedin'] ) ) 	
		) ? true : false;
		
		echo $before_widget;

		if ( isset( $instance['title'] ) AND !empty( $instance['title'] ) ) {
			echo $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title;
		}
			
		echo '<div class="rf_widget_contact">';
		
			if(isset( $hasSocial ) AND $hasSocial === true ) {
				echo '<div class="social-buttons">';
					
					if( isset( $instance['rss'] ) AND !empty( $instance['rss'] ) ) { 
						echo '<a href="' . get_bloginfo( 'rss2_url' ).'" target="_blank" class="round rss"></a>'; 
					}
					if( isset( $instance['twitter'] ) AND !empty( $instance['twitter'] ) ) { 
						echo '<a href="http://www.twitter.com/' . $instance['twitter'] . '" target="_blank" class="round twitter"></a>'; 
					}
					if( isset( $instance['facebook'] ) AND !empty( $instance['facebook'] ) ) { 
						echo '<a href="' . $instance['facebook'] . '" target="_blank" class="round facebook"></a>'; 
					}
					if( isset( $instance['flickr'] ) AND !empty( $instance['flickr'] ) ) { 
						echo '<a href="' . $instance['flickr'] . '" target="_blank" class="round flickr"></a>'; 
					}
					if( isset( $instance['linkedin'] ) AND !empty( $instance['linkedin'] ) ) { 
						echo '<a href="' . $instance['linkedin'] . '" target="_blank" class="round linkedin"></a>'; 
					}
					
					echo "<div class='clear'></div>";
				echo '</div>';
			}
			
			if ( isset( $instance['phone'] ) AND !empty( $instance['phone'] ) ) {
				echo '<p class="phone">' . $instance['phone'] . '</p>';
			}
			if ( isset( $instance['email'] ) AND !empty( $instance['email'] ) ) {
				echo '<p class="email">' . $instance['email'] . '</p>';

			}
			if ( isset( $instance['location'] ) AND !empty( $instance['location'] ) ) {
				echo '<p class="location">' . $instance['location'] . '</p>';

			}
		echo '</div>';

		echo $after_widget;
	}


	/**
	 * Saving Widget Data
	 *
	 * @param array $new_instance The new options we want to save
	 * @param array $old_instance The old options we had before saving
	 *
	 * @return array $data The final data we want to save
	 *
	 */
	function update( $new_instance, $old_instance ) {		
		$data = array();
		$data = array_map( 'strip_tags', $new_instance );
		
		$data['rss'] = ( !isset( $data['rss'] ) OR $data['rss'] != 1 ) ? 0 : 1;
		
		
		return $data;
	}
	
	/**
	 * Widget backend display 
	 *
	 * @param array $instance The instance data for this instance of the widget
	 *
	 */	
	function form( $instance ) {
	
		$defaults = array(
			'title'    => 'Get in touch',
			'rss'      => '1',
			'twitter'  => 'redfactory',
			'facebook' => 'http://www.facebook.com',
			'flickr'   => 'http://www.flickr.com',
			'linkedin' => 'http://www.linkedin.com',
			'phone'    => '+31 070 3123456',
			'email'    => 'info@redfactory.nl',
			'location' => 'The Hague, The Netherlands'
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	
	?>

		<p>
			<label for='<?php echo $this->get_field_id( 'title' ) ?>'>Title:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'title' ) ?>' name='<?php echo $this->get_field_name( 'title' ) ?>' value='<?php echo $instance['title'] ?>' />
		</p>

		<p>
			<?php $checked = ( isset($instance['rss']) AND $instance['rss'] == '1') ? 'checked = "checked"' : ''; ?>
        	<label for='<?php echo $this->get_field_id( 'rss' ) ?>'>Display rss button:</label>
        	<input class='widefat' type='checkbox' <?php echo $checked ?> id='<?php echo $this->get_field_id( 'rss' ) ?>' name='<?php echo $this->get_field_name( 'rss' ) ?>' value='1' />
        </p>

		<p>
			<label for='<?php echo $this->get_field_id( 'twitter' ) ?>'>Twitter username:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'twitter' ) ?>' name='<?php echo $this->get_field_name( 'twitter' ) ?>' value='<?php echo $instance['twitter'] ?>' />
		</p>
		
		<p>
			<label for='<?php echo $this->get_field_id( 'facebook' ) ?>'>Facebook url:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'facebook' ) ?>' name='<?php echo $this->get_field_name( 'facebook' ) ?>' value='<?php echo $instance['facebook'] ?>' />
		</p>
		
		<p>
			<label for='<?php echo $this->get_field_id( 'flickr' ) ?>'>Flickr url:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'flickr' ) ?>' name='<?php echo $this->get_field_name( 'flickr' ) ?>' value='<?php echo $instance['flickr'] ?>' />
		</p>
		
		<p>
			<label for='<?php echo $this->get_field_id( 'linkedin' ) ?>'>Linkedin url:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'linkedin' ) ?>' name='<?php echo $this->get_field_name( 'linkedin' ) ?>' value='<?php echo $instance['linkedin'] ?>' />
		</p>
	
		<p>
			<label for='<?php echo $this->get_field_id( 'phone' ) ?>'>Phone number:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'phone' ) ?>' name='<?php echo $this->get_field_name( 'phone' ) ?>' value='<?php echo $instance['phone'] ?>' />
		</p>
		
		<p>
			<label for='<?php echo $this->get_field_id( 'email' ) ?>'>Email:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'email' ) ?>' name='<?php echo $this->get_field_name( 'email' ) ?>' value='<?php echo $instance['email'] ?>' />
		</p>
        
        <p>
			<label for='<?php echo $this->get_field_id( 'location' ) ?>'>Location:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'location' ) ?>' name='<?php echo $this->get_field_name( 'location' ) ?>' value='<?php echo $instance['location'] ?>' />
		</p>
	<?php
	}
}
?>