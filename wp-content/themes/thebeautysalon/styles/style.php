<?php
	$theme_options = get_option( 'rf_options_' . THEMENAME);
 	$colors['primary']          = $theme_options['rfoption_colors_primary'];
 	$colors['red']              = '#E23455';
 	$colors['cyan']             = '#139BC1';
 	$colors['yellow']           = '#F4D248';
 	$colors['black']            = '#4D4D4D';
 	$colors['blue']             = '#569CD1';
 	$colors['green']            = '#88BF53';
 	$colors['purple']           = '#C355BD';
 	$colors['orange']           = '#EC9F5F';
?>
<style type='text/css'>

body {
	background-color: <?php echo $theme_options['rfoption_colors_background_color'] ?>;
	<?php if( isset( $theme_options['rfoption_colors_background_image'] ) AND !empty( $theme_options['rfoption_colors_background_image'] ) ) : ?>
	background-image: url('<?php echo $theme_options['rfoption_colors_background_image'] ?>')
	<?php endif ?>
} 

#header-logo {
	top: <?php echo $theme_options['rfoption_images_logo_push'] ?>px;
}


#header-nav ul.children,  #header-nav ul.sub-menu{
	background-color: <?php echo $theme_options['rfoption_colors_background_color'] ?>;
	<?php if( isset( $theme_options['rfoption_colors_background_image'] ) AND !empty( $theme_options['rfoption_colors_background_image'] ) ) : ?>
	background-image: url('<?php echo $theme_options['rfoption_colors_background_image'] ?>')
	<?php endif ?>

}

.breadcrumb a:hover {
	color: <?php echo $colors['primary'] ?>	;
}

#header-nav li ul.children li:hover a,  #header-nav li ul.sub-menu li:hover a{
	color: <?php echo $colors['primary'] ?>	;
}


a,
.content h1 
{
	color: <?php echo $colors['primary'] ?>
}

.highlight {
	background: <?php echo $colors['primary'] ?>;
	color: <?php echo rf_css_text_color( $colors['primary'] ) ?> 
}

.border-primary{
	border-color:#<?php echo rf_hcm( $colors['primary'], "-", "22" ) ?>;
}

.color-block {
	background: <?php echo $colors['primary'] ?>;
}

#site-sidebar .widget .widget-title a:hover {
	color: <?php echo $colors['primary'] ?>
}

#site-sidebar .widget ul li a:hover {
	color: <?php echo $colors['primary'] ?>
}

#site-sidebar .widget .tagcloud a:hover {
	color: <?php echo $colors['primary'] ?>
}


.message, .button-inner{
	background-color: <?php echo $colors['primary'] ?>;
	border-color: #<?php echo rf_hcm( $colors['primary'], "+", "11" ) ?>;
}
.message.text-shadow-outer{
	text-shadow: 1px 1px 0px #<?php echo rf_hcm( $colors['primary'], "+", "33" ) ?>;	
}
.message.text-shadow-inner{
	text-shadow: -1px -1px 0px #<?php echo rf_hcm( $colors['primary'], "-", "33" ) ?>;	
}	

.button a.button-inner {
	.element-color(<?php echo $colors['primary'] ?>);
}
.button a.button-inner:hover{
	background-color: #<?php echo rf_hcm( $colors['primary'], "+", "15" ) ?>;
}
.button a.button-inner:active{
	background-color: #<?php echo rf_hcm( $colors['primary'], "-", "22" ) ?>;
}

.message a {
	color: <?php echo rf_css_text_color( $colors['primary'] ) ?>;
	text-decoration:underline;
}

.banner .flap-right {
	border-color: transparent transparent transparent #<?php echo rf_hcm( $colors['primary'], "-", "33" ) ?>;
}

.banner .flap-left {
	border-color: transparent #<?php echo rf_hcm( $colors['primary'], "-", "33" ) ?> transparent transparent ;
}

.primary-links a {
	color: <?php echo $colors['primary'] ?> !important
}

.primary-link {
	color: <?php echo $colors['primary'] ?> !important
}

.gray-links a {
	color: inherit !important
}

.gray-link {
	color: inherit !important
}

.gray-links a:hover {
	color: <?php echo $colors['primary'] ?> !important
}

