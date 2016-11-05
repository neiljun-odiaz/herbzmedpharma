<?php

/*-------------------------------------------------------------------------

   Plugin Name: Easy Testimonial Manager
   Description:  Easy Testimonial Manager Plugin allows you to easily display testimonials along with Photo, Name, Company, URL and social profiles.
   Version: 1.2.0
   Plugin URI: http://www.jwthemes.com
   Author: JW Themes
   Author URI: http://www.jwthemes.com
   License: Under GPL2

--------------------------------------------------------------------------*/
register_activation_hook(__FILE__, 'register_upon_activation_testimonial');

function register_upon_activation_testimonial(){//create the uploads folder upon activation
	add_option( 'jwthemes_testimonial_manager', true );

	 $upload_dir = wp_upload_dir();
	$upload_dir=$upload_dir['basedir'].""."/easy_testimonial_images/"; 

  	if (!is_dir($upload_dir)) {

		mkdir($upload_dir, 0777, true);

  	}

	//creating table upon activation

  	global $wpdb;

  	$table_name = $wpdb->prefix . "easy_testimonial_manager";

	if($wpdb->get_var("show tables like '$table_name'") != $table_name){ 


  	$sql = "CREATE TABLE $table_name (

  	id int NOT NULL AUTO_INCREMENT,

  	name varchar(50),
	job_position varchar(50),
	company varchar(50),	
  	image varchar(50),
  	url VARCHAR(60),
	description varchar(400),
	social_media varchar(470),
  	PRIMARY KEY id (id)

    );";
	}
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

   dbDelta( $sql );

  	global $wpdb;

  	$table_name = $wpdb->prefix . "easy_testimonial_setting";

	if($wpdb->get_var("show tables like '$table_name'") != $table_name){ 
  	$sql = "CREATE TABLE $table_name (

  	tid int NOT NULL AUTO_INCREMENT,
	setting varchar(370),
  	PRIMARY KEY tid (tid)
    );";

	}
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

   dbDelta( $sql );
   $wpdb->insert(''.$wpdb->prefix.'easy_testimonial_setting',array('tid'=>'1','setting'=>'easy_testimonial'),array('%s','%s'));

}

//Hooked to upgrade_clear_destination
function delete_old_plugin_easy_testimonial_manager($removed, $local_destination, $remote_destination, $plugin) {
    global $wp_filesystem;

    if ( is_wp_error($removed) )
        return $removed; //Pass errors through.

    $plugin = isset($plugin['plugin']) ? $plugin['plugin'] : '';
    if ( empty($plugin) )
        return new WP_Error('bad_request', $this->strings['bad_request']);

    $plugins_dir = $wp_filesystem->wp_plugins_dir();
    $this_plugin_dir = trailingslashit( dirname($plugins_dir . $plugin) );

    if ( ! $wp_filesystem->exists($this_plugin_dir) ) //If its already vanished.
        return $removed;

    // If plugin is in its own directory, recursively delete the directory.
    if ( strpos($plugin, '/') && $this_plugin_dir != $plugins_dir ) //base check on if plugin includes directory separator AND that its not the root plugin folder
        $deleted = $wp_filesystem->delete($this_plugin_dir, true);
    else
        $deleted = $wp_filesystem->delete($plugins_dir . $plugin);

    if ( ! $deleted )
        return new WP_Error('remove_old_failed', $this->strings['remove_old_failed']);

    return true;
}

//admin_menu_setup

add_action('admin_menu','easy_testimonial_manager_menu');

