<?php
/*
Template Name: Post List
*/

get_header();
$postmeta = get_postmeta();
?>
<div class='section-inner'>


	<?php if( ! ( isset( $postmeta['rfpostoption_title_disable'] ) AND $postmeta['rfpostoption_title_disable'] == 'yes' ) ) :
	?>
		<?php echo rf_breadcrumb() ?>	
		<h1 class='page-title'><?php the_title() ?></h1>
	<?php endif ?>		


	<?php if( isset( $postmeta['rfpostoption_description_show'] ) AND $postmeta['rfpostoption_description_show'] == 'yes' ) : ?>
		<div class='content pad'>
			<?php the_content() ?>
		</div>
	<?php endif ?>
						
	<div class='row'>		
	
		<?php if( rf_has_sidebar() ) : ?>
			<div class='<?php echo rf_sidebar_classes() ?>' id="site-sidebar">
				<?php dynamic_sidebar( rf_get_sidebar() ); ?>
			</div>
		<?php endif ?>

		<div class='<?php echo rf_content_classes() ?>' id="site-content">
				
			<?php get_template_part( 'template-content', 'postlist' ) ?> 				
			
		</div>
		
		
	</div>

</div>
<?php get_footer() ?>