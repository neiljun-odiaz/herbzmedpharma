<?php
	/*
		This page handles the output of the website footer
	*/

	global $theme_options; 
?>
			
			<?php if( ! ( $theme_options['rfoption_footer_disable'] AND $theme_options['rfoption_footer_disable'] == 'yes' ) ) : ?>

				<div id='site-footer'>
					<div class='row'>
					
						<div id='footer-text' class='sixcol'>
							<?php echo stripslashes($theme_options['rfoption_footer_text']) ?>
							<a title='Vectors' href='http://www.vectors4all.net'>Vectors</a>
						</div>
						
						<div id='footer-social' class='sixcol last'>
						
							<?php if( isset( $theme_options['rfoption_social_twitter_username'] ) AND !empty( $theme_options['rfoption_social_twitter_username'] ) ) : ?> 
								<a title='Follow on Twitter' href='http://twitter.com/<?php echo $theme_options['rfoption_social_twitter_username'] ?>'><img alt='Twitter Bird'  src='<?php echo get_template_directory_uri() ?>/images/footer-twitter.png'></a>
							<?php endif ?>
							
							<?php if( isset( $theme_options['rfoption_social_facebook_url'] ) AND !empty( $theme_options['rfoption_social_facebook_url'] ) ) : ?> 
								<a title='Facebook Account' href='<?php echo $theme_options['rfoption_social_facebook_url'] ?>'><img alt='Facebook Icon' src='<?php echo get_template_directory_uri() ?>/images/footer-facebook.png'></a>
							<?php endif ?>
							
							<a title='Subscribe to the RSS feed' href='<?php echo get_bloginfo( 'rss2_url' ) ?>'><img alt='RSS Icon' src='<?php echo get_template_directory_uri() ?>/images/footer-rss.png'></a>
						</div>
	
					</div>
				</div>
				
			<?php endif ?>
			
		</div>
	</div>	

<?php wp_footer() ?>
</body>
</html>