<?php
global $theme_options;
$location = icore_get_location();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-content">

		<?php if( isset($theme_options['blog_style']) && $theme_options['blog_style'] == '1' ) { ?>

			<a href="<?php the_permalink(); ?>" >
				<blockquote>
					<?php the_content(); ?>
				</blockquote>
			</a>
				<div class="like-counter">
					<a id="like-<?php the_ID(); ?>" class="like-count icon-heart" href="#" <?php ufo_liked_class(); ?>>
						<?php ufo_post_liked_count(); ?>
					</a>
				</div>

		<?php } else { ?>

				<div class="post-format-content">
					<a href="<?php the_permalink(); ?>" >
						<blockquote>
							<?php the_content(); ?>
						</blockquote>
					</a>
				</div>

					<div class="like-counter">
						<a id="like-<?php the_ID(); ?>" class="like-count icon-heart" href="#" <?php ufo_liked_class(); ?>>
							<?php ufo_post_liked_count(); ?>
						</a>
					</div>

		<?php } ?>

	</div><!-- .post-content  -->
</article>