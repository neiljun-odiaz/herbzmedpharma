<?php global $theme_options, $product, $woocommerce; ?>
<?php if ( class_exists('woocommerce') ) {  ?>
	<?php if ( isset( $theme_options['home_products_recent'] ) && $theme_options['home_products_recent'] == 1 ) { ?>

		<section class="home-products recent-products related">
		
			<h2 class="widgettitle"><?php _e("New Products", "Shopster"); ?></h2>
			<div class="flexslider">
				<ul class="products slides">
				<?php	
	        	$wpq = array( 'post_type' => 'product', 'taxonomy' => 'product_cat', 'field'=>'slug', 'orderby' => '', 'posts_per_page' => $theme_options['home_recent_number'] );
	        	$type_posts = new WP_Query ($wpq);

		 	?>
	      <?php while ( $type_posts->have_posts() ) : $type_posts->the_post(); 

			global $post, $product, $woocommerce;

			$_product = $product;
			echo "<li class='product'>";
			if ($_product->is_on_sale()) : ?>
			<?php echo apply_filters('woocommerce_sale_flash', '<span class="onsale">'.__('Sale!', 'woocommerce').'</span>', $post, $_product); ?>
              <?php endif;
			echo "<div class='product-thumb-wrap'>";
				icore_woocommerce_thumbnails();
			echo "</div>"; ?>

	              <a href="<?php echo get_permalink(); ?>" class="home-product-title"><h3><?php echo the_title() ?></h3>
					<?php woocommerce_template_loop_rating(); ?>
	              <!--  Display Product Price -->               
	              <span class="price">
	                  <?php woocommerce_template_loop_price($post, $_product); ?>
	              </span>
	              </a>                                 
	          </li> <!--  .product  -->

			<?php endwhile; wp_reset_postdata(); ?>

				</ul>
			</div>
		</section><!-- home-products featured-products related -->
	<?php  } ?>
<?php  } ?>