function easy_testimonial_manager_menu() {
	//this is the main item for the menu
	
	add_menu_page('Easy Testimonial', //page title
	'Easy Testimonial', //menu title
	'manage_options', //capabilities
	'testimonials', //menu slug
	'testimonials',
	 plugin_dir_url( __FILE__ ) . 'images/easy-logo-slider-icon.png'
	);
	
	add_submenu_page('null', //parent slug

	'manage testimonial', //page title

	'manage testimonial', //menu title

	'manage_options', //capability

	'easy_testimonial_create', //menu slug

	'easy_testimonial_create'); //function

	//this submenu is HIDDEN, however, we need to add it anyways

	add_submenu_page(null, //parent slug

	'Update testimonial', //page title

	'Update testimonial', //menu title

	'manage_options', //capability

	'easy_testimonial_update', //menu slug

	'easy_testimonial_update'); //function
	

	add_submenu_page(null, //parent slug

	'Remove testimonial', //page title

	'Remove testimonial', //menu title

	'manage_options', //capability

	'easy_testimonial_remove', //menu slug

	'easy_testimonial_remove'); //function
}
/*start of shortcode*/
add_shortcode('easy-testimonial-manager','easy_testimonial_manager_shortcode');//add shortcode
function easy_testimonial_manager_shortcode(){
		ob_start();?>                   
    <div class="easy_testimonial">
    			<?php global $wpdb;
                $rows=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."easy_testimonial_setting where tid = 1");
                foreach($rows as $row){$setting=unserialize($row->setting);?>
				 <style>
                    .easy_testimonial{ background:<?php if($setting['back_cl']!='')echo $setting['back_cl'];?> !important;}
					.bx-viewport{ background:<?php if($setting['back_cl']!='')echo $setting['back_cl'];?> !important;}
                    .easy_testimonial h3{ color:<?php if($setting['cl_th']!='') echo $setting['cl_th'];?> !important;}
                    .easy_testimonial a:hover{color:<?php if($setting['cl_th']!='') echo $setting['cl_th'];?> !important;}	
                    .bx-wrapper .bx-viewport {box-shadow: 0 0 0px #ccc;	border:  0!important; background: none;}	
                    .bx-wrapper .bx-pager.bx-default-pager a:hover,
                    .bx-wrapper .bx-pager.bx-default-pager a.active {background: <?php if($setting['cl_th']!='') echo $setting['cl_th'];?>;
                    }			
                </style>
            <?php }?>
            
            <ul class="testimonial_fader testimonial_fader1" style="margin:0;">
				<?php global $wpdb;
                $rows=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."easy_testimonial_manager JOIN ".$wpdb->prefix."easy_testimonial_setting
                where tid = 1");
                foreach($rows as $row){ $social_media=unserialize($row->social_media); $setting=unserialize($row->setting);?>
                <li>
                    <p style="font-size:<?php if(isset($setting['t_ft_s'])) echo $setting['t_ft_s'];?> !important;"><?php echo stripslashes($row->description);?>
                    </p><br>
                    
                     <span class="testimonial_image" style="box-shadow:0 0 1px <?php if($setting['br_cl']!='') echo $setting['br_cl'];?>; border-radius:<?php if($setting['br_rd']!='') echo $setting['br_rd'];?>;height:<?php if($setting['image_ht']!='')echo $setting['image_ht'];?>; width:<?php if($setting['image_wt']!='')echo $setting['image_wt'];?>"><a href="<?php echo $row->url;?>" style="background:url(<?php $upload_dir = wp_upload_dir();echo $upload_dir["baseurl"]."/"."easy_testimonial_images/".$row->image;?>) no-repeat center center; background-size:100% auto; display:block; height:100%;"></a><br></span>
                     
                    <h3><?php echo $row->name;?></h3>
                    
                    <h4><?php echo $row->job_position;?> at <a href="<?php echo $row->url;?>" target="_blank"><?php echo $row->company;?></a></h4> 
                    
                    <span class="jw_social_links">
           
						<?php if(isset($social_media['ind_facebook_link']) AND $social_media['ind_facebook_link']!=''){?>
                        <a href="http://www.facebook.com/<?php echo $social_media['ind_facebook_link'];?>"> 
                        <i class="fa fa-facebook"></i></a><?php }?>
                        <?php if(isset($social_media['ind_twitter_link']) AND $social_media['ind_twitter_link']!=''){?>
                        <a href="http://www.twitter.com/<?php echo $social_media['ind_twitter_link'];?>"> 
                        <i class="fa fa-twitter"></i></a><?php }?>
                        
                         <?php if(isset($social_media['ind_skype_link']) AND $social_media['ind_skype_link']!=''){?>
        
                        <a href="http://www.skype.com/<?php echo $social_media['ind_skype_link'];?>"> 
                        <i class="fa fa-skype"></i></a><?php }?>
                        <?php if(isset($social_media['ind_google_link']) AND $social_media['ind_google_link']!=''){?>
                        <a href="http://www.googleplus.com/<?php echo $social_media['ind_google_link'];?>"> 
                        <i class="fa fa-google"></i></a><?php }?>
                        
                        <?php if(isset($social_media['linkdin']) AND $social_media['linkdin']!=''){?>
                        
                        <a href="http://www.linkedin.com/<?php echo $social_media['linkdin'];?>"> 
                        <i class="fa fa-linkedin"></i></a><?php }?>
                        
                        <?php if(isset($social_media['instagram']) AND $social_media['instagram']!=''){?>
                        
                         <a href="http://www.instagram.com/<?php echo $social_media['instagram'];?>"> 
                        <i class="fa fa-instagram"></i></a><?php }?>
                        
                        <?php if(isset($social_media['youtube']) AND $social_media['youtube']!=''){?>
                         <a href="http://www.youtube.com/<?php echo $social_media['youtube'];?>"> 
                        <i class="fa fa-youtube"></i></a><?php }?>
                        
                        <?php if(isset($social_media['vimeo']) AND $social_media['vimeo']!=''){?>
                        <a href="http://www.vimeo.com/<?php echo $social_media['vimeo'];?>"> 
                        <i class="fa fa-vimeo-square"></i></a><?php }?>
                        
                        <?php if(isset($social_media['stumbleupon']) AND $social_media['stumbleupon']!=''){?>
                        <a href="http://www.stumbleupon.com/<?php echo $social_media['stumbleupon'];?>"> 
                        <i class="fa fa-stumbleupon"></i></a><?php }?>
                        
                        <?php if(isset($social_media['timblr']) AND $social_media['timblr']!=''){?>
                        <a href="http://www.tumblr.com/<?php echo $social_media['timblr'];?>"> 
                        <i class="fa fa-tumblr"></i></a><?php }?>
                        
                        <?php if(isset($social_media['digg']) AND $social_media['digg']!=''){?>
                        <a href="http://www.digg.com/<?php echo $social_media['digg'];?>"> 
                        <i class="fa fa-digg"></i></a><?php }?>
                        
                        <?php if(isset($social_media['behance']) AND $social_media['behance']!=''){?>
                        <a href="http://www.behance.com/<?php echo $social_media['behance'];?>"> 
                        <i class="fa fa-behance"></i></a><?php }?>
                        
                        <?php if(isset($social_media['foursquare']) AND $social_media['foursquare']!=''){?>
                        <a href="http://www.foursquare.com/<?php echo $social_media['foursquare'];?>"> 
                        <i class="fa fa-foursquare"></i></a><?php }?>
                        
                        <?php if(isset($social_media['delicious']) AND $social_media['delicious']!=''){?>
                        <a href="http://www.delicious.com/<?php echo $social_media['delicious'];?>"> 
                        <i class="fa fa-delicious"></i></a><?php }?>
                        <?php if(isset($social_media['reddit']) AND $social_media['reddit']!=''){?>
                        <a href="http://www.reddit.com/<?php echo $social_media['reddit'];?>"> 
                        <i class="fa fa-reddit"></i></a><?php }?>
                        <?php if(isset($social_media['wordpress']) AND $social_media['wordpress']!=''){?>
                        <a href="http://www.wordpress.com/<?php echo $social_media['wordpress'];?>"> 
                        <i class="fa fa-wordpress"></i></a><?php }?>
                    </span>
                </li>                             
                <?php }?>
            </ul>
            <div class="easy_testimonial_controls" style=" <?php if(isset($setting['sh_ctrl']) AND $setting['sh_ctrl']=="show"){?>display:visible;<?php } 
			else {?>display:none;<?php }?>">
            	<div class="prevDiv" id="proprev1"><i class="fa fa-angle-left"></i></div>
            	<div class="nextDiv" id="pronext1"><i class="fa fa-angle-right"></i></div>
            </div>
        </div>
  <?php return ob_get_clean();?>

<?php }

