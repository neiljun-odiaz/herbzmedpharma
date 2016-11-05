<?php get_header(); ?>
<?php global $theme_shortname; global $theme_options; ?>
<div class="main home-no-page">

	<?php if(is_active_sidebar('homepage')) { ?>
       <section id="home-widgets">
            <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Homepage') ) : ?>
       	    <?php endif; ?>
       </section> <!--  end #home-widgets  -->
	<?php } ?>

</div> <!-- .main -->
</div> <!-- .main-content -->

</div> <!-- .wrap-inside -->
</div> <!-- .wrapper -->


	<div class="wrapper container">

		<div class="wrap-inside">

        	<div class="main-content">

				<div class="main home-no-page">

					<!-- HOMEPAGE PORTFOLIO SECTION -->
					<?php if ( isset($theme_options['homepage_portfolio']) && $theme_options['homepage_portfolio'] == 1 ) { ?>

						<div id="home-portfolio">
							<h2 class="widgettitle"><?php _e("From Portfolio", "Shopster"); ?></h2>
							<?php
							$portoflio_number = 3;
							$portoflio_number = $theme_options['homepage_portfolio_number'];

							$args = array(
								'post_type'=>'portfolio',
								'showposts' => $portoflio_number
							);

							$temp = $wp_query;
							$wp_query = null;
							$wp_query = new WP_Query();
							$wp_query->query( $args );
							?>

							<?php if ( $wp_query->have_posts() ) : ?>

								<div class="galleries">
									<div class="three-column">

							        <?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
							    		<?php $do_not_duplicate = $post->ID; ?>

							    		<?php get_template_part('content-portfolio'); ?>

									<?php endwhile; endif; ?>

									</div>
								</div> <!-- .galleries -->
						</div> <!-- #home-portoflio -->
					<?php } ?>


					<!-- HOMEPAGE TEAM SECTION -->
					<?php if ( isset($theme_options['homepage_team']) && $theme_options['homepage_team'] == 1 ) { ?>
						<div id="home-team" class="team-page-grid">
							<h2 class="widgettitle"><?php _e("Meet the Team", "Shopster"); ?></h2>

							<?php
							    $blogusers = get_users();
							    foreach ($blogusers as $user) {

									if ( $user->display_archive == '1' ) {

										$user_avatar = get_avatar($user->ID, 512);
										?>

										<div class="author-wrap">
											<span class="author-image"><?php echo $user_avatar; ?></span>
											<div class='author-info'>
				 								<ul class='author-details'>
													<li class='author-info-name'><h3><?php echo $user->display_name; ?></h3></li>
													<?php if ( ! empty($user->position)) { ?>
													<li class='author-info-position'><?php echo $user->position; ?></li>
													<?php } ?>
													<?php if ( ! empty($user->description)) { ?>
														<li class='author-info-bio'><?php echo $user->description; ?></li>
													<?php } ?>

													<?php if ( ! empty($user->user_url)) { ?>
														<li class="author-social icon-link">
															<a href='<?php echo $user->user_url; ?>' target='_blank'><?php _e( 'website', 'Shopster' ); ?></a>
														</li>
													<?php } ?>

													<?php if ( ! empty($user->twitter)) { ?>
														<li class="author-social icon-twitter">
															<a href='<?php echo $user->twitter; ?>' target='_blank'><?php _e( 'twitter', 'Shopster' ); ?></a>
														</li>
													<?php } ?>
														<?php if ( ! empty($user->facebook)) { ?>
															<li class="author-social icon-facebook">
																<a href='<?php echo $user->facebook; ?>' target='_blank'><?php _e( 'facebook', 'Shopster' ); ?></a>
															</li>
														<?php } ?>
														<?php if ( ! empty($user->googleplus)) { ?>
															<li class="author-social icon-google-plus">
																<a href='<?php echo $user->googleplus; ?>' target='_blank'><?php _e( 'google +', 'Shopster' ); ?></a>
															</li>
														<?php } ?>
														<?php if ( ! empty($user->youtube)) { ?>
															<li class="author-social icon-youtube">
																<a href='<?php echo $user->youtube; ?>' target='_blank'><?php _e( 'youtube', 'Shopster' ); ?></a>
															</li>
														<?php } ?>
														<?php if ( ! empty($user->vimeo)) { ?>
															<li class="author-social icon-vimeo">
																<a href='<?php echo $user->vimeo; ?>' target='_blank'><?php _e( 'vimeo', 'Shopster' ); ?></a>
															</li>
														<?php } ?>

												</ul>
											</div>
										</div>
									<?php }
								}
							?>

						</div>
					<?php } ?>  <!-- homepage team -->

					<?php
					// Load featured products
					get_template_part( '/includes/loop-featured-products' );
					?>
					<?php
					// Load recent products
					get_template_part( '/includes/loop-recent-products' );
					?>

					<!-- HOMEPAGE PAGE ONE SECTION -->
					<?php if ( isset($theme_options['homepage_page']) && $theme_options['homepage_page'] <> '' && $theme_options['homepage_page'] != 'none' ) { ?>
						<div id="homepage-page" class="content-page content-first">

				    		<?php query_posts( 'page_id=' . $theme_options['homepage_page'] ); while (have_posts()) : the_post(); ?>

								<h3 class="title"><?php the_title(); ?></h3>

								<?php
							    global $more;
								$more = 0;
								the_content(''); ?>

								<?php if ($pos=strpos($post->post_content, '<!--more-->')) { ?>
									<a href="<?php the_permalink() ?>" class="learnmore"><span><?php _e('Read more','Shopster'); ?> &#8594;</span></a>
								<?php } ?>

						   <?php endwhile; wp_reset_query(); ?>
						</div>
					<?php } ?>


					<!-- HOMEPAGE PAGE TWO SECTION -->
					<?php if ( isset($theme_options['homepage_page_2']) && $theme_options['homepage_page_2'] <> '' && $theme_options['homepage_page_2'] != 'none' ) { ?>
						<div id="homepage-page-2" class="content-page content-second">

				    		<?php query_posts( 'page_id=' . $theme_options['homepage_page_2'] ); while (have_posts()) : the_post(); ?>

								<h3 class="title"><?php the_title(); ?></h3>

								<?php
							    global $more;
								$more = 0;
								the_content(''); ?>

								<?php if ($pos=strpos($post->post_content, '<!--more-->')) { ?>
									<a href="<?php the_permalink() ?>" class="learnmore"><span><?php _e('Read more','Shopster'); ?> &#8594;</span></a>
								<?php } ?>

						   <?php endwhile; wp_reset_query(); ?>
						</div>
					<?php } ?>

				</div><!--  end .main  -->
<?php get_footer(); ?>