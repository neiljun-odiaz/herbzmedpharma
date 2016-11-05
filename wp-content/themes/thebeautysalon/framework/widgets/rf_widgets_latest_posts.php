<?php
/**
 * Latest Posts Widget
 * 
 * This file handles the admin and the frontend display of the custom
 * latest post widget. This widget allows you to list posts based on
 * categories of your choice.
 *
 * @author Daniel Pataki <daniel@redfactory.nl>
 * @package Skeleton
 *
 */


/**
 * Hook the widget into WordPress
 */
add_action( 'widgets_init', 'rf_widgets_latest_posts' );

/**
 * Register the latest posts widget
 */
function rf_widgets_latest_posts() {
	register_widget( 'rf_widgets_latest_posts' );
}

/**
 * Latest posts widget admin and frontend controller
 */
class rf_widgets_latest_posts extends WP_Widget {

	/**
	 * Basic widget setup 
	 */
	function rf_widgets_latest_posts() {
		$widget_options = array(
			'classname'   => 'rf_widgets_latest_posts', 
			'description' => 'A widget that displays the latest posts from a category.'
		);
		
		$control_options = array( 
			'width'   => 250, 
			'height'  => 250, 
			'id_base' => 'rf_widgets_latest_posts' 
		);
		
		$this->WP_Widget( 'rf_widgets_latest_posts', 'Custom Latest Posts Widget', $widget_options, $control_options );
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

		echo $before_widget;
		
		if ( isset( $instance['title'] ) AND !empty( $instance['title'] ) ) {
			echo $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title;
		}
		
		?>
		
		<ul class='rf_widgets_latest_posts postlist'>
		
		<?php 
			global $wp_query;
			$temp_query = $wp_query;
			$temp_post = $post;
			 
			$posttype   = ( isset($instance['posttype'] ) AND !empty( $instance['posttype'] ) ) ? $instance['posttype'] : 'post';
		
			$categories = ( isset($instance['categories'] ) AND !empty( $instance['categories'] ) AND is_array( $instance['categories'] ) ) 
				? implode( ',', $instance['categories'] ) : '';
			$count = 1;
			query_posts( '&cat=' . $categories . '&posts_per_page=' . $instance['postcount'] . '&post_type='.$posttype );
			
			$has_image   = ( isset($instance['showimages'] ) AND !empty( $instance['showimages'] ) ) ? true : false;
			$has_date    = ( isset($instance['showdate'] ) AND !empty( $instance['showdate'] ) ) ? true : false;
			$has_excerpt    = ( isset($instance['showexcerpt'] ) AND !empty( $instance['showexcerpt'] ) ) ? true : false;
			
        	
        	while( have_posts() ) : the_post(); ?>
            	<li <?php post_class( 'layout-sidebar ' . $instance['layout'] ) ?>>
            	
            		<?php if( isset( $instance['layout'] ) AND $instance['layout'] == 'thumbimages' ) : ?>
            			
            			<div class='inner-row'>
	            			<div class='threecol'>
	            				<div class='image'>
	            					<a class='hoverfade' href='<?php the_permalink() ?>'><?php the_post_thumbnail( 'rf_tiny_thumb' ) ?></a>
	            				</div>
	            			</div>
	            			<div class='onecol'></div>  			
	            			<div class='eightcol last'>
	            			
		            			<h1 class='primary-links title'><a href='<?php the_permalink() ?>'><?php the_title() ?></a></h1>
		            			
		            			<?php if( $has_date == true ) : ?>
			            			<div class='meta'>
			            				<span class='date'><?php the_time( 'F m, Y' ) ?></span>
			            			</div>
		            			<?php endif ?>
		            			
		            			<?php if( $has_excerpt == true ) : ?>
		            			
			            			<div class='excerpt'>
			            				<?php 
			            					ob_start();
			            					the_excerpt();
			            					$excerpt = ob_get_clean();
			            					$excerpt = substr( $excerpt, 0, 90 );
			            					echo $excerpt;
			            				?>
			            			</div>
		            			
		            			<?php endif ?>
		            			            			
	            			</div>
	            			
	            		</div>
            			            			
            		
            		<?php else : ?>
            			
            			
            			<div class='image'>
            				<a class='hoverfade' href='<?php the_permalink() ?>'><?php the_post_thumbnail( 'rf_small_thumb' ) ?></a>
            			</div>
            			
            			<h1 class='primary-links title'><a href='<?php the_permalink() ?>'><?php the_title() ?></a></h1>
            			
            			<?php if( $has_date == true ) : ?>
	            			<div class='meta'>
	            				<span class='date'><?php the_time( 'F m, Y' ) ?></span>
	            			</div>
            			<?php endif ?>
            			
            			<?php if( $has_excerpt == true ) : ?>
            			
	            			<div class='excerpt'>
	            				<?php the_excerpt() ?>
	            			</div>
            			
            			<?php endif ?>
            			
            		<?php endif ?>
	               	                
           		</li>
        		
        		<?php 
        		$count++;
			endwhile;
       		wp_reset_query(); 
       		$wp_query = $tem_query;
			$post = $temp_post;

       	?>
		</ul>

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
		$data = array();
		$data = $new_instance;
		
		$data['postcount'] = strip_tags( $data['postcount'] );
		$data['posttype'] = ( !isset( $data['posttype'] ) OR empty( $data['posttype']) ) ? 'post' : $data['posttype'];
		$data['showdate'] = ( !isset( $data['showdate'] ) OR $data['showdate'] != 1 ) ? 0 : 1;
		$data['showexcerpt'] = ( !isset( $data['showexcerpt'] ) OR $data['showexcerpt'] != 1 ) ? 0 : 1;
		$data['layout'] =  ( isset( $data['layout'] ) AND !empty( $data['layout'] ) ) ? $data['layout'] : 'largeimages';
		
		
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
			'title'      => '',
			'postcount'  => '5',
			'showexcerpt'  => 0,
			'layout'   => 'thumbimages',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		$args = array( '_builtin' => false );
		
		$layouts = array( 'largeimages' => 'Full Width Images' , 'thumbimages' => 'Left Aligned Thumbnails' );	
		
		
	?>

		<p>
			<label for='<?php echo $this->get_field_id( 'title' ) ?>'>Title:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'title' ) ?>' name='<?php echo $this->get_field_name( 'title' ) ?>' value='<?php echo $instance['title'] ?>' />
		</p>
        <p>
        	<?php $checked = ( isset( $instance['showexcerpt'] ) AND $instance['showexcerpt'] == '1') ? 'checked="checked"' : '' ?>
        	<label for='<?php echo $this->get_field_id( 'showexcerpt' ) ?>'>Show excerpt:</label>
        	<input class='' type='checkbox' <?php echo $checked ?> id='<?php echo $this->get_field_id( 'showexcerpt' ) ?>' name='<?php echo $this->get_field_name( 'showexcerpt' ) ?>' value='1' />
        </p>       
        <p>
        	<?php $checked = ( isset( $instance['showdate'] ) AND $instance['showdate'] == '1') ? 'checked="checked"' : '' ?>
        	<label for='<?php echo $this->get_field_id( 'showdate' ) ?>'>Show dates:</label>
        	<input class='' type='checkbox' <?php echo $checked ?> id='<?php echo $this->get_field_id( 'showdate' ) ?>' name='<?php echo $this->get_field_name( 'showdate' ) ?>' value='1' />
        </p>
  
