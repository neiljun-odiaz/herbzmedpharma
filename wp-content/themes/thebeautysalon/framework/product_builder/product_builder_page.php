<?php
	$product_page = get_option( 'rf_product_page' );
	
?>

<div class="wrap">
<div id="icon-tools" class="icon32 icon32-posts-rf_product"><br></div><h2>Product Page Builder  </h2>

<div class='page-creator'>
	<div class='row'>
		<div class='threecol'>
			
			
			<h1>Products</h1>
		
			<div class='product_categories' id='product_categories'>
			<?php 
				$product_categories = get_terms( 'rf_product_category' );
				foreach( $product_categories as $product_category ) :
			?>
			
				<div class='product_category' data-id='<?php echo $product_category->term_id ?>'>
					<h2><?php echo $product_category->name ?></h2>
					
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
						while ( $products->have_posts() ) : $products->the_post();
					?>
						<div class='product'>
							<div class='twocol'>
								<div class='image'>
									<?php the_post_thumbnail( 'rf_tiny_thumb' ) ?>
								</div>
							</div>
							<div class='tencol last'>
								<h3><?php the_title() ?></h3>
							</div>
							<div class='clear'></div>
						</div>
					<?php endwhile ?>
			
				</div>
			<?php endforeach ?>
			</div>
		
		</div>
		
		
		<?php
			$populate = ( isset( $product_page ) AND !empty( $product_page )) ? 'populate' : '';
		?>
		
		<div id='pages' class='ninecol last <?php echo $populate ?>'>
		<h1>Pages</h1>
		
		<?php if( !isset( $product_page ) OR empty( $product_page )) : ?>
			
			<div class='page'>	
				<div class='inner'>	
					<div class='controls'>
						<div class='page-number'>page 1</div>
						<a href='#' class='delete-page'>delete page</a>
					</div>
					<div class='page_left'>
						<div class='product_categories'>
						</div>
					</div>
					
					<div class='page_right'>
						<div class='product_categories'>
						</div>
					</div>
				</div>
				<div class='clear'></div>
			
			</div>
			
		<?php else : ?>
		
			<?php 
				$i=1; foreach ( $product_page as $page ) : 
				$left = implode(',', $page['left']);
				$right = implode(',', $page['right']);
			?>
		
			<div class='page'>	
				<div class='inner'>	
					<div class='controls'>
						<div class='page-number'>page <?php echo $i ?></div>
						<a href='#' class='delete-page'>delete page</a>
					</div>
					<div class='page_left'>
						<div class='product_categories' data-ids='<?php echo $left ?>'>
						</div>
					</div>
					
					<div class='page_right'>
						<div class='product_categories' data-ids='<?php echo $right ?>'>
						</div>
					</div>
				</div>
				<div class='clear'></div>
			
			</div>
		
			<?php $i++; endforeach ?>
		
		<?php endif ?>
		
			<a href='#' class='add-page'>+ Add Page</a>
		
		</div>
		
	</div>	
</div>



</div>