<?php 
/**
 * Comments File
 * 
 * This file handles the display of comments and the comment form. 
 * You can modify numerous option for comments by going to 
 * Settings->Discussion in the admin. 
 *
 * @author Daniel Pataki <daniel@redfactory.nl>
 * @package Skeleton
 *
 */
?>

<?php 
/* 
If the post requires a password we won't show the comments. Once the correct password
is given the comments are shown under the post as usual
*/
if ( ! post_password_required() ) : 
?>
	<div id='comments' class='pad-side'>	
	
		<?php if ( have_comments() ) : ?>
				
			<div class='inner-row'>	
				<div class='threecol'></div>		
					<div class='ninecol last'>
					<h1><?php comments_number( 'No Comments Yet', '1 Comment', '% Comments' ) ?> on <?php the_title() ?></h1>
				</div>
			</div>
	
			<?php 
				/* 
				If there are many comments they can be paged. The section below adds the 
				pagination above the comment list. You can change at what point the 
				comments should be paginated by going to Settings->Discussion in the admin
				*/
				if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : 
			?>
				<nav class='above'>
					<div class='prev'>
						<?php previous_comments_link( '&laquo; Older Comments' ) ?>
					</div>
					<div class='next'>
						<?php next_comments_link( 'Newer Comments &raquo;' ) ?>
					</div>
				</nav>
			<?php endif ?>
	
			<ol class='commentlist'>
				<?php wp_list_comments( array( 'callback' => 'rf_comment' ) ) ?>
			</ol>
	
			<?php 
				/* 
				If there are many comments they can be paged. The section below adds the 
				pagination below the comment list. You can change at what point the 
				comments should be paginated by going to Settings->Discussion in the admin
				*/
				if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : 
			?>	
				<nav class='below'>
					<div class='prev'>
						<?php previous_comments_link( '&laquo; Older Comments' ) ?>
					</div>
					<div class='next'>
						<?php next_comments_link( 'Newer Comments &raquo;' ) ?>
					</div>
				</nav>
			<?php endif ?>
		
		<?php endif // have_comments() End ?>
		<div class='primary-links'>
		<div class='inner-row'>
			<div class='threecol'></div>
			<div class='ninecol last'>
				<?php		
					/* 
					We've redefined the fileds for the comment for so we can more easily add
					javascript into the form 
					*/					
					$args['comment_notes_after']  = '';
					$args['comment_notes_before'] = '';
					$args['logged_in_as'] = '';
					ob_start();
					comment_form($args);
					$form = ob_get_clean();
		
					$form = str_replace( '<input name="submit" type="submit" id="submit" value="Post Comment">', 's<input name="submit" type="submit" id="submit" value="Post Comment">', $form );
					
					preg_match( '/<p class="form-submit">(.*)<\/p>/s' , $form, $matches );
					$find = $matches[1];
					$replace = "
						" . $find . "
						
					";
					
					$form = str_replace( $find, $replace, $form );
					
					echo $form;
		
					
				?>	
			</div>
		</div>
	</div> <!== #comments End -->
<?php endif ?>