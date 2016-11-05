<?php
	/*
		This page handles the output of many pages where
		a separate template does not exist
	*/
	
	get_header();
?>
<div class='section-inner'>
				
	<div class='row'>		
			
			<?php 
				if( have_posts() ) : 
					while( have_posts() ) {
						the_post();
						$postmeta = get_postmeta();
						get_template_part( 'template-content', 'page' );	
					}		
			?>
				
			<?php else : ?>
				<div class='content pad'>
					<h1><?php echo $theme_options['rfoption_error_noposts_title'] ?></h1>
					<p><?php echo $theme_options['rfoption_error_noposts_message'] ?></p>
				</div>
			<?php endif ?>
					
	</div>

</div>
<?php get_footer() ?>