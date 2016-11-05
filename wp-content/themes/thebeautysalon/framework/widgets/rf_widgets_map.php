<?php
/**
 * Map Widget
 * 
 * This file handles the admin and the frontend display of the map
 * widget. This widget allows you to add a map to your sidebar
 * very easily with numerous options to choose from
 *
 * @author Daniel Pataki <daniel@redfactory.nl>
 * @package Skeleton
 *
 */



/**
 * Hook the widget into WordPress
 */
add_action( 'widgets_init', 'rf_widgets_map' );
 
/**
 * Register the map widget
 */
function rf_widgets_map() {
	register_widget( 'rf_widgets_map' );
}

/**
 * Map widget admin and frontend controller
 */
class rf_widgets_map extends WP_Widget {

	/**
	 * Basic widget setup 
	 */
	function rf_widgets_map() {
		$widget_options = array( 
			'classname'   => 'rf_widgets_map', 
			'description' => 'A widget that displays a google map.'
		);
		$control_options = array( 
			'width'   => 300, 
			'height'  => 250, 
			'id_base' => 'rf_widgets_map' 
		);
		$this->WP_Widget( 'rf_widgets_map', 'Custom Maps Widget', $widget_options, $control_options );
	}


	/**
	 * Widget frontend display 
	 *
	 * @param array $args The arguments passed to the widget
	 * @param array $instance The instance data for this instance of the widget
	 *
	 */
	function widget( $args, $instance ) {
		global $wpdb;
		extract( $args );

		// Our variables from the widget settings		
		
		$instance['zoom_ctrl']       =         ( isset($instance['zoom_ctrl']) AND !empty($instance['zoom_ctrl'] ) )       ? 'true' : 'false'; 
		$instance['drag_ctrl']       =         ( isset($instance['drag_ctrl']) AND !empty($instance['drag_ctrl'] ) )       ? 'true' : 'false'; 
		$instance['map_typ_ctrl']    =         ( isset($instance['map_typ_ctrl']) AND !empty($instance['map_typ_ctrl'] ) )    ? 'true' : 'false';
		$instance['scale_ctrl']      =         ( isset($instance['scale_ctrl']) AND !empty($instance['scale_ctrl'] ) )      ? 'true' : 'false';
		$instance['streetview_ctrl'] =         ( isset($instance['streetview_ctrl']) AND !empty($instance['streetview_ctrl'] ) ) ? 'true' : 'false';
		$instance['overview_ctrl']   =         ( isset($instance['overview_ctrl']) AND !empty($instance['overview_ctrl'] ) )   ? 'true' : 'false';
		$instance['in_map_scroll']   =         ( isset($instance['in_map_scroll']) AND !empty($instance['in_map_scroll'] ) )   ? 'false' : 'true';
		$instance['in_map_drag']     =         ( isset($instance['in_map_drag']) AND !empty($instance['in_map_drag'] ) )     ? 'false' : 'true';
		$instance['marker_icon']     =         ( isset($instance['marker_icon']) AND !empty($instance['marker_icon'] ) )     ? '"'.$instance['marker_icon'].'"' : 'marker';
		$instance['geocode']         =         ( isset($instance['geocode']) AND !empty($instance['geocode'] ) )     ?  $instance['geocode'] : '';
		
		?>
        <?php 		
        	echo $before_widget;
        ?>
 
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		
		<script type="text/javascript">
			jQuery(document).ready(function($){              
				var myLatlng = new google.maps.LatLng(<?php echo $instance['geocode'] ?>);
				var myOptions = {
					zoom               : <?php echo $instance['zoom'] ?>,
					panControl         : <?php echo $instance['drag_ctrl'] ?>,
					zoomControl        : <?php echo $instance['zoom_ctrl'] ?>,
					mapTypeControl     : <?php echo $instance['map_typ_ctrl'] ?>,
					scaleControl       : <?php echo $instance['scale_ctrl'] ?>,
					streetViewControl  : <?php echo $instance['streetview_ctrl'] ?>,
					overviewMapControl : <?php echo $instance['overview_ctrl'] ?>,
				    scrollwheel        : <?php echo $instance['in_map_scroll'] ?>,
				    draggable          : <?php echo $instance['in_map_drag'] ?>,
					disableDefaultUI   : true,
					center             : myLatlng,
					mapTypeId          : google.maps.MapTypeId.<?php echo $instance['map_type'] ?>
				}
				var map = new google.maps.Map(document.getElementById("map_canvas_<?php echo str_replace( ',', '_', $instance['geocode']) ?>"), myOptions);
				
				var marker = new google.maps.Marker({
					icon      : <?php echo $instance['marker_icon'] ?>,
					position  : myLatlng, 
					map       : map,
					animation : google.maps.Animation.DROP, 
					title     :"<?php echo $instance['location_title'] ?>"
				});
				
				google.maps.event.addListener(marker, 'click', toggleBounce);
						
				function toggleBounce() {
					if (marker.getAnimation() != null) {
						marker.setAnimation(null);
					} else {
						marker.setAnimation(google.maps.Animation.BOUNCE);
					}
				}
			});
		</script>
       
		<?php 		
		
		if ( isset( $instance['title'] ) AND !empty( $instance['title'] ) ) {
			echo $before_title . apply_filters( 'widget_title', $instance['title'] ) . $after_title;
		}
		
		?>
        
        <div class='rf_maps_widget'>
            <div id='map_canvas_<?php echo str_replace( ',', '_', $instance['geocode'] ) ?>' class='map_canvas'></div>
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
		$data = array();
		$data = array_map( 'strip_tags', $new_instance );

		$checkboxes = array( 'zoom_ctrl', 'drag_ctrl', 'map_typ_ctrl', 'scale_ctrl', 'streetview_ctrl', 'overview_ctrl', 'in_map_scroll', 'in_map_drag' );
		foreach ( $checkboxes as $check ) {
			$data[$check] = ( !isset( $data[$check] ) OR $data[$check] != 1 ) ? 0 : 1;
		}

			
		$location = str_replace( ' ', '+', $new_instance['location_value'] );
		$geocode = simplexml_load_file('http://maps.google.com/maps/api/geocode/xml?address=' . $location . '&sensor=false');
		$lat = floatval($geocode->result->geometry->location->lat);
		$lng = floatval($geocode->result->geometry->location->lng);
		
		if ($lat AND $lng) {
			$data['geocode'] = $lat . ',' . $lng;
		} else {
			$data['geocode'] = 'Location was NOT found! Remember: Google limits the search for geocodes, so you could try again in a couple of minutes.';
		}
	
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
			'title'            => '',
			'location_value'   => '',
			'location_title'   => 'My Location',
			'zoom'             => '14',
			'geocode'          => '',
			'zoom_ctrl'        => 0,
			'drag_ctrl'        => 0,
			'map_typ_ctrl'     => 0,
			'scale_ctrl'       => 0,
			'streetview_ctrl'  => 0,
			'overview_ctrl'    => 0,
			'in_map_scroll'    => 0,
			'in_map_drag'      => 0,
			'marker_icon'      => '',
			'map_type'         => 'ROADMAP'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ) ?>

