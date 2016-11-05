<?php get_header();?>

<div id="entry-full">
    <div id="left">
		<div id="head-line">
	    <h1 class="title"><?php  the_title();  ?></h1>
		<div class="meta">
            <?php the_time('M j, Y | ');  _e('Posted by ','Shopster');  the_author_posts_link(); ?> <?php _e('in ','Shopster');  the_category(', ') ?> | <?php comments_popup_link(__('0 comments','Shopster'), __('1 comment','Shopster'), '% '.__('comments','Shopster')); ?>
			<div class="like-counter">
				<a id="like-<?php the_ID(); ?>" class="like-count icon-heart" href="#" <?php ufo_liked_class(); ?>>
					<?php ufo_post_liked_count(); ?>
				</a>
			</div>
        </div><!--  end .meta  -->
		</div>
        <div class="post-full single">
            <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div class="post-content">
						<?php
						if ( has_post_format('image') ) {
							the_post_thumbnail("large");
						}
						?>

						<?php
						if ( has_post_format('quote') ) { ?>
							<blockquote>
								<?php the_content(); ?>
							</blockquote>
						<?php } else {
							the_content(); 
						} ?>
                    </div>  <!--  end .post-content  -->
					<div id="sharrre">
					<div id="twitter" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-title="Tweet"></div>
					<div id="facebook" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-title="Share"></div>
					<div id="pinterest" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-title="Pin"></div>
					<div id="googleplus" data-url="<?php the_permalink(); ?>" data-text="<?php the_title(); ?>" data-title="Share"></div>
					</div>
					<div id="tags">
						<?php the_tags('', '', ''); ?>
					</div>
					<?php comments_template(); ?>

				<?php endwhile; else: ?>

					<p><?php _e('Sorry, no posts matched your criteria.','Shopster'); ?></p>

				<?php endif; ?>
           </div>  
         </div> <!--  end .post  -->
    </div> <!--  end #right  -->
<?php get_sidebar(); ?>
</div> <!--  end #entry-full  -->
<?php get_footer(); ?>