.gray-link:hover {
	color: <?php echo $colors['primary'] ?> !important
}

.flag .flag-ear-base, .flag .flag-ear-left, .flag .flag-ear-right, .flag .flag-body {
	background-color: <?php echo $colors['primary'] ?>;
}
	
.flag .flag-ear-left, .flag .flag-ear-right {
	background-color: #<?php echo rf_hcm( $colors['primary'], "-", "33" ) ?>;
}

.layout-default .title {
	color: <?php echo $colors['primary'] ?>;
}



.date.round {
	color: <?php echo rf_css_text_color( $colors['primary'] ) ?>;
	background-color: <?php echo $colors['primary'] ?>;
}


.pagination .page-numbers {
	background-color: <?php echo $colors['primary'] ?>;
	color: <?php echo rf_css_text_color( $colors['primary'] ) ?>;
}



#site-header #header-nav li.current_page_item .triangle-down,
#site-header #header-nav li.current_menu_item .triangle-down,
#site-header #header-nav li:hover .triangle-down,
#site-header #header-nav li.current_page_ancestor .triangle-down,
#site-header #header-nav li.current-menu-ancestor .triangle-down {
	border-color: <?php echo $colors['primary'] ?> transparent transparent transparent;
}

#site-header #header-nav li.current_page_item > a,
#site-header #header-nav li.current_menu_item > a,
#site-header #header-nav li:hover > a,
#site-header #header-nav li.current_page_ancestor > a,
#site-header #header-nav li.current-menu-ancestor > a {
	color: <?php echo $colors['primary'] ?>;
}

.widget ul li.current_page_item .triangle-down,
.widget ul  li.current_menu_item .triangle-down,
.widget ul  li:hover .triangle-down,
.widget ul  li.current_page_ancestor .triangle-down,
.widget ul  li.current-menu-ancestor .triangle-down {
	border-color: <?php echo $colors['primary'] ?> transparent transparent transparent !important;
}

.widget ul  li.current_page_item > a,
.widget ul  li.current_menu_item > a,
.widget ul  li:hover > a,
.widget ul  li.current_page_ancestor > a,
.widget ul  li.current-menu-ancestor > a {
	color: <?php echo $colors['primary'] ?> !important;
}

.arrow {
	background-color: <?php echo $colors['primary'] ?>;

}

.rf_twitter_widget .follow a {
	color: <?php echo $colors['primary'] ?> !important;
}
.prev_product_page, .next_product_page {
	background-color: <?php echo $colors['primary'] ?>;
}

#site-header #header-nav li a:hover{
	color: <?php echo $colors['primary'] ?>;

}
#site-sidebar .rf_widgets_latest_posts li .title a {
	color: <?php echo $colors['primary'] ?>;
}

form input#submit, form input[type=submit] {
	color: <?php echo $colors['primary'] ?> !important;
}

#site-sidebar ul li a:hover {
	color: <?php echo $colors['primary'] ?>;
}
#site-sidebar #recentcomments li a {
	color: <?php echo $colors['primary'] ?>;
}
#site-sidebar .tagcloud a:hover {
	color: <?php echo $colors['primary'] ?>;
}
#site-sidebar .widget .title-text .rsswidget {
	color: <?php echo $colors['primary'] ?>;
}	
#site-sidebar .widget #wp-calendar tbody td a {
	color: <?php echo $colors['primary'] ?>;
}

.layout-default .title, .layout-single .title, .layout-thumbs .title, .layout-simple .title, .layout-page .title, .layout-single-product .title {
	color: <?php echo $colors['primary'] ?>;
}	

.layout-single .author-box h2 {
	color: <?php echo $colors['primary'] ?>;
}

.gallery .item-box {
	background-color: <?php echo $colors['primary'] ?> !important;

}

.gallery .item-box	.item {
		background-color: <?php echo $colors['primary'] ?> !important;
		color: <?php echo rf_css_text_color( $colors['primary'] ) ?>;
	}

.gallery-filter span.current, .gallery-filter span:hover {
	color: <?php echo $colors['primary'] ?>;
}

#comments h1, #comments #reply-title {
	color: <?php echo $colors['primary'] ?>;
}
		
</style>