		<p>
			<label for='<?php echo $this->get_field_id( 'title' ) ?>'>Title:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'title' ) ?>' name='<?php echo $this->get_field_name( 'title' ) ?>' value='<?php echo $instance['title'] ?>' />
		</p>
        <p>
			<label for='<?php echo $this->get_field_id( 'location_value' ) ?>'>Location:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'location_value' ) ?>' name='<?php echo $this->get_field_name( 'location_value' ) ?>' value='<?php echo $instance['location_value'] ?>' />
            <small>Geocode: <?php echo $instance['geocode'] ?></small>
		</p>
        <p>
			<label for='<?php echo $this->get_field_id( 'location_title' ) ?>'>Give a title to your location:</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'location_title' ) ?>' name='<?php echo $this->get_field_name( 'location_title' ) ?>' value='<?php echo $instance['location_title'] ?>' />
            <small>e.g. 'Our HQ', 'This is where I live' or 'Here you can find us'</small>
		</p> 
        <p>
			<label for='<?php echo $this->get_field_id( 'zoom' ) ?>'>Zoom level (1 to 22):</label>
			<input class='widefat' id='<?php echo $this->get_field_id( 'zoom' ) ?>' name='<?php echo $this->get_field_name( 'zoom' ) ?>' value='<?php echo $instance['zoom'] ?>' />
		</p>
        <p>
        	<?php $checked = ( isset( $instance['zoom_ctrl'] ) AND $instance['zoom_ctrl'] == 1 ) ? 'checked="checked"' : ''; ?>
        	<label for='<?php echo $this->get_field_id( 'zoom_ctrl' ) ?>'>Show zoom control:</label>
        	<input class='' type='checkbox' <?php echo $checked ?> id='<?php echo $this->get_field_id( 'zoom_ctrl' ) ?>' name='<?php echo $this->get_field_name( 'zoom_ctrl' ) ?>' value='1' />
        </p>
        <p>
        	<?php $checked = ( isset( $instance['drag_ctrl'] ) AND $instance['drag_ctrl'] == 1 ) ? 'checked="checked"' : ''; ?>
        	<label for='<?php echo $this->get_field_id( 'drag_ctrl' ) ?>'>Show drag control:</label>
        	<input class='' type='checkbox' <?php echo $checked ?> id='<?php echo $this->get_field_id( 'drag_ctrl' ) ?>' name='<?php echo $this->get_field_name( 'drag_ctrl' ) ?>' value='1' />
        </p>
        <p>
        	<?php $checked = ( isset( $instance['map_typ_ctrl'] ) AND $instance['map_typ_ctrl'] == 1 ) ? 'checked="checked"' : ''; ?>
        	<label for='<?php echo $this->get_field_id( 'map_typ_ctrl' ) ?>'>Show map typ control:</label>
        	<input class='' type='checkbox' <?php echo $checked ?> id='<?php echo $this->get_field_id( 'map_typ_ctrl' ) ?>' name='<?php echo $this->get_field_name( 'map_typ_ctrl' ) ?>' value='1' />
        </p>
        <p>
        	<?php $checked = ( isset( $instance['scale_ctrl'] ) AND $instance['scale_ctrl'] == 1 ) ? 'checked="checked"' : ''; ?>
        	<label for='<?php echo $this->get_field_id( 'scale_ctrl' ) ?>'>Show scale control:</label>
        	<input class='' type='checkbox' <?php echo $checked ?> id='<?php echo $this->get_field_id( 'scale_ctrl' ) ?>' name='<?php echo $this->get_field_name( 'scale_ctrl' ) ?>' value='1' />
        </p>     
        <p>
        	<?php $checked = ( isset( $instance['streetview_ctrl'] ) AND $instance['streetview_ctrl'] == 1 ) ? 'checked="checked"' : ''; ?>
        	<label for='<?php echo $this->get_field_id( 'streetview_ctrl' ) ?>'>Show streetview control:</label>
        	<input class='' type='checkbox' <?php echo $checked ?> id='<?php echo $this->get_field_id( 'streetview_ctrl' ) ?>' name='<?php echo $this->get_field_name( 'streetview_ctrl' ) ?>' value='1' />
        </p>              
        <p>
        	<?php $checked = ( isset( $instance['overview_ctrl'] ) AND $instance['overview_ctrl'] == 1 ) ? 'checked="checked"' : ''; ?>
        	<label for='<?php echo $this->get_field_id( 'overview_ctrl' ) ?>'>Show overview control:</label>
        	<input class='' type='checkbox' <?php echo $checked ?> id='<?php echo $this->get_field_id( 'overview_ctrl' ) ?>' name='<?php echo $this->get_field_name( 'overview_ctrl' ) ?>' value='1' />
        </p>
        <p>
        	<?php $checked = ( isset( $instance['in_map_scroll'] ) AND $instance['in_map_scroll'] == 1 ) ? 'checked="checked"' : ''; ?>
        	<label for='<?php echo $this->get_field_id( 'in_map_scroll' ) ?>'>Disable in-map scrolling:</label>
        	<input class='' type='checkbox' <?php echo $checked ?> id='<?php echo $this->get_field_id( 'in_map_scroll' ) ?>' name='<?php echo $this->get_field_name( 'in_map_scroll' ) ?>' value='1' />
        </p>
        <p>
        	<?php $checked = ( isset( $instance['in_map_drag'] ) AND $instance['in_map_drag'] == 1 ) ? 'checked="checked"' : ''; ?>
        	<label for='<?php echo $this->get_field_id( 'in_map_drag' ) ?>'>Disable in-map dragging:</label>
        	<input class='' type='checkbox' <?php echo $checked ?> id='<?php echo $this->get_field_id( 'in_map_drag' ) ?>' name='<?php echo $this->get_field_name( 'in_map_drag' ) ?>' value='1' />
        </p>        
		<p>
			<label for='<?php echo $this->get_field_id( 'marker_icon' ) ?>'>Upload own marker icon:</label>
			<input class='widefat upload_field' id='<?php echo $this->get_field_id( 'marker_icon' ) ?>' name='<?php echo $this->get_field_name( 'marker_icon' ) ?>' value='<?php echo $instance['marker_icon'] ?>' />
            <small><input class='upload_button' type='button' value='Browse' /></small><div class='clearfix'></div>
		</p>       
        <p>
			<label for='<?php echo $this->get_field_id( 'map_type' ) ?>'>Map type:</label>
            <select name='<?php echo $this->get_field_name( 'map_type' ) ?>' id='<?php echo $this->get_field_id( 'map_type' ) ?>'>
                <option <?php if ($instance['map_type']  == 'ROADMAP') { ?>selected='selected'<?php } ?> value='ROADMAP'>Roadmap</option>
              	<option <?php if ($instance['map_type'] == 'TERRAIN') { ?>selected='selected'<?php } ?> value='TERRAIN'>Terrain</option>
                <option <?php if ($instance['map_type']  == 'SATELLITE') { ?>selected='selected'<?php } ?> value='SATELLITE'>Satellite</option>
                <option <?php if ($instance['map_type']  == 'HYBRID') { ?>selected='selected'<?php } ?> value='HYBRID'>Hybrid</option>
			</select>        
        </p>
	<?php
	}
}
?>