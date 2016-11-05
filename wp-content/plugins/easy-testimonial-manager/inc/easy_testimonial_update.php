<?php
function easy_testimonial_update () {
if(isset($_POST['update'])){	
		global $wpdb;
		$id = sanitize_text_field($_POST['id']);
		$name = sanitize_text_field($_POST["name"]);
		$job_position = sanitize_text_field($_POST['job_position']);
		$company = sanitize_text_field($_POST['company']);
		$url = sanitize_text_field($_POST['url']);
		$txtdesc = sanitize_text_field($_POST['txtarea']);
		$ind_facebook_link=mysql_real_escape_string($_POST['ind_facebook_link']);
		$ind_twitter_link=mysql_real_escape_string($_POST['ind_twitter_link']);
		$ind_google_link=mysql_real_escape_string($_POST['ind_google_link']);
		$ind_skype_link=mysql_real_escape_string($_POST['ind_skype_link']);
		$instagram=mysql_real_escape_string($_POST['instagram']);
		$youtube=mysql_real_escape_string($_POST['youtube']);
		$linkdin=mysql_real_escape_string($_POST['linkdin']);
		$vimeo=mysql_real_escape_string($_POST['vimeo']);
		$stumbleupon=mysql_real_escape_string($_POST['stumbleupon']);
		$timblr=mysql_real_escape_string($_POST['timblr']);
		$digg=mysql_real_escape_string($_POST['digg']);
		$foursquare=mysql_real_escape_string($_POST['foursquare']);
		$behance=mysql_real_escape_string($_POST['behance']);
		$delicious=mysql_real_escape_string($_POST['delicious']);
		$reddit=mysql_real_escape_string($_POST['reddit']);
		$wordpress=mysql_real_escape_string($_POST['wordpress']);
		if(isset($_POST['social_media_check'])){
			$ind_social_media=array(
			'ind_facebook_link'=> $ind_facebook_link,
			'ind_twitter_link'=> $ind_twitter_link,
			'ind_google_link'=> $ind_google_link,
			'ind_skype_link'=> $ind_skype_link,
			'instagram'=>$instagram,
			'youtube'=>$youtube,
			'linkdin'=>$linkdin,
			'vimeo'=>$vimeo,
			'stumbleupon'=>$stumbleupon,
			'timblr'=>$timblr,
			'digg'=>$digg,
			'foursquare'=>$foursquare,
			'behance'=>$behance,
			'delicious'=>$delicious,
			'reddit'=>$reddit,
			'wordpress'=>$wordpress
			);
		}
	$random_digit=rand(000000,999999);// produces random no
	$imgname=basename($_FILES['image']['name']);
	$imgpath=$random_digit.$imgname;
			
	$upload_dir = wp_upload_dir(); 
	move_uploaded_file($_FILES['image']['tmp_name'],$upload_dir['basedir'].""."/easy_testimonial_images/".$imgpath);
	$path=''.$path_name.'';
	
	$path=$path.$imgpath;		
	
	if ($_FILES['image']['name'] != '') {
	$wpdb->update(
		''.$wpdb->prefix.'easy_testimonial_manager', //table
		array('name' => $name,'job_position'=>$job_position,'company'=>$company,'image'=>$random_digit.$imgname,'url'=>$url,'description'=>$txtdesc,'social_media'=> serialize($ind_social_media)),array	( 'id' => $id ), //data
		array('%s', '%s', '%s', '%s', '%s', '%s', '%s'),array('%d') //data format			
	);
	}
	else {
		$wpdb->update(
		''.$wpdb->prefix.'easy_testimonial_manager', //table
		array('name' => $name,'job_position'=>$job_position,'company'=>$company,'url'=>$url,'description'=>$txtdesc,'social_media'=> serialize($ind_social_media)),array('id'=>$id),
		 array('%s', '%s', '%s', '%s', '%s', '%s'),array('%d') //data format			
	);
	}
}
else{//selecting value to update
	global $wpdb;	
        $rows = $wpdb->get_results("SELECT *from ".$wpdb->prefix."easy_testimonial_manager where id=".$_GET['id']);
	foreach ($rows as $s ){
		$id=$_GET['id'];
		$socia_media=unserialize($s->social_media);
		$name = esc_attr($s->name);
		$job_position = esc_attr($s->job_position);
		$company = esc_attr($s->company);
		$url = esc_url($s->url);
		$image=$s->image;
		$txtdesc = esc_textarea($s->description);
	}
}?>
    <div class="wrap jw_admin_wrap">
    <style>
	.wp-editor-tabs{ float:left !important}
	.wp-editor-container {
	clear: both;
	width: 700px !important;
	}
	.wp-editor-container textarea.wp-editor-area{ border:1px solid #ddd !important}
	</style>
	<?php if (isset($_POST['update'])) { 
        $location=admin_url('admin.php?page=testimonials');
        echo'<script> window.location="'.$location.'"; </script> ';
        }?>
    
    <h2>Add New Testimonials &nbsp;&nbsp;&nbsp;&nbsp;<span class="go_back"> <a href="<?php echo admin_url('admin.php?page=testimonials');?>">
    <button class="button primary-button">Back</button></a></span></h2>
    
    <?php if (isset($_POST['update'])): ?><div class="updated"><p><?php echo update;?><br/>
        <a href="<?php echo admin_url('admin.php?page=testimonials')?>">		&laquo; Back to  list</a>
        </p></div><?php endif;?>
        
        <form method="post" action="" enctype="multipart/form-data" class="easy_testimonial_manager_add_details">
        
        	<table class='wp-list-table widefat fixed jw_easy_testimonial_add'>
                <tr>
                    <th style="width:150px;">Name:</th>
                    <td><input type="text" name="name" value="<?php echo $name;?>" placeholder="Enter the Name..." required/></td>
                </tr>
                
                <tr>
                    <th>Job Position</th>
                    <td><input type="text" name="job_position" value="<?php echo $job_position;?>" placeholder="Enter the Postion..." /></td>
                </tr>
                
                <tr>
                    <th>Company</th>
                    <td><input type="text" name="company" value="<?php echo $company;?>" placeholder="Enter the Company Name..." /></td>
                </tr>
                
                <tr>
                    <th>Image:</th>
                    <td><input type="file" name="image" accept="image/*"/></td>
                </tr>
                <tr>
                    <th></th>
                    <td><?php if($image!=''){?><img src='<?php $upload_dir = wp_upload_dir();
					echo $upload_dir["baseurl"]."/"."easy_testimonial_images/".$image;?>' style="float:left; height:96px; width:auto;"><?php } else {?>
                 <img src='<?php echo plugins_url('easy-testimonial-manager/images/');?>'><?php }?></td>
                 </tr>
                 
                <tr>
                    <th>URL</th>
                    <td><input type="text" name="url" value="<?php echo $url;?>" id="url" placeholder="Enter the  link..."></td>
                </tr>
                
                <tr>
                    <th>Description</th>
                    <td><?php wp_editor($txtdesc, 'textarea', $settings = array('textarea_name'=>'txtarea','media_buttons' => false) ); ?> </td>
                </tr>     
                
                <tr>
                    <th> Select</th>
                    <td class="social_dropdown">
                         <select id='purpose'>
                            <option value="0">Select Social Media</option>
                            <option value="1">Facebook</option>
                            <option value="2">Twitter</option>
                            <option value="3">Google</option>
                            <option value="4">Skype</option>
                            <option value="5">Instagram</option>
                            <option value="6">Youtube</option>
                            <option value="7">Linkdin</option>
                            <option value="8">Vimeo</option>
                            <option value="9">Stumbleupon</option>
                            <option value="10">Tumblr</option>
                            <option value="11">Digg</option>
                            <option value="12">Foursquare</option>
                            <option value="13">Behance</option>
                            <option value="14">Delicious</option>
                            <option value="15">Reddit</option>
                            <option value="16">Wordpress</option>
                        </select>
                     </td>
                </tr>
                
                <script>
                    jQuery( document ).ready(function() {
                    <?php if ($socia_media['ind_facebook_link']!=''){?>
                    	jQuery("#facebook").show();
                    <?php }
                    else {?> jQuery("#facebook").hide();<?php }?>
                    <?php if ($socia_media['ind_twitter_link']!=''){?>
                    	jQuery("#twitter").show();
                    <?php }
                    else {?>jQuery("#twitter").hide();<?php }?>
                    <?php if ($socia_media['ind_google_link']!=''){?>
                    	jQuery("#google").show();
                    <?php }
                    else {?> jQuery("#google").hide();<?php }?>
                    
                    <?php if ($socia_media['ind_skype_link']!=''){?>
                    	jQuery("#skype").show();
                    <?php }
                    else {?> jQuery("#skype").hide();<?php }?>
                    
                    <?php if ($socia_media['instagram']!=''){?>
                    	jQuery("#instagram").show();
                    <?php }
                    else {?> jQuery("#instagram").hide();<?php }?>
                    
                    <?php if ($socia_media['youtube']!=''){?>
                    	jQuery("#youtube").show();
                    <?php }
                    else {?> jQuery("#youtube").hide();<?php }?>
                    
                    <?php if ($socia_media['linkdin']!=''){?>
                    	jQuery("#linkdin").show();
                    <?php }
                    else {?> jQuery("#linkdin").hide();<?php }?>
                    <?php if ($socia_media['vimeo']!=''){?>
                    	jQuery("#vimeo").show();
                    <?php }
                    else {?> jQuery("#vimeo").hide();<?php }?>
                    
                    <?php if ($socia_media['stumbleupon']!=''){?>
                    	jQuery("#stumbleupon").show();
                    <?php }
                    else {?> jQuery("#stumbleupon").hide();<?php }?>
                    
                    <?php if ($socia_media['timblr']!=''){?>
                    	jQuery("#timblr").show();
                    <?php }
                    else {?> jQuery("#timblr").hide();<?php }?>
                    
                    <?php if ($socia_media['digg']!=''){?>
                    	jQuery("#digg").show();
                    <?php }
                    else {?> jQuery("#digg").hide();<?php }?>
                    <?php if ($socia_media['behance']!=''){?>
                    	jQuery("#behance").show();
                    <?php }
                    else {?> jQuery("#behance").hide();<?php }?>
                    <?php if ($socia_media['foursquare']!=''){?>
                    	jQuery("#foursquare").show();
                    <?php }
                    else {?> jQuery("#foursquare").hide();<?php }?>
                    <?php if ($socia_media['delicious']!=''){?>
                    	jQuery("#delicious").show();
                    <?php }
                    else {?> jQuery("#delicious").hide();<?php }?>
                    
                    <?php if ($socia_media['reddit']!=''){?>
                    	jQuery("#reddit").show();
                    <?php }
                    else {?> jQuery("#reddit").hide();<?php }?>
                    
                    <?php if ($socia_media['wordpress']!=''){?>
                    	jQuery("#wordpress").show();
                    <?php }
                    else {?> jQuery("#wordpress").hide();<?php }?>
                        });
                    	jQuery('#purpose').on('change', function () {
                    
                    if(this.value === "1"){
                        jQuery("#facebook").show();
                    }
                    else if(this.value === "2"){
                        jQuery("#twitter").show();
                    } 
                    else if(this.value === "3"){
                        jQuery("#google").show();
                    }
                    else if(this.value === "4"){
                        jQuery("#skype").show();
                    }
                    else if(this.value === "5"){
                        jQuery("#instagram").show();
                    }
                    else if(this.value === "6"){
                        jQuery("#youtube").show();																			
                    }
                    else if(this.value === "7"){
                        jQuery("#linkdin").show();
                    }
                    else if(this.value === "8"){
                        jQuery("#vimeo").show();
                    }
                    else if(this.value === "9"){
                        jQuery("#stumbleupon").show();
                    }
                    else if(this.value === "10"){
                        jQuery("#timblr").show();
                    }
                    else if(this.value === "11"){
                        jQuery("#digg").show();
                    }
                    else if(this.value === "12"){
                        jQuery("#foursquare").show();
                    }
                    else if(this.value === "13"){
                        jQuery("#behance").show();
                    }
                    else if(this.value === "14"){
                        jQuery("#delicious").show();
                    }
                    else if(this.value === "15"){
                        jQuery("#reddit").show();
                    }
                    else if(this.value === "16"){
                        jQuery("#wordpress").show();
                    }
                    });
                </script>
                <tr id="facebook">
                    <th>facebook</th>
                    <td><input type="text" name="ind_facebook_link" id="ind_facebook_link" value="<?php echo $socia_media['ind_facebook_link'];?>">
                    <input type="checkbox" name="social_media_check" <?php if ($socia_media['ind_facebook_link']!=''){?> checked <?php } else {?> disable <?php }?> >
                    </td>
                </tr>
                
                <tr id="twitter">
                    <th>Twitter</th>
                    <td>
                        <input type="text" name="ind_twitter_link" id="ind_twitter_link" value="<?php echo $socia_media['ind_twitter_link'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['ind_twitter_link']!=''){?> checked <?php } else {?> disable <?php }?>>
                    </td>
                </tr>
                
                <tr id="google">
                    <th>Google</th>
                    <td>
                        <input type="text" name="ind_google_link" id="ind_google_link" value="<?php echo $socia_media['ind_google_link'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['ind_google_link']!=''){?> checked <?php } else {?> disable <?php }?>>
                    </td>
                </tr>
                
                <tr id="skype">
                    <th>Skype</th>
                    <td>
                        <input type="text" name="ind_skype_link" id="ind_skype_link" value="<?php echo $socia_media['ind_skype_link'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['ind_skype_link']!=''){?> checked <?php } else {?> disable <?php }?>>
                    </td>
                </tr>
                
                
                 <tr id="instagram">
                    <th>Instagram</th>
                    <td>
                        <input type="text" name="instagram" id="instagram" value="<?php echo $socia_media['instagram'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['instagram']!=''){?> checked <?php } else {?> disable <?php }?>>
                    </td>
                </tr>
                
                <tr id="youtube">
                    <th>Youtube</th>
                    <td>	
                        <input type="text" name="youtube" id="youtube" value="<?php echo $socia_media['youtube'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['youtube']!=''){?> checked <?php } else {?> disable <?php }?>>
                    </td>
                </tr>
                
                <tr id="linkdin">
                    <th>Linkdin</th>
                    <td>
                        <input type="text" name="linkdin" id="linkdin" value="<?php echo $socia_media['linkdin'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['linkdin']!=''){?> checked <?php } else {?> disable <?php }?> >
                    </td>
                </tr>
                
                <tr id="vimeo">
                    <th>Vimeo</th>
                    <td>
                        <input type="text" name="vimeo" id="vimeo" value="<?php echo $socia_media['vimeo'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['vimeo']!=''){?> checked <?php } else {?> disable <?php }?>>
                     </td>
                 </tr>
                
                 <tr id="stumbleupon">
                    <th>stumbleupon</th>
                    <td>
                        <input type="text" name="stumbleupon" id="stumbleupon" value="<?php echo $socia_media['stumbleupon'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['stumbleupon']!=''){?> checked <?php } else {?> disable <?php }?>>
                    </td>
                </tr>
                
                <tr id="timblr">
                <th>Timblr</th>
                    <td>
                        <input type="text" name="timblr" id="timblr" value="<?php echo $socia_media['timblr'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['timblr']!=''){?> checked <?php } else {?> disable <?php }?>>
                    </td>
                </tr>
                
                <tr id="digg">
                    <th>Digg</th>
                    <td>
                        <input type="text" name="digg" id="digg" value="<?php echo $socia_media['digg'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['digg']!=''){?> checked <?php } else {?> disable <?php }?>>
                    </td>
                </tr>
                
                <tr id="foursquare">
                    <th>foursquare</th>
                    <td>
                        <input type="text" name="foursquare" id="foursquare" value="<?php echo $socia_media['foursquare'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['foursquare']!=''){?> checked <?php } else {?> disable <?php }?>>
                    </td>
                </tr>
                
                 <tr id="behance">
                    <th>behance</th>
                    <td>
                        <input type="text" name="behance" id="behance" value="<?php echo $socia_media['behance'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['behance']!=''){?> checked <?php } else {?> disable <?php }?>>
                    </td>
                </tr>
                
                 <tr id="delicious">
                    <th>delicious</th>
                    <td>
                        <input type="text" name="delicious" id="delicious" value="<?php echo $socia_media['delicious'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['delicious']!=''){?> checked <?php } else {?> disable <?php }?>>
                    </td>
                </tr>
                
                <tr id="reddit">
                    <th>Reddit</th>
                    <td>
                        <input type="text" name="reddit" id="reddit" value="<?php echo $socia_media['reddit'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['reddit']!=''){?> checked <?php } else {?> disable <?php }?>>
                    </td>
                </tr>
                
                <tr id="wordpress">
                    <th>Wordpress</th>
                    <td>
                        <input type="text" name="wordpress" id="wordpress" value="<?php echo $socia_media['wordpress'];?>">
                        <input type="checkbox" name="social_media_check" <?php if ($socia_media['wordpress']!=''){?> checked <?php } else {?> disable <?php }?>>
                    </td>
                </tr>
            
               <tr>
                    <td>
                        <input type="hidden" value="<?php echo $_GET['id'];?>" name="id" id="id">
                    </td>
               </tr>
                
                <tr>
                    <td></td>
                    <td><input type='submit' name="update" value='&nbsp; Save Details &nbsp;' class='button button-primary' style="float:left;">
                    </td>
                </tr>
            </table>
        </form>
</div>
<?php }?>