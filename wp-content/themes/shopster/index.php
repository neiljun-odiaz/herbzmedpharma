<?php get_header(); ?>
<?php
global $theme_shortname;
?>
<div id="index-page">
	<div id="left" <?php if ( 'portfolio' == get_post_type() ) echo 'class="full-width"'; ?>>
		<?php if ( 'portfolio' != get_post_type() ) { ?>
			<div id="head-line">
				<h1 class="title">
				<?php
					if ( is_category() ) {
						printf( __( 'Category Archives: %s', 'Shopster' ), '<span>' . single_cat_title( '', false ) . '</span>' );

					} elseif ( is_tag() ) {
						printf( __( 'Tag Archives: %s', 'Shopster' ), '<span>' . single_tag_title( '', false ) . '</span>' );

					} elseif ( is_author() ) {
						/* Queue the first post, that way we know
						 * what author we're dealing with (if that is the case).
						*/
						the_post();
						printf( __( 'Author Archives: %s', 'Shopster' ), '<span class="vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( "ID" ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' );
						/* Since we called the_post() above, we need to
						 * rewind the loop back to the beginning that way
						 * we can run the loop properly, in full.
						 */
						rewind_posts();

					} elseif ( is_day() ) {
						printf( __( 'Daily Archives: %s', 'Shopster' ), '<span>' . get_the_date() . '</span>' );

					} elseif ( is_month() ) {
						printf( __( 'Monthly Archives: %s', 'Shopster' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

					} elseif ( is_year() ) {
						printf( __( 'Yearly Archives: %s', 'Shopster' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

					} else {
						_e( 'Archives', 'Shopster' );

					}
				?>
				</h1>
		    </div>
		<?php } ?>
		<?php if (have_posts()) : ?>

			<?php if ( 'portfolio' == get_post_type() ) { ?>
				<div class="galleries <?php echo $theme_options['portfolio_layout'] . '-column'; ?>">
					<div class="portfolio-filtered <?php echo $theme_options['portfolio_layout'] . '-column'; ?>">
			<?php } ?>

			<!-- The Loop -->
    		<?php while (have_posts()) : the_post(); ?>

				<?php if ( 'portfolio' == get_post_type() ) { ?>

					<?php get_template_part('content-portfolio'); ?>

				<?php } else {

					get_template_part( 'content', get_post_format() );

				}

			endwhile;?>

			<?php if ( 'portfolio' == get_post_type() ) { ?>
					</div>
				</div><!-- .galleries -->
			<?php } ?>

			<?php if(function_exists('wp_pagenavi')) { ?>

				<?php wp_pagenavi(); ?>

			<?php } else { ?>

				<?php get_template_part( 'navigation', 'index' ); ?>

			<?php } else : ?>

				<?php get_template_part( 'no-results', 'index' ); ?>

			<?php endif; wp_reset_query(); ?>

	</div> <!--  end #left  -->

	<?php if ( 'portfolio' != get_post_type() ) get_sidebar(); ?>

</div><!--  end #index-page  -->
<?php get_footer();?>