<?php
/**
 * @package WordPress
 * @subpackage shopster_Theme
 */

// Do not delete these lines
	if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	
	if ( post_password_required() ) { ?>
		<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'Shopster'); ?></p> 
	<?php
		return;
	}
?>

<!-- You can start editing here. -->

<?php if ( have_comments() ) : ?>
    
	<div id="commentbox" class="clearfix"><h3 id="comments"><?php _e('Comments', 'Shopster') ?> (<?php comments_number(__('No Responses','Shopster'), __('One Response','Shopster'), __('% Responses','Shopster'));?>)</h3>

<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>

	<ol class="commentlist">
	<?php wp_list_comments('avatar_size=60');?>
	</ol>


	<div class="navigation">
		<div class="alignleft">
		<?php previous_comments_link() ?>
		</div>
		<div class="alignright">
		<?php next_comments_link() ?>
		</div>
	</div> 
	<?php if ( ! empty($comments_by_type['pings']) ) : ?>
	<div id="trackbacks">
		<h3 id="pings"><?php _e('Trackbacks/Pingbacks','Shopster') ?></h3>
		<ol class="pinglist">
			<?php wp_list_comments('type=pings&callback=list_pings'); ?>
		</ol>
	</div>
	<?php endif; ?>	
 <?php else : // this is displayed if there are no comments so far ?>
	<div id="commentbox" class="nocomments">
    	<?php if ('open' == $post->comment_status) : ?>
    		<!-- If comments are open, but there are no comments. -->

	 <?php else : // comments are closed ?>
		<!-- If comments are closed. -->
	
		<!-- <p class="nocomments"><?php _e('Comments are closed for this page.', 'Shopster'); ?></p>  -->

	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?> 
    
    <?php 
    
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $commenter = wp_get_current_commenter();
    
	$commenter['comment_author'] = esc_attr__( 'Name', 'Shopster' ); 
    $commenter['comment_author_email'] = esc_attr__( 'Email', 'Shopster' ); 
    $commenter['comment_author_url'] = esc_attr__( 'Website', 'Shopster' );
     
   	 $fields =  array( 

            'author' => '<p class="comment-form-author">' . '<label for="author">'. esc_attr( $commenter['comment_author']) . ( $req ?  '<span class="required">*</span>' : '') . '</label><input id="author" name="author" type="text" value="" size="30"' . $aria_req . ' /></p>',  

            'email'  => '<p class="comment-form-email"><label for="email">'. esc_attr( $commenter['comment_author_email']) . ( $req ?  '<span class="required">*</span>' : '' ) .'</label><input id="email" name="email" type="text" value="" size="30"' . $aria_req . ' /></p>',  

            'url'    => '<p class="comment-form-url"><label for="url">'. esc_attr( $commenter['comment_author_url']) .'</label>' . '<input id="url" name="url" type="text" value="" size="30" /></p>' 

        ); ?>
    
    

    <?php comment_form( array(
        'fields' => apply_filters( 'comment_form_default_fields', $fields ), 
        'label_submit' => esc_attr__( 'Submit Comment', 'Shopster' ), 
        'title_reply' => '<span>' . esc_attr__( 'Add Your Comment', 'Shopster' ) . '</span>', 
        'title_reply_to' => esc_attr__( 'Leave a Reply to %s', 'Shopster' ), 
        'comment_notes_after' => '',
        'comment_notes_before' => '<p>' . esc_attr__( 'Your email address will not be published. Required fields are marked *', 'Shopster' ) . '</p>'
        ) ); ?>
        
      <?php else: ?>


<?php endif; // if you delete this the sky will fall on your head ?>
</div>