        <p>
	        <label for='<?php echo $this->get_field_id( 'posttype' ) ?>'>Post Type:</label>
	        <select id='<?php echo $this->get_field_id( 'posttype' ) ?>' name='<?php echo $this->get_field_name( 'posttype' ) ?>'>
        	<?php 
        		$posttypes = array('post' => 'Post', 'rf_product' => 'Products');
        		foreach( $posttypes as $posttype => $name )	: 
       			$selected = ( isset( $instance['posttype'] ) AND $instance['posttype'] == $posttype ) ? 'selected="selected"' : '' 
        	?>
        		<option <?php echo $selected ?> value='<?php echo $posttype ?>'><?php echo $name ?></option>
    	   	<?php endforeach ?>
    	   	</select>
        </p>  
  
        
        <p>
	        <label for='<?php echo $this->get_field_id( 'layout' ) ?>'>Layout:</label>
	        <select id='<?php echo $this->get_field_id( 'layout' ) ?>' name='<?php echo $this->get_field_name( 'layout' ) ?>'>
        	<?php 
        		foreach( $layouts as $layout => $name )	: 
       			$selected = ( isset( $instance['layout'] ) AND $instance['layout'] == $layout ) ? 'selected="selected"' : '' 
        	?>
        		<option <?php echo $selected ?> value='<?php echo $layout ?>'><?php echo $name ?></option>
    	   	<?php endforeach ?>
    	   	</select>
        </p>
        
        
        <p>
			<label for='<?php echo $this->get_field_id( 'postcount' ) ?>'>Number of posts:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'postcount' ) ?>' name='<?php echo $this->get_field_name( 'postcount' ) ?>' value='<?php echo $instance['postcount'] ?>' />
		</p>
		
        <div style='overflow:auto;width:92%;padding-right:20px;height:150px;border:1px solid #DFDFDF;display:inline-block'>
			
			<?php 
            $categories = get_categories('order_by=name&order=asc&hide_empty=0');
            if( isset( $categories ) AND !empty( $categories ) ) :
	            foreach ($categories as $cat) :
	        	    $checked = ( isset( $instance['categories'] ) AND $instance['categories'] AND in_array($cat->term_id, $instance['categories'])) ? 'checked="checked"' : '';
	        	?>
	                <input type='checkbox' <?php echo $checked ?>  name='<?php echo $this->get_field_name( 'categories' ) ?>[]' id='<?php echo $this->get_field_id( 'categories' ) ?>' value='<?php echo $cat->term_id ?>' /> <?php echo $cat->name ?><br />
            <?php endforeach; endif ?>
            
        </div>
	<?php
	}
}
?>