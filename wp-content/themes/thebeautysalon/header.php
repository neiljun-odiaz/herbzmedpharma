<?php
	/*
		This page handles the output of the website header
	*/

	global $theme_options; 
?>

<!DOCTYPE html>

<html <?php language_attributes() ?>>
	
	<head>
		
		<!-- Site Title and Description --> 
		<title><?php rf_meta_title() ?></title>
		<meta name='description' value='<?php rf_meta_description() ?>' />
	
		<!-- Facebook Meta Tags --> 
		<meta property="og:title" content="<?php rf_meta_title() ?>"/>
		<meta property="og:url" content="<?php rf_canonical_url() ?>"/>
		<meta property="og:site_name" content="<?php rf_site_title() ?>"/>
		<meta property="og:image" content="<?php rf_meta_image() ?>"/>

		<!-- Misc Meta tags and other links  --> 
		<link rel="profile" href="http://gmpg.org/xfn/11" />		
		<meta charset='<?php bloginfo ('charset' ) ?>' />
		<meta name='description' content='<?php bloginfo( 'description' ) ?>' />
		<meta name='generator' content='WordPress <?php bloginfo( 'version' ) ?>' />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- Show Favicons --> 
		<?php rf_show_favicons() ?>
		
		<!-- Supporting Old Browsers --> 
		<!--[if lte IE 9]><link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/styles/1140-ie.css" type="text/css" media="screen" /><![endif]-->

		<!--[if lt IE 9]>
			<script src="dist/html5shiv.js"></script>
		<![endif]-->	
	
		<!-- Styles --> 
		<link rel='stylesheet' type='text/css' href='<?php bloginfo( 'stylesheet_url' ) ?>' /> 		
		
		<?php
			if ( is_singular() && get_option( 'thread_comments' ) )
				wp_enqueue_script( 'comment-reply' );
		?>		
				
		<!-- Dynamic Style Generator --> 
		<?php include( get_template_directory() . '/styles/style.php' ) ?>
		
		<!-- Your Custom Styles --> 
		<link rel='stylesheet' type='text/css' href='<?php echo get_template_directory_uri() ?>/styles/custom.css' /> 
		
		<?php wp_head() ?>

	</head>

	<body <?php body_class() ?>>
	
	
	<div id='site-header'>
		<div class='container'><div class='row'>
			
			<div class='pad'>
				<div class='fourcol'>
					<?php rf_site_logo() ?>
				</div>
				
				<div class='eightcol last'>
					<div id='header-nav'>
						<?php wp_nav_menu( 'fallback_cb=rf_page_menu&theme_location=main-menu' ) ?>
					</div>
				</div>
			</div>
			
		</div></div>
		
	</div>
	
	
	<div class='container' id='site-container'>
		<div class='section'>