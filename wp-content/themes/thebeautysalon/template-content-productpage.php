<?php 
$product_page = get_option( 'rf_product_page' );
$width = count($product_page) * 1048;
global $postmeta;

?>

		<?php if( ! ( isset( $postmeta['rfpostoption_title_disable'] ) AND $postmeta['rfpostoption_title_disable'] == 'yes' ) ) :
		?>	
			<?php rf_breadcrumb() ?>
			<h1 class='page-title'><?php the_title() ?></h1>
		<?php endif ?>		


		<?php if( isset( $postmeta['rfpostoption_description_show'] ) AND $postmeta['rfpostoption_description_show'] == 'yes' ) : ?>
			<div class='content pad'>
				<?php the_content() ?>
			</div>
		<?php endif ?>

	
	<div class='products' data-count='<?php echo count($product_page) ?>'>
		
		<?php if( count($product_page) > 1 ) : ?>
		<a href='#' class='hidden prev_product_page'></a>					
		<a href='#' class='next_product_page'></a>
		<?php endif ?>
		
		<div class='products-inner'>

		<div class='product-pages-container' style='width:<?php echo $width ?>px'>
		<?php 
			$temp_post = $post;
			foreach( $product_page as $single_page ) :
		?>	
			<div class='product-page'>
				<div class='inner-row'>
					<div class='sixcol'>
				
						<?php 
							foreach( $single_page['left'] as $category_id ) :
								$product_category = get_term( $category_id, 'rf_product_category' );
							?>
							<div class='product_category'>
								<div class='category-title'>
									<h2><?php echo $product_category->name ?></h2>
								</div>
								<div class='postlist'>
							<?php
								$args = array(
									'post_type' => 'rf_product',
									'post_status' => 'publish',
									'post_per_page' => -1,
									'numberposts' => 5,
									'nopaging' => true,
									'tax_query' => array(
										array(
											'taxonomy' => 'rf_product_category',
											'field' => 'id',
											'terms' => $product_category->term_id
										)
									)
								);
								$products = new WP_Query( $args );
								while ( $products->have_posts() ) { 
									$products->the_post();
									get_template_part('layouts/layout', 'product');
								}
								
						?>
							</div>
						</div>
						
						<?php endforeach; ?>
											
					</div>
					
					<div class='sixcol last'>
					
						<?php 
							foreach( $single_page['right'] as $category_id ) :
								$product_category = get_term( $category_id, 'rf_product_category' );
							?>
							<div class='product_category'>
								<div class='category-title'>
									<h2><?php echo $product_category->name ?></h2>
								</div>
								<div class='postlist'>
							<?php
							
								$args = array(
									'post_type' => 'rf_product',
									'post_status' => 'publish',
									'post_per_page' => -1,
									'numberposts' => 5,
									'nopaging' => true,
									'tax_query' => array(
										array(
											'taxonomy' => 'rf_product_category',
											'field' => 'id',
											'terms' => $product_category->term_id
										)
									)
								);
								$products = new WP_Query( $args );
								while ( $products->have_posts() ) { 
									$products->the_post();
									get_template_part('layouts/layout', 'product');
								}
								
						?>
							</div>
						</div>
						
						<?php endforeach; ?>								
					
					</div>
				</div>
			</div>
		<?php 
			endforeach;
			$post = $temp_post;
		?>
			</div>
		</div>
	</div>