/*end of the shortcode*/

/* start of the widgets*/

class easy_testimonials_manager extends WP_Widget{

	function __construct(){//function will call the description of the widget and it's name

		$params=array(

			'description'=>'Display the testimonials in widgets',

			'name'=>'Easy Testimonials Manager'

		);

	parent::__construct('easy_testimonials_manager','',$params);

	}	

	public function form($instance){//create the title form

		extract($instance);?>
		<p>
        	<label for="<?php echo $this->get_field_id('title');?>">Title</label>

            <input 

            	class="widefat"

                id="<?php echo $this->get_field_id('title');?>"

                name="<?php echo $this->get_field_name('title');?>"

                value="<?php if(isset($title)) echo esc_attr($title); ?>" />
		</p>

	<?php }
	public function widget($args,$instance){

		extract($args);

		extract($instance);

		echo $before_widget;?>
        
    	<div class="easy_testimonial">
    		<h2><?php echo $before_title.$title.$after_title;?></h2>
    			<?php global $wpdb;
                $rows=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."easy_testimonial_setting where tid = 1");
                foreach($rows as $row){$setting=unserialize($row->setting);?>
             <style>
                .easy_testimonial{ background:<?php if($setting['back_cl']!='')echo $setting['back_cl'];?> !important;}
                .easy_testimonial h3{ color:<?php if($setting['cl_th']!='') echo $setting['cl_th'];?> !important;}
                .easy_testimonial a:hover{color:<?php if($setting['cl_th']!='') echo $setting['cl_th'];?> !important;}	
				.bx-wrapper .bx-viewport {
					box-shadow: 0 0 0px #ccc;
					border:  0!important;
					background: none;
				}
            </style>         
            <?php }?>
            
