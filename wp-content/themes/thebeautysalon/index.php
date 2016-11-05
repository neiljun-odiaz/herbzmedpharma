<?php
	/*
		This page handles the output of many pages where
		a separate template does not exist
	*/
	
	get_header() 
?>
<div class='section-inner'>
		<?php rf_breadcrumb() ?>
		<?php if ( is_day() ) : ?>
				<h1 class='page-title'><?php printf( __( 'Daily Archives: %s', 'rf_themes' ), '<span>' . get_the_date() . '</span>' ); ?></h1>
		<?php elseif ( is_month() ) : ?>
			<h1 class='page-title'><?php printf( __( 'Monthly Archives: %s', 'rf_themes' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'rf_themes' ) ) . '</span>' ); ?></h1>
		<?php elseif ( is_year() ) : ?>
			<h1 class='page-title'><?php printf( __( 'Yearly Archives: %s', 'rf_themes' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'rf_themes' ) ) . '</span>' ); ?></h1>
		<?php elseif( is_category() ) : ?>
			<h1 class='page-title'><?php
				printf( __( 'Category Archives: %s', 'rf_themes' ), '<span>' . single_cat_title( '', false ) . '</span>' );
			?></h1>
		<?php elseif( is_author() ) : ?>
			<h1 class='page-title'><?php printf( __( 'Author Archives: %s', 'rf_themes' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?></h1>
		<?php elseif( is_tag() ) : ?>
			<h1 class='page-title'><?php
				printf( __( 'Tag Archives: %s', 'rf_themes' ), '<span>' . single_tag_title( '', false ) . '</span>' );
			?></h1>
		<?php elseif( is_search() ) : ?>
			<h1 class='page-title'><?php printf( __( 'Search Results for: %s', 'rf_themes' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		<?php elseif ( !is_home() ) : ?>
			<h1 class='page-title'><?php _e( 'Blog Archives', 'rf_themes' ); ?></h1>
		<?php endif; ?>								
							
	<div class='row'>		
		
		<?php if( rf_has_sidebar() ) : ?>
			<div class='<?php echo rf_sidebar_classes( true ) ?>' id="site-sidebar">
				<?php dynamic_sidebar( rf_get_sidebar() ); ?>
			</div>
		<?php endif ?>
		
	
		<div class='<?php echo rf_content_classes( true ) ?>' id="site-content">
							
			<?php 
				if( have_posts() ) : 
					echo '<div class="postlist">';
					while( have_posts() ) {
						the_post();
						get_template_part( 'layouts/layout', 'default' );	
					}		
					echo '</div>';
					echo '<div class="pad-side">';
						rf_post_pagination();
					echo '</div>';
			?>
				
			
			<?php else : ?>
				<div class='content pad-side'>
					<?php if( is_search() ) : ?>
						<h1><?php echo $theme_options['rfoption_no_searchresults_title'] ?></h1>
						<p><?php echo $theme_options['rfoption_no_searchresults_message'] ?></p>
					<?php else : ?>
						<h1><?php echo $theme_options['rfoption_error_noposts_title'] ?></h1>
						<p><?php echo $theme_options['rfoption_error_noposts_message'] ?></p>
					<?php endif ?>
				</div>
			<?php endif ?>
			
		</div>
		
	</div>

</div>
<?php get_footer() ?>