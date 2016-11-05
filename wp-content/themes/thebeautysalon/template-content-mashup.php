<?php
global $postmeta;
$mashup_pages = get_post_meta( $post->ID, 'rfpostoption_pages_include', true );
$mashup = array();
foreach( $mashup_pages as $item ) {
	$template = get_post_meta( $item, '_wp_page_template', true );
	$mashup[$item] = $template;
}
?>

<?php if( isset( $postmeta['rfpostoption_description_show'] ) AND $postmeta['rfpostoption_description_show'] == 'yes' ) : ?>
	<div class='content pad-left'>
		<?php the_content() ?>
	</div>
<?php endif ?>		
		
		
	<div class='postlist'>
	<?php 
		$temp_post = $post;
		$temp_postmeta = $postmeta;
		
		foreach( $mashup as $id => $template ) : 
			$post = get_post( $id );
			$postmeta = get_postmeta( $id ); 
			global $in_mashup;
			$in_mashup = true;
			setup_postdata($post);
			
			echo '<div class="layout-mashup">';
				if( $template == 'template-gallery.php' ) {
					get_template_part( 'template-content', 'gallery' );
				}
				elseif( $template == 'template-postlist.php' ) {
					get_template_part( 'template-content', 'postlist' );
				}
				elseif( $template == 'template-productpage.php' ) {
					get_template_part( 'template-content', 'productpage' );
				}
				else {
					get_template_part( 'template-content', 'page' );
				}
				
			echo '</div>';	
	?>

<?php endforeach; ?>
	</div>

<?php
	$post = $temp_post;
	$postmeta = $temp_postmeta;
?>
