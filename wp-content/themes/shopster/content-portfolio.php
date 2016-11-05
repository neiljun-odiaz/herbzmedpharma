<?php
$item_classes = '';
$item_cats = get_the_terms($post->ID, 'pcategory');
if($item_cats):
foreach($item_cats as $item_cat) {
	$item_classes .= $item_cat->slug . ' ';
}
endif;
?>
<article id="post-<?php the_ID(); ?>" class="portfolio <?php echo $item_classes; ?>">
	<div class="gallery-image-wrap">
		<?php if ( has_post_thumbnail() ) { ?>
			<?php $thumbid = get_post_thumbnail_id($post->ID);
				  $img = wp_get_attachment_image_src($thumbid,'full');
				  $img['title'] = get_the_title($thumbid); ?>


				<?php the_post_thumbnail("gallery-thumb"); ?>
				<?php
				$content = get_the_content();
				preg_match('/\[gallery(.*?)]/', $content , $matches);

				if ( ! empty($matches) && ! is_single() ) { ?>
					<div class="portfolio-has-gallery">
						<span class="icon-images"></span>
					</div>
				<?php } ?>
				<div class="portfolio-overlay">
					<a href="<?php echo $img[0]; ?>" class="zoom-icon" rel="shadowbox" ><span class="icon-eye"> <?php _e('View', 'Shopster'); ?></span></a>
					<a href="<?php the_permalink(); ?>" class="link-icon"><span class="icon-menu-3"> <?php _e('Details', 'Shopster'); ?></span></a>
				</div>
		<?php } else { ?>
				  <a href="<?php the_permalink(); ?>">
				  <?php echo '<img src="'.get_stylesheet_directory_uri().'/images/no-portfolio-archive.png" class="wp-post-image"/>'; ?></a>
		<?php } ?>
		
	</div>
	<div class="like-counter">
		<a id="like-<?php the_ID(); ?>" class="like-count icon-heart" href="#" <?php ufo_liked_class(); ?>>
			<?php ufo_post_liked_count(); ?>
		</a>
	</div>
	<h2 class="gallery-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
</article><!-- #post-<?php the_ID(); ?> -->