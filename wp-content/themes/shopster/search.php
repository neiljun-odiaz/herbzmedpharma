<?php get_header(); ?>

<div id="index-page">
    <div id="left">
		<h1 class="title"><?php printf( __( 'Search Results for: %s', 'Shopster' ), '<span>' . get_search_query() . '</span>' ); ?></h1>

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content' ); ?>

			<?php endwhile; ?>

			<?php get_template_part( 'navigation', 'index' ); ?>
				 
		<?php  else : ?>
	
			<?php get_template_part( 'no-results', 'index' ); ?>
	
		<?php endif; ?>

	</div><!-- #left -->
	<?php get_sidebar(); ?>
</div><!-- #index-page -->
<?php get_footer(); ?>