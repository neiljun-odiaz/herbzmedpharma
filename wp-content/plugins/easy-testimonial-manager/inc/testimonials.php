<?php function testimonials(){?>
	<div class="wrap jw_admin_wrap">
  	<h2>Manage Testimonials</h2>
 	 <a href="<?php echo admin_url('admin.php?page=easy_testimonial_create'); ?>" class="button button-primary easy_testimonial_manager_add_image">
     Add New Testimonials</a> <br/>
  	<?php
     global $wpdb;
     $rows = $wpdb->get_results("SELECT * from ".$wpdb->prefix."easy_testimonial_manager");
	 $id = $wpdb->get_var( "SELECT id FROM ".$wpdb->prefix."easy_testimonial_manager");	
	 if($id==''){ echo "<br/>Enter the Testimonials by Clicking on above button";} else{
     echo "<table class='list-table widefat fixed easy_testimonial_manager_list'>";
     echo "<tr><th>Name</th><th>Job Position</th><th>Company</th><th>Image</th><th>URL</th><th>Description</th><th>social media</th><th>Action</th></tr>";
     foreach ($rows as $row ){ $socia_media=unserialize($row->social_media);?>
  	
    <tr class="remove_list">

    <td class="easy_testimonial_manager_title"><?php echo $row->name;?></td>
    <td class="easy_testimonial_manager_title"><?php echo $row->job_position;?></td>
    <td class="easy_testimonial_manager_title"><?php echo $row->company;?></td>

    <td class="easy_testimonial_manager_image"><img src='<?php $upload_dir = wp_upload_dir();echo $upload_dir["baseurl"]."/"."easy_testimonial_images/".$row->image;?>'></td>

    <td class="easy_testimonial_manager_url"><?php echo $row->url; ?></td>

    <td class="easy_testimonial_manager_description"><?php $str=substr($row->description,0,50);echo stripslashes($str);?></td>
    <td class="easy_testimonial_manager_social_media" style="font-size:16px;">
						<?php if($socia_media['ind_facebook_link']!=''){?>
                      	<a href="http://www.facebook.com/<?php echo $social_media['ind_facebook_link'];?>"> 
                       <i class="fa fa-facebook"></i></a><?php }?>
                        <?php if($socia_media['ind_twitter_link']!=''){?>
                       <a href="http://www.twitter.com/<?php echo $social_media['ind_twitter_link'];?>"> 
                       <i class="fa fa-twitter"></i></a><?php }?>
                       <?php if($socia_media['ind_google_link']!=''){?>
                        <a href="http://www.google.com/<?php echo $social_media['ind_google_link'];?>"> 
                       <i class="fa fa-google"></i></a><?php }?>
                       <?php if($socia_media['ind_skype_link']!=''){?>
                       <a href="http://www.skype.com/<?php echo $social_media['ind_skype_link'];?>"> 
                       <i class="fa fa-skype"></i></a><?php }?>
                       <?php if($socia_media['instagram']!=''){?>
                       <a href="http://www.skype.com/<?php echo $social_media['instagram'];?>"> 
                       <i class="fa fa-instagram"></i></a>
                       <?php }?>
                        <?php if($socia_media['youtube']!=''){?>
                       <a href="http://www.skype.com/<?php echo $social_media['youtube'];?>"> 
                       <i class="fa fa-youtube"></i></a>
                       <?php }?>
                        <?php if($socia_media['linkdin']!=''){?>
                       <a href="http://www.skype.com/<?php echo $social_media['linkdin'];?>"> 
                       <i class="fa fa-linkedin"></i></a>
                       <?php }?>
                        <?php if($socia_media['vimeo']!=''){?>
                       <a href="http://www.skype.com/<?php echo $social_media['vimeo'];?>"> 
                       <i class="fa fa-vimeo-square"></i></a>
                       <?php }?>
                       <?php if($socia_media['stumbleupon']!=''){?>
                       <a href="http://www.skype.com/<?php echo $social_media['stumbleupon'];?>"> 
                       <i class="fa fa-stumbleupon"></i></a>
                       <?php }?>
                        <?php if($socia_media['timblr']!=''){?>
                       <a href="http://www.skype.com/<?php echo $social_media['timblr'];?>"> 
                       <i class="fa fa-tumblr-square"></i></a>
                       <?php }?>
                         <?php if($socia_media['digg']!=''){?>
                       <a href="http://www.skype.com/<?php echo $social_media['digg'];?>"> 
                       <i class="fa fa-digg"></i></a>
                       <?php }?>
                        <?php if($socia_media['behance']!=''){?>
                       <a href="http://www.behance.com/<?php echo $social_media['behance'];?>"> 
                       <i class="fa fa-behance"></i></a>
                       <?php }?>
                         <?php if($socia_media['foursquare']!=''){?>
                       <a href="http://www.skype.com/<?php echo $social_media['foursquare'];?>"> 
                       <i class="fa fa-foursquare"></i></a>
                       <?php }?>
                       
                        <?php if($socia_media['delicious']!=''){?>
                       <a href="http://www.skype.com/<?php echo $social_media['delicious'];?>"> 
                       <i class="fa fa-delicious"></i></a>
                       <?php }?>
                        <?php if($socia_media['reddit']!=''){?>
                       <a href="http://www.skype.com/<?php echo $social_media['reddit'];?>"> 
                       <i class="fa fa-reddit"></i></a>
                       <?php }?>
                        <?php if($socia_media['wordpress']!=''){?>
                       <a href="http://www.skype.com/<?php echo $social_media['wordpress'];?>"> 
                       <i class="fa fa-wordpress"></i></a>
                       <?php }?></td>

    	<td class="easy_testimonial_manager_action"><a href='<?php echo admin_url('admin.php?page=easy_testimonial_update&id='.$row->id );?>' 

   	 class="button easy_testimonial_manager_details_edit" style="display:inline-block; margin:3px 0;">Edit</a>
     <a href="#" id="<?php echo $row->id;?>" class=" button easy_testimonial_manager_details_delete" style="display:inline-block; margin:3px 0;">Remove</a></td>
	<tr>
	<?php }?>
 	</table>
    </div>

    <script type="text/javascript">
	jQuery(document).ready(function($) {
	jQuery(".easy_testimonial_manager_details_delete").click(function(){
	//Save the link in a variable called element
	var element = $(this);
	//Find the id of the link that was clicked
	var del_id = element.attr("id");
	//Built a url to send
	var info = 'id=' + del_id;
 	if(confirm("Sure you want to delete this testimonial? There is NO undo!")){
	   $.ajax({
	   type: "GET",
	   url: "<?php echo admin_url('admin.php?page=easy_testimonial_remove');?>",
	   data: info,
	   success: function(){
	}
 	});
         jQuery(this).parents(".remove_list").animate({ backgroundColor: "#e74c3c" }, "fast")
		.animate({ opacity: "hide" }, "slow");
 	}
	return false;
	});
	});
	</script>
    
    <!--setting_sector-->
    <?php 
		if(isset($_POST['submit_setting'])){
		$test_fnt_size=mysql_real_escape_string($_POST['testimonial_font_size']);
		$image_height=mysql_real_escape_string($_POST['image_height']);
		$image_width=mysql_real_escape_string($_POST['image_width']);
		$border_color=mysql_real_escape_string($_POST['border_color']);
		$border_radius=mysql_real_escape_string($_POST['border_radius']);
		$color_theme=mysql_real_escape_string($_POST['color_theme']);
		$sh_control=mysql_real_escape_string($_POST['sh_control']);
		$interval=mysql_real_escape_string($_POST['interval']);
		$back_color=mysql_real_escape_string($_POST['back_color']);
		$t_setting=array(
		't_ft_s'=>$test_fnt_size,
		'image_ht'=>$image_height,
		'br_cl'=>$border_color,
		'image_wt'=>$image_width,
		'br_rd'=>$border_radius,
		'cl_th'=>$color_theme,
		'sh_ctrl'=>$sh_control,
		'intv'=>$interval,
		'back_cl'=>$back_color
		);
		$id='1';
		$sql=$wpdb->update(
		''.$wpdb->prefix.'easy_testimonial_setting', //table
		array('setting'=>serialize($t_setting)),array('tid'=> $id), //data
		array('%s','%s'));
		$message.="Your Setting Has Been Updated";
		}else{
		global $wpdb;
		$rows=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."easy_testimonial_setting where tid = 1");
		foreach($rows as $row){
			$setting=unserialize($row->setting);
		$test_fnt_size=$setting['t_ft_s'];
		$image_height=$setting['image_ht'];
		$border_color=$setting['br_cl'];
		$image_width=$setting['image_wt'];
		$border_radius=$setting['br_rd'];
		$color_theme=$setting['cl_th'];
		$sh_control=$setting['sh_ctrl'];
		$interval=$setting['intv'];
		$back_color=$setting['back_cl'];
		}	
		}?>
        
    <div class="wrap jw_admin_wrap jw_easy_testimonial_settings">
    	<h1>Setting</h1>
        <div class="shortcode_easy_testimonial"><strong>Shortcode</strong><code style="margin-left:15px; padding:10px 20px; border-radius:4px; font-size:16px;">[easy-testimonial-manager]</code></div>
         <script>var imageUrl='<?php echo plugins_url('easy-testimonial-manager/images/paint_brush_color.png');?>';</script>
        
        <!--setting_updated_message-->
        
          <?php if (isset($message)): ?><div class="updated"><p><?php echo $message;?><br/></div><?php endif;?>
          
        <form action="#" method="post">            
            <div>
            	<label class="form_caption">Background Color:</label>
                <input type="text" name="back_color" id="back_color" placeholder="#FFF" class="iColorPicker" value="<?php echo $back_color; ?>">
            </div>
            
        	<div>
            	<label class="form_caption">Testimonial Font Size:</label>
                <input type="text" name="testimonial_font_size" placeholder="22px"  value="<?php if(isset($test_fnt_size)) echo $test_fnt_size;?>"/>
            </div>
            
        	<div>
            	<label class="form_caption">Image Height:</label>
                <input type="text"name="image_height"  placeholder="100px" value="<?php if(isset($image_height))
				 echo $image_height;?>"/>
        	</div>
            
            <div>
            	<label class="form_caption">Image Width:</label>
                <input type="text"name="image_width"  placeholder="100px" value="<?php if(isset($image_width)) echo $image_width;?>"/>
            </div>
            
            <div>
            	<label class="form_caption">Image Border Color:</label>
                <input type="text" name="border_color" id="border_color" placeholder="#999" class="iColorPicker" value="<?php if(isset($border_color)) echo $border_color; ?>" class="small">
            </div>
            
            <div>
            	<label class="form_caption">Image Border Radius:</label>
           		<input type="text" name="border_radius" placeholder="50%" value="<?php if(isset($border_radius))echo $border_radius;?>">
            </div>
            
            <div>
            	<label class="form_caption">Color Theme:</label>
                <input type="text" name="color_theme" id="color_theme" placeholder="#1abc9c" class="iColorPicker" value="<?php if(isset($color_theme)) echo $color_theme;?>"/>
               
            </div>
            
            <div style="margin-top:15px;">
            	<label class="form_caption">Show/Hide Controls:</label>
                <input type="radio" name="sh_control" value="show" <?php if($sh_control=="show"){ echo "checked";} else { echo "unchecked";}?>>Show &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input type="radio" name="sh_control" value="hide" <?php if($sh_control=="hide"){ echo "checked";} else { echo "unchecked";}?>>Hide</td></tr>
            </div>
                
            <div style="margin-top:15px; margin-bottom:20px; margin-left:205px;">
            	<input type="submit" value="Save Setting" name="submit_setting" class="button button-primary"/>
            </div>
        </form> 
    </div>
<?php }}?>