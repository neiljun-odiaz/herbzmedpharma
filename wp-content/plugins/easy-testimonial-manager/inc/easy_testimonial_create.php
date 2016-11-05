<?php
function easy_testimonial_create () {
if(isset($_POST['insert'])){
		require_once(ABSPATH . 'wp-admin/includes/image.php');
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
		
	$upload_dir = wp_upload_dir();
	$path_name = $upload_dir['basedir'].""."/easy_testimonial_images/"; //file upload path !important
	$path=''.$path_name.'';
	$random_digit=rand(000000,999999);// produces random no
	$imgname=basename($_FILES['image']['name']);
	$imgpath=$random_digit.$imgname;
	$path=$path.$imgpath;
	global $wpdb;
	if(move_uploaded_file($_FILES['image']['tmp_name'],$path)){
	$wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."easy_testimonial_manager
	(name, job_position, company, image, url, description, social_media)
	vALUES(%s, %s, %s, %s, %s, %s, %s)",
	array(
	$name,
	$job_position,
	$company,
	$imgpath,
	$url,
	$txtdesc,
	serialize($ind_social_media)
	)));
	
	$message.="New Datails Added";
	
}}?>
<div class="wrap jw_admin_wrap">
    <style>
	.wp-editor-tabs{ float:left !important}
	.wp-editor-container {
	clear: both;
	width: 700px !important;
	}
	.wp-editor-container textarea.wp-editor-area{ border:1px solid #ddd !important}
	</style>	    
        <h2>Add New Testimonials &nbsp;&nbsp;&nbsp;&nbsp;<span class="go_back"> <a href="<?php echo admin_url('admin.php?page=testimonials');?>">
        <button class="button primary-button">Back</button></a></span></h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message;?><br/><a href="<?php echo admin_url('admin.php?page=testimonials')?>">&laquo; Back to  list</a>
        </p></div><?php endif;?>
        <form method="post" action="" enctype="multipart/form-data" class="easy_testimonial_manager_add_details">
        <table class='wp-list-table widefat fixed jw_easy_testimonial_add'>
        <tr>
        	<th style="width:150px; ">Name:</th>
            <td><input type="text" name="name" placeholder="Enter the Name..." required/></td>
        </tr>
        
        <tr>
        	<th>Job Position:</th>
            <td><input type="text" name="job_position" placeholder="Enter the Postion..." /></td>
        </tr>
        
        <tr>
        	<th>Company:</th>
            <td><input type="text" name="company" placeholder="Enter the Company Name..." /></td>
        </tr>
        
        <tr>
        	<th>Company Url:</th>
            <td><input type="text" name="url" id="url" placeholder="Enter the  link..."></td>
        </tr> 
        
        <tr>
        	<th>Image:</th>
            <td><input type="file" name="image" accept="image/*"/></td>
        </tr>
               
  		<tr>
        	<th>Description:</th>
            <td><?php wp_editor( '' , 'textarea',$settings = array('textarea_name'=>'txtarea','media_buttons' => false)); ?> </td>
        </tr>
        
        <tr>
        	<th>Social Profile:</th>
            
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
			jQuery("#facebook").hide();
			jQuery("#twitter").hide();
			jQuery("#google").hide();
			jQuery("#skype").hide();
			jQuery("#instagram").hide();
			jQuery("#youtube").hide();
			jQuery("#linkdin").hide();
			jQuery("#vimeo").hide();
			jQuery("#stumbleupon").hide();
			jQuery("#timblr").hide();
			jQuery("#digg").hide();
			jQuery("#behance").hide();
			jQuery("#foursquare").hide();
			jQuery("#delicious").hide();
			jQuery("#reddit").hide();
			jQuery("#wordpress").hide();
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
        <tr id="facebook"><th>facebook</th><td><input type="text" name="ind_facebook_link" id="ind_facebook_link">
        <input type="checkbox" name="social_media_check"></td></tr>
        <tr id="twitter"><th>Twitter</th><td><input type="text" name="ind_twitter_link" id="ind_twitter_link">
        <input type="checkbox" name="social_media_check"></td></tr>
        <tr id="google"><th>Google</th><td><input type="text" name="ind_google_link" id="ind_google_link">
        <input type="checkbox" name="social_media_check"></td></tr>
        <tr id="skype"><th>Skype</th><td><input type="text" name="ind_skype_link" id="ind_skype_link">
        <input type="checkbox" name="social_media_check"></td></tr>
        
         <tr id="instagram"><th>Instagram</th><td><input type="text" name="instagram" id="instagram">
        <input type="checkbox" name="social_media_check"></td></tr>
        
        <tr id="youtube"><th>Youtube</th><td><input type="text" name="youtube" id="youtube">
        <input type="checkbox" name="social_media_check"></td></tr>
        
        <tr id="linkdin"><th>Linkdin</th><td><input type="text" name="linkdin" id="linkdin">
        <input type="checkbox" name="social_media_check"></td></tr>
        
        <tr id="vimeo"><th>Vimeo</th><td><input type="text" name="vimeo" id="vimeo">
        <input type="checkbox" name="social_media_check"></td></tr>
        
         <tr id="stumbleupon"><th>stumbleupon</th><td><input type="text" name="stumbleupon" id="stumbleupon">
        <input type="checkbox" name="social_media_check"></td></tr>
        
        <tr id="timblr"><th>Timblr</th><td><input type="text" name="timblr" id="timblr">
        <input type="checkbox" name="social_media_check"></td></tr>
        
        <tr id="digg"><th>Digg</th><td><input type="text" name="digg" id="digg">
        <input type="checkbox" name="social_media_check"></td></tr>
        
        <tr id="foursquare"><th>foursquare</th><td><input type="text" name="foursquare" id="foursquare">
        <input type="checkbox" name="social_media_check"></td></tr>
        
         <tr id="behance"><th>behance</th><td><input type="text" name="behance" id="behance">
        <input type="checkbox" name="social_media_check"></td></tr>
        
         <tr id="delicious"><th>delicious</th><td><input type="text" name="delicious" id="delicious">
        <input type="checkbox" name="social_media_check"></td></tr>
        
        <tr id="reddit"><th>Reddit</th><td><input type="text" name="reddit" id="reddit">
        <input type="checkbox" name="social_media_check"></td></tr>
        
        <tr id="wordpress">
        	<th>Wordpress</th>
        	<td><input type="text" name="wordpress" id="wordpress"><input type="checkbox" name="social_media_check"></td>
        </tr>
        
    
    <tr><td></td><td><br /><input type='submit' name="insert" value='Save Details' class='button button-primary' style="float:left;"></td></tr>
    </table>
    </form>
</div>
<?php }?>