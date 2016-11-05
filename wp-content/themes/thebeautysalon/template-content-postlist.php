<?php 
	global $postmeta, $in_mashup, $post ;	
	
	$layout = ( isset( $postmeta['rfpostoption_postlayout'] ) AND !empty( $postmeta['rfpostoption_postlayout'] ) ) ? $postmeta['rfpostoption_postlayout'] : 'default';
?>

<?php if( isset( $in_mashup ) AND $in_mashup === true ) : ?> 

	<?php if( ! ( isset( $postmeta['rfpostoption_title_disable'] ) AND $postmeta['rfpostoption_title_disable'] == 'yes' ) ) :
	?>
		<?php echo rf_breadcrumb() ?>	
		<h1 class='page-title'><?php the_title() ?></h1>
	<?php endif ?>		


	<?php if( isset( $postmeta['rfpostoption_description_show'] ) AND $postmeta['rfpostoption_description_show'] == 'yes' ) : ?>
		<div class='content pad'>
			<?php the_content() ?>
		</div>
	<?php endif ?>
<?php endif ?>


<?php if( isset( $in_mashup ) AND $in_mashup === true ) : ?> 
	<div class='inner-row'> 

	<?php if( rf_has_sidebar() ) : ?>
		<div class='<?php echo rf_sidebar_classes( ) ?>' id="site-sidebar">
			<?php 	
				$temp_post = $post;
				dynamic_sidebar( rf_get_sidebar() ); 
				$post = $temp_post;
			?>
		</div>
	<?php endif; ?>	
	<div class='<?php echo rf_content_classes( ) ?>' id="site-content">
<?php endif ?>								


<?php
	$temp_post = $post;
	$category_list = array();
	
	$categories = get_post_meta( $post->ID, 'rfpostoption_categories', true );
	if(isset($categories) AND !empty($categories)) {
		$type          = $postmeta['rfpostoption_categories_include_exclude'];
		$type          = ( !isset( $type ) OR empty( $type )) ? 'include' : $type;
		$category_list = ( $type == 'exclude' ) ? '-'.implode( ',-', $categories ) : implode( ',', $categories );
	}

	$args = array(
		'paged'		=> $paged,
		'post_type'    => 'post',
		'post_status'  => 'publish',
		'cat'          => $category_list,
		'posts_per_page' => $postmeta['rfpostoption_posts_per_page'],
	); 
	

	$postlist = new WP_Query( $args );
	echo '<div class="postlist">';
	while( $postlist->have_posts() ) {
		$postlist->the_post();
		get_template_part( 'layouts/layout', $layout );	
	}		
	echo '</div>';
	
	if( ! isset( $in_mashup ) OR empty( $in_mashup) OR $in_mashup !== true ) {
		rf_post_pagination( $postlist );
	}
	$post = $temp_post;
?>

<?php if( isset( $in_mashup ) AND $in_mashup === true ) : ?> 
	</div></div>
<?php endif ?>