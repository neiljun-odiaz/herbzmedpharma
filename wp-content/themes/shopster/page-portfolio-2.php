<?php 
/* 
Template Name: Portfolio Two Column 
*/
?>
<?php get_header();?>

<div id="entry-full">
	<div id="left" class="full-width">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; endif; ?>

		<?php
	    $args = array(
			'post_type'=>'portfolio',
			'paged' => $paged,
			'posts_per_page' => -1,
			'post_status' => 'publish'
	    );

		$temp = $wp_query;
		$wp_query = null;
		$wp_query = new WP_Query();
		$wp_query->query( $args );
	    ?>

		<?php if ( $wp_query->have_posts() ) : ?>

			<div class="galleries two-column">
				<div class="two-column portfolio-filtered">

		        <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

					<?php $do_not_duplicate = $post->ID; ?>
					<?php get_template_part('content-portfolio'); ?>

				<?php endwhile; ?>

				</div><!-- .three-column .portfolio-filtered -->
			</div> <!-- .galleries -->

			<?php get_template_part( 'navigation', 'index' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'index' ); ?>

		<?php endif; ?>

	</div> <!-- #left  -->
</div> <!-- #entry-full  -->
<?php get_footer(); ?>