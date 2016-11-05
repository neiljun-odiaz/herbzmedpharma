<?php

global $post, $product, $woocommerce;
$small_thumbnail_size = apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail');
$large_thumbnail_size = apply_filters('single_product_large_thumbnail_size', 'shop_single');
?>

<div class="images">
<?php
$attachment_ids = $product->get_gallery_attachment_ids();

if ( $attachment_ids ) {
	?>	
	<div id="ss_wrap">
		<div class="flexslider">
			<ul class="slides">
				<?php

				$loop = 0;
				foreach ( $attachment_ids as $id ) {

					$attachment_url = wp_get_attachment_url( $id );
					$large_image = wp_get_attachment_image($id, $large_thumbnail_size);
			
					if ( ! $attachment_url )
						continue;
					?>
			 		<li>                                      
	            		<div class="single-product-image">
	            			<?php echo '<a href="'.esc_attr( $attachment_url ).'" rel="shadowbox[gallery]" class="product-image-zoom zoom" zoom="'.$attachment_url.'">'; ?>
	            			<?php echo $large_image; ?>
							<?php echo '</a>'; ?>
	            		</div>
					</li>
					<?php

					$loop++;
				} ?>
				
			</ul><!-- .slides -->
			
			<ul class="pagination" id="product-slider-pagination">
				<?php foreach ( $attachment_ids as $id ) {
			  		$small_image = wp_get_attachment_image($id, $small_thumbnail_size); ?>
					<li class=""><a href="#" ><?php echo $small_image; ?></a></li> 
			  <?php } ?>
			</ul>
		</div><!-- .flexslider -->
	</div><!-- #ss-wrap -->

	
<?php
	
} else {

	if (has_post_thumbnail()) : $thumb_id = get_post_thumbnail_id();  ?>
		<?php $attachment_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) ); ?>
        <div class="single-product-image">
  		<a href="<?php echo wp_get_attachment_url($thumb_id); ?>" rel="shadowbox" class="product-image-zoom zoom" zoom="<?php echo $attachment_url; ?>"><?php echo get_the_post_thumbnail($post->ID, array(650,440)); ?></a>
        </div>
  	<?php else : ?>
  		<img src="<?php echo $woocommerce->plugin_url() ?>/assets/images/placeholder.png" alt="Placeholder" />
  	<?php endif;
} ?>

</div> <!--  .images  -->