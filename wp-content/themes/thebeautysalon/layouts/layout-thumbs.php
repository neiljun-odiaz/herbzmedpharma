<?php /* Format Name: Thumbnails */ ?>

<?php global $theme_options; ?>

<div <?php post_class( 'layout-thumbs' ) ?>>
	
	<div class='main pad-side'>
		
		<div class='threecol meta'>
			<div class='image'>
				<a href='<?php the_permalink() ?>' class='hoverfade'>
					<?php the_post_thumbnail( 'rf_tiny_thumb' ) ?>
				</a>
			</div>		
		</div>
		
		<div class='ninecol last'>
		
			<h1 class='title'><a href='<?php the_permalink() ?>'><?php the_title() ?></a></h1>
	
			<div class='excerpt content'>
				<?php the_excerpt() ?>
			</div>
	
			<a href='<?php the_permalink() ?>' title='Read this post in full' class='read_more'><?php echo $theme_options['rfoption_readmore_text'] ?></a>
		
		</div>
				
	</div>
	
</div>