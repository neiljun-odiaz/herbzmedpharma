<?php
/**
 * Twitter Widget
 * 
 * This file handles the admin and the frontend display of the twitter
 * widget. This widget allows you to display latest tweets from a 
 * Twitter user 
 *
 * @author Daniel Pataki <daniel@redfactory.nl>
 * @package Skeleton
 *
 */




/**
 * Hook the widget into WordPress
 */
add_action( 'widgets_init', 'rf_twitter_widget' );

 
/**
 * Register the twitter widget
 */
function rf_twitter_widget() {
	register_widget( 'rf_twitter_widget' );
}

/**
 * Twitter widget admin and frontend controller
 */
class rf_twitter_widget extends WP_Widget {

	function rf_twitter_widget() {	
		$widget_options = array( 
			'classname'   => 'rf_twitter_widget', 
			'description' => 'A widget that displays the latest tweet of a user.'
		);
		$control_options = array( 
			'width'   => 200, 
			'height'  => 250, 
			'id_base' => 'rf_twitter_widget' 
		);
		$this->WP_Widget( 'rf_twitter_widget', 'Custom Twitter Widget', $widget_options, $control_options );
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
		$username = str_replace( '@', '', $instance['username'] );
		$count = ( !isset($instance['count'] ) OR empty( $instance['count'] ) ) ? 3 : $instance['count'];


		$twitter = get_option('rf_twitter_feed');		
		
		if( !isset( $twitter ) OR empty( $twitter ) ) {
			$feed = simplexml_load_file( "http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=" . $username . "&count=" . $count );		
			$tweets = $feed->channel; 

			ob_start();
			foreach( $tweets->item as $tweet ) {
				$text = $tweet->title;
				$output = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $text);
					echo '<li>' . $output . '</li>';			
			}
			$twitter['tweets'] = ob_get_clean();
			$twitter['time'] = time();
			
			update_option( 'rf_twitter_feed', $twitter );
		}			
	
	
		if( time() - $twitter['time']  > 900 ) {	
			$feed = simplexml_load_file( "http://api.twitter.com/1/statuses/user_timeline.rss?screen_name=" . $username . "&count=" . $count );		
			$tweets = $feed->channel; 

			ob_start();
			foreach( $tweets->item as $tweet ) {
				$text = $tweet->title;
				$output = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $text);
					echo '<li>' . $output . '</li>';			
			}
			$twitter['tweets'] = ob_get_clean();
			$twitter['time'] = time();
			update_option( 'rf_twitter_feed', $twitter );
		}
		
		
			
		echo $before_widget;

		if ( isset( $instance['title'] ) AND !empty( $instance['title'] ) ) {
			echo $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title;
		}
			
		echo '<div class="rf_twitter_widget username-' . $username . '"><ul class="secondary-links">';
		
		echo $twitter['tweets'];
		
		echo '</ul>';
		echo '<div class="follow"><img src="' . get_template_directory_uri() . '/images/twitterbird.png"> <a class="primary-link" href="http://twitter.com/' . $username.'">'.$instance['followme'].'</a></div>';
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
		$data = array_map( 'strip_tags', $new_instance );
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
			'title'    => '',
			'username' => 'redfactory',
			'count'    => 3,
			'followme' => 'follow'
		);
	
		$instance = wp_parse_args( (array) $instance, $defaults ) 
		
		?>

		<p>
			<label for='<?php echo $this->get_field_id( 'title' ) ?>'>Title:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'title' ) ?>' name='<?php echo $this->get_field_name( 'title' ) ?>' value='<?php echo $instance['title'] ?>' />
		</p>
        <p>
			<label for='<?php echo $this->get_field_id( 'username' ) ?>'>Username:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'username' ) ?>' name='<?php echo $this->get_field_name( 'username' ) ?>' value='<?php echo $instance['username'] ?>' />
		</p>
        <p>
			<label for='<?php echo $this->get_field_id( 'count' ) ?>'>Tweets to show:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'count' ) ?>' name='<?php echo $this->get_field_name( 'count' ) ?>' value='<?php echo $instance['count'] ?>' />
		</p>
        <p>
			<label for='<?php echo $this->get_field_id( 'followme' ) ?>'>Follow button title:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'followme' ) ?>' name='<?php echo $this->get_field_name( 'followme' ) ?>' value='<?php echo $instance['followme'] ?>' />
		</p>
		
	<?php
	}
}
?>