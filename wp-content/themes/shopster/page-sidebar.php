<?php 
/* 
Template Name: Page with Sidebar
*/ ?>
<?php get_header();?>

<div id="entry-full">
    <div id="left">
		<div id="head-line"> 
	    <h1 class="title"><?php  the_title();  ?></h1>
		</div>
        <div class="post-full single">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div class="post-content"> 
					<?php the_content(); ?>
					<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>  
				</div>  <!--  end .post-content  -->

					<?php comments_template(); ?>

			<?php endwhile; else: ?>

				<p><?php _e('Sorry, no posts matched your criteria.','Shopster'); ?></p>

			<?php endif; ?>
            
         </div> <!--  end .post  -->
    </div> <!--  end #left  -->
<?php get_sidebar(); ?>
</div> <!--  end #entry-full  -->
<?php get_footer(); ?>
