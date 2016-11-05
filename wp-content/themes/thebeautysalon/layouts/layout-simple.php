<?php /* Format Name: Simple */ ?>

<?php global $theme_options; ?>

<div <?php post_class( 'layout-simple' ) ?>>

	<div class='main pad-side'>
		
			<h1 class='title'><a href='<?php the_permalink() ?>'><?php the_title() ?></a></h1>
	
			<div class='excerpt content'>
				<?php the_excerpt() ?>
			</div>
	
			<a href='<?php the_permalink() ?>' title='Read this post in full' class='read_more'><?php echo $theme_options['rfoption_readmore_text'] ?></a>
		
	</div>
	
</div>