            <ul class="testimonial_fader testimonial_fader2" style="margin:0 !important;">
				<?php global $wpdb;
                $rows=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."easy_testimonial_manager JOIN ".$wpdb->prefix."easy_testimonial_setting
                where tid = 1");
                foreach($rows as $row){ $social_media=unserialize($row->social_media); $setting=unserialize($row->setting);?>
                <li>
                    <p style="font-size:<?php if(isset($setting['t_ft_s'])) echo $setting['t_ft_s'];?> !important;"><?php echo stripslashes($row->description);?>
                    </p><br>
                    
                     <span class="testimonial_image" style="box-shadow:0 0 1px <?php if($setting['br_cl']!='') echo $setting['br_cl'];?>; border-radius:<?php if($setting['br_rd']!='') echo $setting['br_rd'];?>;height:<?php if($setting['image_ht']!='')echo $setting['image_ht'];?>; width:<?php if($setting['image_wt']!='')echo $setting['image_wt'];?>"><a href="<?php echo $row->url;?>" style="background:url(<?php $upload_dir = wp_upload_dir();echo $upload_dir["baseurl"]."/"."easy_testimonial_images/".$row->image;?>) no-repeat center center; background-size:100% auto; display:block; height:100%;"></a><br></span>
                     
                    <h3><?php echo $row->name;?></h3>
                    
                    <h4><?php echo $row->job_position;?> at <a href="<?php echo $row->url;?>" target="_blank"><?php echo $row->company;?></a></h4> 
                    

                    <span class="jw_social_links">
           
						<?php if(isset($social_media['ind_facebook_link']) AND $social_media['ind_facebook_link']!=''){?>
                        <a href="http://www.facebook.com/<?php echo $social_media['ind_facebook_link'];?>"> 
                        <i class="fa fa-facebook"></i></a><?php }?>
                        <?php if(isset($social_media['ind_twitter_link']) AND $social_media['ind_twitter_link']!=''){?>
                        <a href="http://www.twitter.com/<?php echo $social_media['ind_twitter_link'];?>"> 
                        <i class="fa fa-twitter"></i></a><?php }?>
                        
                         <?php if(isset($social_media['ind_skype_link']) AND $social_media['ind_skype_link']!=''){?>
        
                        <a href="http://www.skype.com/<?php echo $social_media['ind_skype_link'];?>"> 
                        <i class="fa fa-skype"></i></a><?php }?>
                        <?php if(isset($social_media['ind_google_link']) AND $social_media['ind_google_link']!=''){?>
                        <a href="http://www.googleplus.com/<?php echo $social_media['ind_google_link'];?>"> 
                        <i class="fa fa-google"></i></a><?php }?>
                        
                        <?php if(isset($social_media['linkdin']) AND $social_media['linkdin']!=''){?>
                        
                        <a href="http://www.linkedin.com/<?php echo $social_media['linkdin'];?>"> 
                        <i class="fa fa-linkedin"></i></a><?php }?>
                        
                        <?php if(isset($social_media['instagram']) AND $social_media['instagram']!=''){?>
                        
                         <a href="http://www.instagram.com/<?php echo $social_media['instagram'];?>"> 
                        <i class="fa fa-instagram"></i></a><?php }?>
                        
                        <?php if(isset($social_media['youtube']) AND $social_media['youtube']!=''){?>
                         <a href="http://www.youtube.com/<?php echo $social_media['youtube'];?>"> 
                        <i class="fa fa-youtube"></i></a><?php }?>
                        
                        <?php if(isset($social_media['vimeo']) AND $social_media['vimeo']!=''){?>
                        <a href="http://www.vimeo.com/<?php echo $social_media['vimeo'];?>"> 
                        <i class="fa fa-vimeo-square"></i></a><?php }?>
                        
                        <?php if(isset($social_media['stumbleupon']) AND $social_media['stumbleupon']!=''){?>
                        <a href="http://www.stumbleupon.com/<?php echo $social_media['stumbleupon'];?>"> 
                        <i class="fa fa-stumbleupon"></i></a><?php }?>
                        
                        <?php if(isset($social_media['timblr']) AND $social_media['timblr']!=''){?>
                        <a href="http://www.tumblr.com/<?php echo $social_media['timblr'];?>"> 
                        <i class="fa fa-tumblr"></i></a><?php }?>
                        
                        <?php if(isset($social_media['digg']) AND $social_media['digg']!=''){?>
                        <a href="http://www.digg.com/<?php echo $social_media['digg'];?>"> 
                        <i class="fa fa-digg"></i></a><?php }?>
                        
                        <?php if(isset($social_media['behance']) AND $social_media['behance']!=''){?>
                        <a href="http://www.behance.com/<?php echo $social_media['behance'];?>"> 
                        <i class="fa fa-behance"></i></a><?php }?>
                        
                        <?php if(isset($social_media['foursquare']) AND $social_media['foursquare']!=''){?>
                        <a href="http://www.foursquare.com/<?php echo $social_media['foursquare'];?>"> 
                        <i class="fa fa-foursquare"></i></a><?php }?>
                        
                        <?php if(isset($social_media['delicious']) AND $social_media['delicious']!=''){?>
                        <a href="http://www.delicious.com/<?php echo $social_media['delicious'];?>"> 
                        <i class="fa fa-delicious"></i></a><?php }?>
                        <?php if(isset($social_media['reddit']) AND $social_media['reddit']!=''){?>
                        <a href="http://www.reddit.com/<?php echo $social_media['reddit'];?>"> 
                        <i class="fa fa-reddit"></i></a><?php }?>
                        <?php if(isset($social_media['wordpress']) AND $social_media['wordpress']!=''){?>
                        <a href="http://www.wordpress.com/<?php echo $social_media['wordpress'];?>"> 
                        <i class="fa fa-wordpress"></i></a><?php }?>
                    </span>
                </li>                             
                <?php }?>
            </ul>
            <div class="easy_testimonial_controls" style=" <?php if(isset($setting['sh_ctrl']) AND $setting['sh_ctrl']=="show"){?>display:visible;<?php } 
			else {?>display:none;<?php }?>">
                <div class="prevDiv" id="proprev"><i class="fa fa-angle-left"></i></div>
            	<div class="nextDiv"  id="pronext"><i class="fa fa-angle-right"></i></div>
            </div>
        </div>
        
<?php }}
//widgets function
add_action('widgets_init','easy_testimonials_manager_widgets');

