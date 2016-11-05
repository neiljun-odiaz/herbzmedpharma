<?php global $theme_options, $product, $woocommerce; ?>
<?php if ( class_exists('woocommerce') ) {  ?>
	<?php if ( isset( $theme_options['home_products_featured'] ) && $theme_options['home_products_featured'] == 1 ) { ?>

		<section class="home-products featured-products related">
		
			<h2 class="widgettitle"><?php _e("Featured Products", "Shopster"); ?></h2>	
			<div class="flexslider">
				<ul class="products slides">

				<?php	
				$query_args = array('posts_per_page' => $theme_options['home_featured_number'], 'no_found_rows' => 1, 'post_status' => 'publish', 'post_type' => 'product' );

				$query_args['meta_query'] = array();

				$query_args['meta_query'][] = array(
					'key' => '_featured',
					'value' => 'yes'
				);
			    $query_args['meta_query'][] = $woocommerce->query->stock_status_meta_query();
			    $query_args['meta_query'][] = $woocommerce->query->visibility_meta_query();

				$r = new WP_Query($query_args);

				if ($r->have_posts()) :

				while ($r->have_posts()) : $r->the_post(); global $product;

				//circumvent the missing post and product parameter in the loop_shop template
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
				<?php endwhile;   endif;?>
				</ul>
			</div>
		</section><!-- home-products featured-products related -->
	<?php  } ?>
<?php  } ?>