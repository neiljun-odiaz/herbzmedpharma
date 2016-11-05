<?php
/*
Template Name: Mashup
*/

/**
 * Mashup Template
 * 
 * A mashup page allows you to assemble pages out of existing content. It works much like 
 * menu building in Appearance->Menus. Check out the mashup page settings in the mashup page's
 * edit page in the admin.
 *
 * @author Daniel Pataki <daniel@redfactory.nl>
 * @package Skeleton
 *
 */
?>

<?php 
get_header();
the_post();
global $paged;
$postmeta = get_postmeta();
?>
<div class='section-inner'>

	<?php if( ! ( isset( $postmeta['rfpostoption_title_disable'] ) AND $postmeta['rfpostoption_title_disable'] == 'yes' ) ) :
	?>
		<?php echo rf_breadcrumb() ?>	
		<h1 class='page-title'><?php the_title() ?></h1>
	<?php endif ?>		
						
	<div class='row'>		
	
		<div class='twelvecol' id="site-content">
		
			<?php get_template_part( 'template-content', 'mashup' ) ?>
							
		</div>
		
	</div>

</div>



<?php get_footer() ?>