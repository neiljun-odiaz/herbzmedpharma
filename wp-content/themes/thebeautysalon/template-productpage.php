<?php
/*
Template Name: Product Page
*/

get_header();
?>
<div class='section-inner'>
							
	<div class='row'>		
		
		<div class='twelvecol' id="site-content">

		<?php 
			if( have_posts() ) : 
				the_post();	
				$postmeta = get_postmeta();
		?>
			<?php get_template_part( 'template-content', 'productpage' ) ?>

		<?php else : ?>
			<div class='content'>
				<h1><?php echo $theme_options['rfoption_error_noposts_title'] ?></h1>
				<p><?php echo $theme_options['rfoption_error_noposts_message'] ?></p>
			</div>
		<?php endif ?>								
			
		</div>
		
		
	</div>

</div>
<?php get_footer() ?>