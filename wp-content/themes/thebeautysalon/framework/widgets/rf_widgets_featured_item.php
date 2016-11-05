<?php
/**
 * Featured Item Widget
 * 
 * This file handles the admin and the frontend display of the featured 
 * item widget. This widget can show a title, image, content and read more
 * link (options can be changed).
 *
 * @author Daniel Pataki <daniel@redfactory.nl>
 * @package Skeleton
 *
 */


/**
 * Hook the widget into WordPress
 */
//add_action( 'widgets_init', 'rf_widget_featured_item' );

/**
 * Register the featured item widget
 */
function rf_widget_featured_item() {
	register_widget( 'rf_widget_featured_item' );
}

/**
 * Featured item widget admin and frontend controller
 */
class rf_widget_featured_item extends WP_Widget {

	/**
	 * Basic widget setup 
	 */
	function rf_widget_featured_item() {
		$widget_options = array( 
			'classname'   => 'rf_widget_featured_item', 
			'description' => 'A widget that displays an image, text and a read-more button.'
		);
		$control_options = array( 
			'width'   => 400, 
			'height'  => 250, 
			'id_base' => 'rf_widget_featured_item' 
		);
		$this->WP_Widget( 'rf_widget_featured_item', 'Featured Item Widget', $widget_options, $control_options );
	}


	/**
	 * Widget frontend display 
	 *
	 * @param array $args The arguments passed to the widget
	 * @param array $instance The instance data for this instance of the widget
	 *
	 */
	function widget( $args, $instance ) {
		global $theme_options;
		extract( $args );	

		$hasLink = ( isset( $instance['link'] ) AND !empty( $instance['link'] ) ) ? true : false;
		$hasImage = ( isset( $instance['image'] ) AND !empty( $instance['image'] ) ) ? true : false;

		echo $before_widget;
		
		if ( isset( $instance['title'] ) AND !empty( $instance['title'] ) ) {
			echo $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title;
		}
			
		?>
		<div class='rf_widget_featured_item post-content'>
			
			<?php if ( isset( $hasImage ) AND $hasImage === true ) : ?>	
				<div class='post-image'>      
					<?php if( isset( $hasLink ) AND $hasLink === true ) : ?>
	                	<a class='hoverfade' href='<?php the_permalink() ?>' title='<?php get_the_title() ?>'>
	                    	<img src='<?php echo $instance['image'] ?>'>
		                </a>
		           	<?php else :?> 
	                    <img src='<?php echo $instance['image'] ?>'>
		            <?php endif ?>
				</div>                
            <?php endif ?>
           
            <div class='post-text'>
			<?php echo nl2br( $instance['content'] ); ?>
            </div>
            
            <?php if( isset( $hasLink ) AND $hasLink === true ) : ?>
                <a class='primary' href='<?php echo $instance['link'] ?>'><?php echo $theme_options['rfoption_readmore_text'] ?></a>
            <?php endif ?>
            
		</div>

		<?php 
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
			'title'   => '',
			'image'   => '',
			'content' => '',
			'link'    => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

		<p>
			<label for='<?php echo $this->get_field_id( 'title' ) ?>'>Title:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'title' ) ?>' name='<?php echo $this->get_field_name( 'title' ) ?>' value='<?php echo $instance['title'] ?>' />
		</p>
        <p>
			<label for='<?php echo $this->get_field_id( 'image' ) ?>'>Image:</label>
			<input class='widefat upload_field' id='<?php echo $this->get_field_id( 'image' ) ?>' name='<?php echo $this->get_field_name( 'image' ) ?>' value='<?php echo $instance['image'] ?>' />
		</p>
        <p>
			<label for='<?php echo $this->get_field_id( 'content' ) ?>'>Content:</label>
            <textarea rows='5' cols='50' name='<?php echo $this->get_field_name( 'content' ) ?>' id='<?php echo $this->get_field_id( 'content' ) ?>'><?php echo stripslashes(htmlspecialchars($instance['content'])) ?></textarea>
		</p>
        <p>
			<label for='<?php echo $this->get_field_id( 'link' ) ?>'>Link:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'link' ) ?>' name='<?php echo $this->get_field_name( 'link' ) ?>' value='<?php echo $instance['link'] ?>' />
		</p>
	<?php
	}
}
?>