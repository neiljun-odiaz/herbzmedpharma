<?php 
	function easy_testimonial_remove(){
	global $wpdb;
	if($_GET['id']){
			$id=$_GET['id'];
			$sql=$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."easy_testimonial_manager WHERE id = %s",$id));
	}
}?>