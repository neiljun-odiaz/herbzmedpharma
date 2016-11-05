<?php
/*
Template Name: Gallery
*/
get_header();

?>

<div class='section-inner'>

<?php if( have_posts() ) : the_post();
	$postmeta = get_postmeta();
	get_template_part( 'template-content', 'gallery' );
else : 
?>

	<div class='content text-center'>
		<h1><?php echo $theme_options['rfoption_error_404_title'] ?></h1>
		<p><?php echo $theme_options['rfoption_error_404_message'] ?></p>
	</div>	

<?php endif ?>

</div>
	

<?php get_footer() ?>