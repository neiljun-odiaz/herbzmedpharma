<?php
global $theme_options;
$location = icore_get_location();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-content">

		<?php if( isset($theme_options['blog_style']) && $theme_options['blog_style'] == '1' ) { ?>

			<h2 class="blog-style title"><a href="<?php the_permalink() ?>" class="title" title="Read <?php the_title_attribute(); ?>"><?php the_title();  ?></a></h2>

			<div class="meta">
				<?php the_time('M j, Y | ');  _e('Posted by ','Shopster');  the_author_posts_link(); ?> <?php _e('in ','Shopster');  the_category(', ') ?> | <?php comments_popup_link(__('0 comments','Shopster'), __('1 comment','Shopster'), '% '.__('comments','Shopster')); ?>
				<div class="like-counter">
					<a id="like-<?php the_ID(); ?>" class="like-count icon-heart" href="#" <?php ufo_liked_class(); ?>>
						<?php ufo_post_liked_count(); ?>
					</a>
				</div>
			</div>  <!-- .meta  -->

			<?php the_content(); ?>

		<?php } else { ?>

				<?php
				$content = get_the_content();
				preg_match('/\[gallery(.*?)]/', $content , $matches);

				if ( ! empty($matches) && ! is_single() ) { ?>
					<div class="post-format-content">
					<?php echo do_shortcode($matches[0]); ?>
					</div>
				<?php } ?>

				<h2 class="title"><a href="<?php the_permalink() ?>" class="title" title="Read <?php the_title_attribute(); ?>"><?php the_title();  ?></a></h2>
				<div class="meta">
					<?php the_time('M j, Y');?> <?php  _e('by ','Shopster');  the_author_posts_link(); ?> <?php _e('in ','Shopster');  the_category(', ') ?> | <?php comments_popup_link(__('0 comments','Shopster'), __('1 comment','Shopster'), '% '.__('comments','Shopster')); ?>
					<div class="like-counter">
						<a id="like-<?php the_ID(); ?>" class="like-count icon-heart" href="#" <?php ufo_liked_class(); ?>>
							<?php ufo_post_liked_count(); ?>
						</a>
					</div>
				</div>  <!-- .meta  -->

				<div class="post-desc">
					<?php  the_excerpt(); ?>
				</div>

				<a href="<?php the_permalink(); ?>" class="readmore"><?php _e('Read More', 'Shopster'); ?></a>

		<?php } ?>

	</div><!-- .post-content  -->
</article>