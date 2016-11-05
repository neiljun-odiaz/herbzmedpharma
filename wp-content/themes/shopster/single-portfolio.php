<?php get_header();?>

<div id="entry-full">
	<div id="left">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		</article><!-- #post-<?php the_ID(); ?> -->
	</div> <!-- #left  -->


	<div id="sidebar" class="portfolio-sidebar">
		<h1 class="title"><?php the_title(); ?></h1>
		<div class="meta">
            <?php the_time('M j, Y'); ?>
        </div><!-- .meta  -->

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<?php the_content(); ?>

		<?php endwhile; else: ?>

			<p><?php _e('Sorry, no posts matched your criteria.','Shopster'); ?></p>

		<?php endif; ?>

		<div id="sharrre">
			<div id="twitter" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-title="Tweet"></div>
			<div id="facebook" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-title="Share"></div>
			<div id="pinterest" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-title="Pin"></div>
			<div id="googleplus" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-title="Share"></div>
			<div class="like-counter">
				<a id="like-<?php the_ID(); ?>" class="like-count icon-heart" href="#" <?php ufo_liked_class(); ?>>
					<?php ufo_post_liked_count(); ?>
				</a>
			</div><!-- .like-counter -->
		</div><!-- #sharrre -->
		<footer class="meta">
			<?php echo get_the_term_list( $post->ID, 'pcategory', 'Category: ', ', ', '' ); ?>
			<div id="tags">
				<?php echo get_the_term_list( $post->ID, 'ptag', 'Tags: ', ', ', '' ); ?>
			</div>
		</footer>

	</div><!-- #sidebar -->
</div> <!-- #entry-full  -->
<?php get_footer(); ?>