function easy_testimonials_manager_widgets(){

	register_widget('easy_testimonials_manager');

}

//widgets

/*end of the widgets*/

define('ROOTDIRR', plugin_dir_path(__FILE__));
require_once(ROOTDIRR . 'inc/easy_testimonial_create.php');
require_once(ROOTDIRR . 'inc/easy_testimonial_update.php');
require_once(ROOTDIRR . 'inc/easy_testimonial_remove.php');
require_once(ROOTDIRR . 'inc/testimonials.php');

function wp_register_tesimonial_scripts(){ 
	wp_register_style( 'font-awesome_min', plugins_url( 'css/font-awesome.min.css',__FILE__ ) );
	wp_enqueue_style( 'font-awesome_min' );

	wp_register_style('testimonial_style',plugins_url( 'css/testimonial_style.css',__FILE__) );
	
	wp_enqueue_style('testimonial_style');
	
	wp_register_style('testimonial_style_admin',plugins_url( 'css/testimonial_style_admin.css',__FILE__) );
	wp_enqueue_style('testimonial_style_admin');
	
	wp_register_style('jw_jquery_bxslider',plugins_url( 'css/jquery.bxslider.css',__FILE__) );
	wp_enqueue_style('jw_jquery_bxslider');	
	
	
	wp_register_script( 'jw_jquery_bxslider',plugins_url( 'js/jquery.bxslider.min.js',__FILE__), array( 'jquery' ) );
	wp_register_script( 'jw_easy_testimonial',plugins_url( 'js/jw_easy_testimonial.js',__FILE__), array( 'jquery' ) );
	wp_register_script( 'iColorPicker',plugins_url( 'js/iColorPicker.js',__FILE__), array( 'jquery' ) );
	
	wp_enqueue_script('jw_jquery_bxslider');
	wp_enqueue_script('jw_easy_testimonial');
	wp_enqueue_script('iColorPicker');

}add_action( 'wp_print_scripts', 'wp_register_tesimonial_scripts' ); 

?>