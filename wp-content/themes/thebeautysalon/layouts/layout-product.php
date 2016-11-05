<?php /* Format Name: Internal */ ?>

<?php global $theme_options, $postmeta; ?>
<div <?php post_class( 'layout-product' ) ?>>
	
	<div class='main inner-row'>
		
		<?php if( ! (isset( $theme_options['rfoption_images_disable'] ) AND $theme_options['rfoption_images_disable'] == 'yes' ) ) : ?>
			<div class='threecol meta'>
				<div class='image'>
					<a href='<?php the_permalink() ?>' class='hoverfade'>
						<?php the_post_thumbnail( 'rf_tiny_thumb' ) ?>
					</a>
				</div>		
			</div>
			<div class='ninecol last'>
		
		<?php else : ?>
		
			<div class='twelvecol'>
		<?php endif ?>
	
		
			<div class='product-header'>
				<div class='price'><?php rf_show_price( get_post_meta( $post->ID, 'rf_product_price', true) ) ?></div>
				<h1 class='title'>
					<?php if( ! (isset( $theme_options['rfoption_links_disable'] ) AND $theme_options['rfoption_links_disable'] == 'yes' ) ) : ?>
						<a href='<?php the_permalink() ?>'><?php the_title() ?></a>
					<?php else : ?>
						<?php the_title() ?>
					<?php endif ?>
				</h1>
			</div>
	
			<div class='excerpt content'>
				<?php echo rf_excerpt( strip_tags( $post->post_content ), 100 ) ?>
			</div>
	
		
		</div>
				
	</div>
	
</div>