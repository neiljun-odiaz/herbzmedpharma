<?php

add_action( 'wp_enqueue_scripts', 'UFOshortcodesInit');

function UFOshortcodesInit() {
	wp_enqueue_style('ufo_shortcodes_css', get_template_directory_uri() . '/ufo-shortcodes/shortcodes.css');
	//Register Google Maps Scripts
	wp_register_script('google-map-script', 'http://maps.google.com/maps/api/js?sensor=false');
	wp_register_script('google-map-shortcode',  get_template_directory_uri() . '/ufo-shortcodes/js/google.map.js' );
}

/*  Shortcodes  */
add_shortcode('half', 'half');

function half($atts, $content = null) {
    $output = do_shortcode($content);
    return '<div class="ufo-shortcode half">' . $output . '</div>';
}


add_shortcode('half_last', 'half_last');

function half_last($atts, $content = null) {
    $output = do_shortcode($content);
    return '<div class="ufo-shortcode half-last">' . $output . '</div><div class="clearboth"></div>';
}


add_shortcode('third', 'third');

function third($atts, $content = null) {
    $output = do_shortcode($content);
    return '<div class="ufo-shortcode third">' . $output . '</div>';
}


add_shortcode('third_last', 'third_last');

function third_last($atts, $content = null) {
    $output = do_shortcode($content);
    return '<div class="ufo-shortcode third-last">' . $output . '</div><div class="clearboth"></div>';
}


add_shortcode('fourth', 'fourth');

function fourth($atts, $content = null) {
    $output = do_shortcode($content);
    return '<div class="ufo-shortcode fourth">' . $output . '</div>';
}


add_shortcode('fourth_last', 'fourth_last');

function fourth_last($atts, $content = null) {
    $output = do_shortcode($content);
    return '<div class="ufo-shortcode fourth-last">' . $output . '</div><div class="clearboth"></div>';
}



add_shortcode('box', 'box');

function box($atts, $content = null) {
    return '<div class="ufo-shortcode box">' . $content . '</div>';
}


add_shortcode('hr', 'hr');

function hr($atts, $content = null) {
    return '<hr class="ufo-shortcode"/>';
}


add_shortcode('code', 'code');
  
function code($atts, $content = null) {
 
        return '<a href="javascript:void(0)" class="ufo-code-toggle">+ See The Code</a><div class="ufo-shortcode code">' . $content . '</div>';
}



add_shortcode('small_button', 'small_button');

function small_button($atts, $content = null) {
    extract(shortcode_atts(array(
        "url" => ''
    ), $atts));
    return '<a class="ufo-shortcode more-icon" href="'.$url.'">'.$content.'</a>';
}


add_shortcode('big_button', 'big_button');

function big_button($atts, $content = null) {
    extract(shortcode_atts(array(
        "url" => ''
    ), $atts));
    return '<a class="ufo-shortcode  more-icon-big" href="'.$url.'">'.$content.'</a>';
}



add_shortcode('youtube', 'youtube');

function youtube($atts, $content = null) {
    return '
<div class="ufo-shortcode" rel="showdowbox"><object width="610" height="420"><param name="movie" value="http://www.youtube.com/v/'.$content.'fs=1&amp;hl=en_US"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$content.'?fs=1&amp;hl=en_US" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="610" height="420"></embed></object></div>
<div class="clearboth"></div>';
}


add_shortcode('hilite', 'hilite');

function hilite($atts, $content = null) {
    return '<span class="ufo-shortcode hilite">' . $content . '</span>';
}



add_shortcode("googlemap", "googleMap");

function googleMap($atts, $content = null) {
   extract(shortcode_atts(array(
      "width"       =>  '480',
      "height"      =>  '480',
      "address"   =>   ''
   ), $atts));
   $src = "http://maps.google.com/maps?f=q&source=s_q&hl=en&q=".$address;
   return '<div class="ufo-shortcode google-map"><iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$src.'&amp;output=embed" class="googlemap" style="width:'.$width.'px; height:'.$height.'px;"></iframe></div>';
}

add_shortcode("map", "googleMaps");

function googleMaps($atts, $content = null) {
	wp_enqueue_script('google-map-script');
	wp_enqueue_script('google-map-shortcode');
	extract( shortcode_atts( array( 
		'address' => 'eiffel tower, paris, france', 
		'width' => "500", 
		'height' => "500", 
		'zoom' => 15), $atts));
		
		$num = rand(0,10000);
		return '<script type="text/javascript">	
					jQuery(document).ready(function() {
				  		googlemap_init("'.$address.'",'.$num.','.$zoom.');
					});
				</script>
				<div class="ufo-shortcode map">
					<div id="ufo_map_wrapper_'.$num.'" style="display: block;width:'.$width.'px;height:'.$height.'px;" class="map-container"></div>
				</div>';
}

add_shortcode('button', 'button');

function button($atts, $content = null) {
    extract(shortcode_atts(array(
      "link"       =>  '#',
      "text"      =>  'Button',
	  "size"   => '',
	  "color"  => ''
   ), $atts));
    return '<a href="'.$link.'" class="ufo-shortcode button '. $color .' ' . $size . ' ">' . $text . '</a>';
}


add_action( 'wp_footer', 'UFOshortcodesJS');

function UFOshortcodesJS() { ?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			
			jQuery('.ufo-shortcode.code').toggle();
			 
			jQuery('a.ufo-code-toggle').click(function() {
				jQuery(this).next('.code').toggle('fast', function() {
			  	});
			});
		});
	</script>
<?php }



	


