<?php
	global $postmeta, $in_mashup;
	$categories = get_post_meta( $post->ID, 'rfpostoption_categories', true );
	$category_operator = ( $postmeta['rfpostoption_categories_include_exclude'] === 'exclude' ) ? 'NOT IN' : 'IN';
	
	$filter_categories = $categories;
	if( isset( $postmeta['rfpostoption_categories_include_exclude'] ) AND $postmeta['rfpostoption_categories_include_exclude'] === 'exclude' ) {
		$cats = get_categories( array( 'exclude' => $categories ) );
		if( isset( $cats ) AND !empty( $cats ) ) {
			$filter_categories = array();
			foreach( $cats as $cat ) {
				$filter_categories[] = $cat->term_id;
			}
		}
	}
	
	$columns = ( isset( $postmeta['rfpostoption_columns'] ) AND !empty( $postmeta['rfpostoption_columns'] ) ) ? $postmeta['rfpostoption_columns'] : 4;
	$column_names = array( 2 => 'sixcol', 3 => 'fourcol', 4 => 'threecol' );
	$classColumn = $column_names[$columns];
	
	$column_to_size = array(
		2 => 'rf_large_thumb',
		3 => 'rf_medium_thumb',
		4 => 'rf_medium_thumb',
	);
	$image_size = in_array( $columns, array(2,3,4) ) ? $column_to_size[$columns] : 'rf_large_thumb';

	$gallery_options['wait'] = ( isset( $postmeta['rfpostoption_gallery_cycle_wait'] ) AND !empty( $postmeta['rfpostoption_gallery_cycle_wait'] ) ) ? $postmeta['rfpostoption_gallery_cycle_wait'] : 4500 ;	

	$gallery_options['fadespeed'] = ( isset( $postmeta['rfpostoption_gallery_cycle_fadespeed'] ) AND !empty( $postmeta['rfpostoption_gallery_cycle_fadespeed'] ) ) ? $postmeta['rfpostoption_gallery_cycle_fadespeed'] : 1000 ;
	
	$gallery_options['random'] = ( isset( $postmeta['rfpostoption_gallery_cycle_random'] ) AND !empty( $postmeta['rfpostoption_gallery_cycle_random'] ) ) ? $postmeta['rfpostoption_gallery_cycle_random'] : 'no' ;	

	$gallery_options['numberposts'] = ( isset( $postmeta['rfpostoption_gallery_numberposts'] ) AND !empty( $postmeta['rfpostoption_gallery_numberposts'] ) ) ? $postmeta['rfpostoption_gallery_numberposts'] : -1 ;	
	
	foreach( $gallery_options as $name => $option ) {
		$gallery_options_array[] = 'data-' . $name .'="' . $option . '"';
	}
	$gallery_options_string = implode( ' ', $gallery_options_array);
	
	?>
	
	
	<?php 
		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => -1
		);
		
		if( $postmeta['rfpostoption_categories_include_exclude'] == 'exclude' ) {
			$args['category__not_in'] = $filter_categories;
		}
		else {
			$args['category__in'] = $filter_categories;
		}
		
		$temp_post = $post;
		
		$gallery = new WP_Query( $args );
		$expanded = false;
		if( $gallery->have_posts() ) : 
			$collapse = ( ( !isset( $postmeta['rfpostoption_description_show'] )  OR empty( $postmeta['rfpostoption_description_show'] ) OR ( isset( $postmeta['rfpostoption_description_show'] ) AND $postmeta['rfpostoption_description_show'] != 'yes' ) ) AND !( isset( $postmeta['rfpostoption_title_disable'] ) AND $postmeta['rfpostoption_title_disable'] == 'yes' ) AND ( isset( $filter_categories ) AND !empty( $filter_categories ) ) AND !( isset( $postmeta['rfpostoption_filter_hide'] ) AND $postmeta['rfpostoption_filter_hide'] == 'yes' ) ) ? 'collapse' : '';
				
		?>
		

		<?php if( ! ( isset( $postmeta['rfpostoption_title_disable'] ) AND $postmeta['rfpostoption_title_disable'] == 'yes' AND ( ! isset( $postmeta['rfpostoption_description_show'] ) OR isset( $postmeta['rfpostoption_description_show'] ) AND $postmeta['rfpostoption_description_show'] != 'yes' ) ) ) : ?>
			
		<div class='<?php echo $collapse ?>'><div class='row'>
				<?php if( ! ( isset( $postmeta['rfpostoption_title_disable'] ) AND $postmeta['rfpostoption_title_disable'] == 'yes' ) ) :
				?>
					<?php echo rf_breadcrumb() ?>	
					<?php if( isset( $in_mashup ) AND $in_mashup === true ) : ?>
						<h1 class='page-title pull'><?php the_title() ?></h1>
					<?php else :?>
	 					<h1 class='page-title'><?php the_title() ?></h1>
					<?php endif ?>
				<?php endif ?>		

	
				<?php if( isset( $postmeta['rfpostoption_description_show'] ) AND $postmeta['rfpostoption_description_show'] == 'yes' ) : ?>
					<div class='content pad'>
						<?php the_content() ?>
					</div>
				<?php endif ?>
		</div></div>	
		<?php else : 
			$expanded = 'expanded';
		?>
		
		<?php endif ?>
				
			<div class='gallery-container <?php echo $expanded ?> <?php echo $collapse ?>'>
			<?php 
			if( !( isset( $postmeta['rfpostoption_filter_hide'] ) AND $postmeta['rfpostoption_filter_hide'] == 'yes' ) ) {
				if( isset( $filter_categories ) AND !empty( $filter_categories ) ) {
					echo '<div class="gallery-filter">';
					echo '<span data-category="all" class="current">all</span>';

					foreach( $filter_categories as $category_id ) {
						$category = get_category( $category_id ); 
						echo '<span data-category="' . $category_id . '">' . $category->name . '</span>';
					} 	
					echo '</div>';
				}
			}
			?>
		
			<div class='nospacing gallery' data-columns='<?php echo $columns ?>' <?php echo $gallery_options_string ?>>
				<?php 
					$i=$columns; 
					while( $gallery->have_posts() ) : 
					$gallery->the_post();
					$lastClass = ( ($i + 1) % $columns == 0 ) ? 'last' : '';
					$categories = wp_get_object_terms( $post->ID, 'category', array( 'fields' => 'ids') )
				?>
						
					<div data-category='<?php echo json_encode( $categories ) ?>' class='item-box <?php echo $classColumn ?> <?php echo $lastClass ?>'>
			
						<?php the_post_thumbnail( $image_size ) ?>
						
						<div class='item'>
							<div class='inner'>
								<h1 class='title'><?php the_title() ?></h1>
								<div class='excerpt'><?php echo rf_excerpt( strip_tags( $post->post_content), 75 ) ?></div>
								
								<div class="pagination">
									<a class="next page-numbers" href="<?php the_permalink() ?>"><img src="<?php echo get_template_directory_uri() ?>/images/roundarrow_right.png"></a>
								</div>

							</div>
						</div>
			
					</div>
															
				<?php $i++; endwhile; $post = $temp_post ?>
								
			</div>
		</div>
					
	<?php else : ?>
		
		<div class='section-inner'>
			<div class='content text-center'>
				<h1><?php echo $theme_options['rfoption_error_noposts_title'] ?></h1>
				<p><?php echo $theme_options['rfoption_error_noposts_message'] ?></p>
			</div>	
		</div>
						
					
	<?php endif ?>
