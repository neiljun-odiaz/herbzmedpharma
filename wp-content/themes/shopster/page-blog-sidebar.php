<?php 
/* 
Template Name: Blog Page with Sidebar
*/ ?>
<?php get_header();?>

<div id="entry-full">
    <div id="left">
		<div id="head-line"> 
	    <h1 class="title"><?php  the_title();  ?></h1>
		</div>
        <?php
	    $args = array(
	    	'paged' => $paged
	    );
	    $wp_query = null;
	    $wp_query = new WP_Query();
	    $wp_query->query( $args );
	    ?>

	    <?php if ( $wp_query->have_posts() ) : ?>
				
			<?php while ( $wp_query->have_posts() ) : $wp_query->the_post();
    	 	
					get_template_part( 'content', get_post_format() ); 
			endwhile;?>
			
			<?php if(function_exists('wp_pagenavi')) { ?>
				 
					<?php wp_pagenavi(); ?>
				
				<?php } else { ?> 
						
					<?php get_template_part( 'navigation', 'index' ); ?>
						 
				<?php } else : ?>
			
					<?php get_template_part( 'no-results', 'index' ); ?>
			
				<?php endif; wp_reset_query(); ?>
    </div> <!--  end #left  -->
<?php get_sidebar(); ?>
</div> <!--  end #entry-full  -->
<?php get_footer(); ?>
