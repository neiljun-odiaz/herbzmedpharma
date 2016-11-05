<?php /* Format Name: Default */ ?>

<?php global $theme_options; ?>

<div <?php post_class( 'layout-default' ) ?>>

	<div class='image'>
		<a href='<?php the_permalink() ?>' class='hoverfade'><?php the_post_thumbnail() ?></a>
	</div>
	
	<div class='main inner-row pad-side'>
		
		<div class='threecol meta'>
			
			<div class='date round'>
				<div class='month'><?php the_time( 'M' ) ?></div>
				<div class='day'><?php the_time( 'd' ) ?></div>
			</div>
			
			<div class='clear'></div>
			
			<div class='author'>By <?php the_author_link() ?></div>
	
			<?php if( has_category() ) : ?> 
				<div class='category'>By <?php the_category(', ') ?></div>
			<?php endif ?>

			<?php if( has_tag() ) : ?> 
				<div class='category'>Tags: <?php the_tags('') ?></div>
			<?php endif ?>
									
			<div class='comments'>
				<a href='<?php comments_link() ?>'><?php comments_number() ?></a>
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