function ufo_icons($atts, $content = null) {
	extract(shortcode_atts(array(
		"type" => 'earth',
		"size"   => '',
		"color"  => ''
   ), $atts));
    return '<span class="icons-'. $type .'" style="color:' . $color . ';font-size:' . $size . ';"></span>';
}
add_shortcode( 'icon', 'ufo_icons' );

function demo_icons(){
	
	// Custom Icons
	$icons = 'icons-menu,icons-earth,icons-link,icons-list,icons-cart,icons-home,icons-home-2,icons-star,icons-heart,icons-close,icons-cancel-circle,icons-google-plus,icons-feed,icons-twitter,icons-facebook,icons-vimeo2,icons-dribbble,icons-pinterest,icons-cart-2,icons-feed-2,icons-facebook-2,icons-google-plus-2,icons-twitter-2,icons-youtube,icons-vimeo2-2,icons-pinterest-2,icons-menu-2,icons-earth-2,icons-link-2,icons-close-2,icons-heart-2,icons-dribbble-2,icons-menu-3,icons-quotes-left,icons-quotes-right,icons-eye,icons-bag,icons-basket,icons-basket-2,icons-search,icons-home-3,icons-arrow-up,icons-arrow-right,icons-arrow-down,icons-arrow-left,icons-pencil,icons-palette,icons-paint-format,icons-image,icons-images,icons-images-2,icons-camera,icons-camera-2,icons-music,icons-music-2,icons-headphones,icons-play,icons-movie,icons-camera-3,icons-camera-4,icons-gamepad,icons-gamepad-2,icons-connection,icons-mic,icons-mic-2,icons-book,icons-graduation,icons-file,icons-file-download,icons-file-upload,icons-folder,icons-folder-open,icons-tag,icons-tag-2,icons-tag-3,icons-tag-4,icons-tag-5,icons-cart-3,icons-bag-2,icons-credit,icons-calculate,icons-support,icons-phone,icons-phone-2,icons-phone-3,icons-envelop,icons-pushpin,icons-location,icons-location-2,icons-compass,icons-compass-2,icons-map,icons-clock,icons-clock-2,icons-print,icons-screen,icons-mobile,icons-tablet,icons-tv,icons-cd,icons-bubble,icons-bubbles,icons-user,icons-users,icons-user-2,icons-users-2,icons-tshirt,icons-hanger,icons-busy,icons-binoculars,icons-search-2,icons-expand,icons-contract,icons-key,icons-key-2,icons-unlocked,icons-lock,icons-wrench,icons-wrench-2,icons-cogs,icons-cog,icons-screwdriver,icons-hammer,icons-aid,icons-bug,icons-pie,icons-pie-2,icons-pie-3,icons-stats,icons-bars,icons-stats-up,icons-stats-down,icons-gift,icons-gift-2,icons-food,icons-leaf,icons-cup,icons-tree,icons-apple-fruit,icons-paw,icons-steps,icons-hammer-2,icons-atom,icons-lab,icons-magnet,icons-lamp,icons-remove,icons-airplane,icons-car,icons-bus,icons-truck,icons-boat,icons-cube,icons-pyramid,icons-puzzle,icons-glasses,icons-sun-glasses,icons-switch,icons-power-cord,icons-list-2,icons-grid,icons-cloud,icons-cloud-2,icons-upload,icons-download,icons-globe,icons-link-3,icons-link-4,icons-anchor,icons-flag,icons-attachment,icons-eye-2,icons-weather-rain,icons-weather-lightning,icons-weather-snow,icons-windy,icons-fan,icons-umbrella,icons-contrast,icons-heart-3,icons-thumbs-up,icons-thumbs-down,icons-thumbs-up-2,icons-thumbs-up-3,icons-people,icons-man,icons-male,icons-woman,icons-female,icons-smiley,icons-checkmark-circle,icons-cancel-circle-2,icons-checkmark,icons-plus,icons-minus,icons-play-2,icons-pause,icons-stop,icons-backward,icons-forward,icons-volume-high,icons-volume-mute,icons-volume-mute-2,icons-arrow-up-left,icons-arrow-up-2,icons-arrow-up-right,icons-arrow-down-right,icons-arrow-down-2,icons-arrow-down-left,icons-arrow-left-2,icons-arrow-right-2,icons-radio-checked,icons-circle,icons-square,icons-checkbox-unchecked,icons-paragraph-center,icons-paragraph-left,icons-paragraph-justify,icons-paragraph-right,icons-twitter-3,icons-facebook-3,icons-google-plus-3,icons-vimeo,icons-dribbble-3,icons-wordpress,icons-wordpress-2,icons-tumblr,icons-tumblr-2,icons-paypal,icons-paypal-2,icons-chrome,icons-firefox,icons-IE,icons-opera,icons-safari,icons-android,icons-apple,icons-feed-3,icons-delicious,icons-lastfm,icons-skype,icons-windows8,icons-windows,icons-flickr,icons-picassa,icons-deviantart,icons-brush,icons-eyedropper,icons-bubble-check,icons-bubble-user,icons-bubble-quote,icons-glass,icons-bottle,icons-mug,icons-rocket,icons-briefcase,icons-bike,icons-lightning';

	$icons_array = explode(',',  $icons);

	echo "<ul class='demo-icons'>";
foreach( $icons_array as $icon ) {
	$iconName = str_replace('icons-', '', $icon);
	echo "<li>";
	echo "<span class='".$icon."'></span> <span class='type-icon'>[icon type='". $iconName ."']</span>";
	echo "</li>";
}
	echo "</ul>";
}
add_shortcode( 'demo_icons', 'demo_icons' );
?>