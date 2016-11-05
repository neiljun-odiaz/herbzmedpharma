<?php
	/*
		This page handles the output of many pages where
		a separate template does not exist
	*/
	
	get_header();
	the_post();
	$theme_options['rfoption_title_product'] = ( !isset( $theme_options['rfoption_title_product'] ) OR empty( $theme_options['rfoption_title_product'] ) ) ? 'Our Products' : $theme_options['rfoption_title_product'];
?>
<div class='section-inner'>
						
	<?php echo rf_breadcrumb() ?>	
	<h1 class='page-title'><?php echo $theme_options['rfoption_title_product'] ?></h1>
							
	<div class='row'>		

		<?php if( rf_has_sidebar() ) : ?>
			<div class='<?php echo rf_sidebar_classes() ?>' id="site-sidebar">
				<?php dynamic_sidebar( rf_get_sidebar( true ) ); ?>
			</div>
		<?php endif ?>	
	
		<div class='<?php echo rf_content_classes() ?>' id="site-content">
				
			<div <?php post_class( 'layout-single-product pad-side' ) ?>>
			
				<div class='inner-row the_post'>

					<div class='fivecol'>
						<div class='image'>
							<?php the_post_thumbnail( 'rf_large_thumb' ) ?>
						</div>
					</div>
										
					<div class='sevencol last'>
						<div class='product-header'>
							<h1 class='title'><?php the_title() ?></h1>
							<div class='price'><?php rf_show_price( get_post_meta( $post->ID, 'rf_product_price', true) ) ?></div>
						</div>
						
						<div class='content'>
							<?php the_content() ?>
						</div>
						
					</div>
				
				</div>
							
			
				<?php 
					$categories = wp_get_object_terms( $post->ID, 'rf_product_category', array( 'fields' => 'ids' ) );
					
					if( isset( $categories ) AND !empty( $categories ) ) :
						$count = ( isset( $theme_options['rfoption_related_product_count'] ) AND !empty( $theme_options['rfoption_related_product_count'] ) ) ? $theme_options['rfoption_related_product_count'] : '3';
						
						$args = array(
							'post_type' => 'rf_product',
							'post_status' => 'publish',
							'posts_per_page' => $count,
							'post__not_in' => array( $post->ID ),
							'tax_query' => array(
								array(
									'taxonomy' => 'rf_product_category',
									'field' => 'id',
									'terms' => $categories,
									'operator' => 'IN'
								)
							)							
						);
						$related = new WP_Query( $args );
						if( $related->have_posts() ) :
				?>
				
				<div class='related-products'>
					<div class='linetitle'>
						<h3>
							<?php 
							if( !isset( $theme_options['rfoption_related_product_title'] ) OR empty( $theme_options['rfoption_related_product_title'] ) ) {
								$theme_options['rfoption_related_product_title'] = 'Related Products';
							}
							echo $theme_options['rfoption_related_product_title'];
							?>
						</h3>
					</div>
					<div class='inner-row'>
					<?php 
						$columns = ( isset( $theme_options['rfoption_related_product_columns'] ) AND !empty( $theme_options['rfoption_related_product_columns'] ) ) ? $theme_options['rfoption_related_product_columns'] : 3;
						$column_names = array( 2 => 'sixcol', 3 => 'fourcol', 4 => 'threecol' );
						$classColumn = $column_names[$columns];
						
						$column_to_size = array(
							2 => 'rf_large_thumb',
							3 => 'rf_medium_thumb',
							4 => 'rf_small_thumb',
						);
						$image_size = in_array( $columns, array(2,3,4) ) ? $column_to_size[$columns] : 'rf_large_thumb';	
						$i=1;
						while( $related->have_posts() ) : $related->the_post();
									
						$lastClass = ( $i % $columns == 0) ? 'last' : '';							 
												
					?>
						<div class='<?php echo $classColumn ?> <?php echo $lastClass ?>'>
							<div <?php post_class( 'layout-product-mini' ) ?>>
								<div class='image'>
									<a href='<?php the_permalink() ?>' class='hoverfade'><?php the_post_thumbnail( $image_size ) ?></s>
								</div>

								<h1 class='title'><a href='<?php the_permalink() ?>'><?php the_title() ?></a></h1>
								<div class='price'><?php rf_show_price( get_post_meta( $post->ID, 'rf_product_price', true) ) ?></div>
								<div class='excerpt'>
									<?php echo rf_excerpt( strip_tags( $post->post_content ), 100 ) ?>
								</div>
							</div>
						</div>
						<?php $i++; endwhile; ?>
						</div>
						
					</div>
					
				</div>
				
				<?php endif; endif ?>
			</div>

			
		</div>
		
	
</div>
<?php get_footer() ?>