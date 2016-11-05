<?php global $postmeta, $in_mashup; ?>

	<?php if( ! ( isset( $postmeta['rfpostoption_title_disable'] ) AND $postmeta['rfpostoption_title_disable'] == 'yes' ) ) :
	?>
		<?php echo rf_breadcrumb() ?>	
		<h1 class='page-title'><?php the_title() ?></h1>
	<?php endif ?>

<div <?php post_class( 'layout-page' ) ?>>

	<?php if( !isset( $in_mashup ) OR $in_mashup != true ) : ?>
		<?php if( rf_has_sidebar() ) : ?>
			<div class='<?php echo rf_sidebar_classes() ?>' id="site-sidebar">
				<?php dynamic_sidebar( rf_get_sidebar() ); ?>
			</div>
		<?php endif ?>	
			<div class='<?php echo rf_content_classes() ?>' id="site-content">

	<?php endif ?>
	
		
			<?php if( ! ( isset( $postmeta['rfpostoption_thumbnail_disable'] ) AND $postmeta['rfpostoption_thumbnail_disable'] == 'yes' ) AND has_post_thumbnail() ) :
			?>
				<div class='image'>
					<?php the_post_thumbnail() ?>
				</div>
			<?php endif ?>
		
			<div class='the_post pad-side'>
			
				<div class='content'>
					<?php the_content() ?>
				</div>
				
			</div>

	<?php if( !isset( $in_mashup ) OR $in_mashup != true ) : ?>	
		</div>
	<?php endif ?>

</div>