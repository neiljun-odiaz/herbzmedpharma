<?php
	/*
		This page handles the output of many pages where
		a separate template does not exist
	*/
	
	get_header();
	the_post();
	$postmeta = get_postmeta();
	$theme_options['rfoption_title_post'] = ( !isset( $theme_options['rfoption_title_post'] ) OR empty( $theme_options['rfoption_title_post'] ) ) ? 'Our Blog' : $theme_options['rfoption_title_post'];
?>
<div class='section-inner'>
				
	<?php echo rf_breadcrumb() ?>	
	<h1 class='page-title'><?php echo $theme_options['rfoption_title_post'] ?></h1>
				
	<div class='row'>		
	
		<?php if( rf_has_sidebar() ) : ?>
			<div class='<?php echo rf_sidebar_classes() ?>' id="site-sidebar">
				<?php dynamic_sidebar( rf_get_sidebar() ); ?>
			</div>
		<?php endif ?>	
	
		<div class='<?php echo rf_content_classes() ?>' id="site-content">
				
			<div <?php post_class( 'layout-single' ) ?>>
			
						
				<?php if( !( isset( $postmeta['rfpostoption_thumbnail_disable'] ) AND $postmeta['rfpostoption_thumbnail_disable'] == 'yes' ) AND has_post_thumbnail() ) : ?>

					<div class='image'>
						<a href='<?php the_permalink() ?>' class='hoverfade'><?php the_post_thumbnail() ?></a>
					</div>
				<?php endif ?>
					
				<div class='main inner-row pad-side the_post'>
					
					<div class='threecol meta'>
						
						<div class='date round'>
							<div class='month'><?php the_time( 'M' ) ?></div>
							<div class='day'><?php the_time( 'd' ) ?></div>
						</div>
						
						<div class='clear'></div>
						
						<?php if( !( isset( $postmeta['rfpostoption_meta_disable'] ) AND $postmeta['rfpostoption_meta_disable'] == 'yes' ) ) : ?>
						
							<div class='author'>By <?php the_author_link() ?></div>
					
							<?php if( has_category() ) : ?> 
								<div class='category'>In: <?php the_category(', ') ?></div>
							<?php endif ?>

							<?php if( has_tag() ) : ?> 
								<div class='category'>Tags: <?php the_tags('') ?></div>
							<?php endif ?>
														
							<div class='comments'>
								<a href='<?php comments_link() ?>'><?php comments_number() ?></a>
							</div>
						<?php endif ?>
					
					</div>
					
					<div class='ninecol last'>
					
						<?php if( !( isset( $postmeta['rfpostoption_title_disable'] ) AND $postmeta['rfpostoption_title_disable'] == 'yes' ) ) : ?>

						<h1 class='title'><?php the_title() ?></h1>

						<?php endif ?>
				
						<div class='excerpt content'>
							<?php the_content() ?>
						</div>
				
					
					</div>
							
				</div>
				
				<?php if( !( isset( $postmeta['rfpostoption_authorbox_disable'] ) AND $postmeta['rfpostoption_authorbox_disable'] == 'yes' ) ) : ?>

					<?php
						$author_description = get_the_author_meta( 'description' );	
						if( isset( $author_description ) AND !empty( $author_description ) ) :
					?>
					<div class='main inner-row pad-side'>
						<div class='author-box'>
							<div class='threecol'>
								<div class='image'>
									<?php echo get_avatar( $post->post_author, 120 ) ?> 
								</div>
							</div>
							<div class='ninecol last'>
								<h2>About <?php echo get_the_author_meta( 'display_name' ) ?></h2>
								<div class='content'>
									<?php echo $author_description ?>
								</div>
							</div>
						</div>
					</div>
					<?php endif ?>			
				<?php endif ?>	
				
				
				<?php comments_template() ?>
				
				
			</div>			
			
		</div>
			</div>

	</div>

</div>
<?php get_footer() ?>