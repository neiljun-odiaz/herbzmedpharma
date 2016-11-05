<?php

/*
Plugin Name: 	HMP Filter Product by Branch
Plugin URI: 	
Description: 	Custom Plugin on Woocommerce to filter product by branch
Version: 		1.0
Author: 		Jess Anthony G. Compacion
Author URI: 	
*/

class HMP_Filter_Product_by_Branch
{
	function __construct()
	{
		add_action('restrict_manage_posts',array($this, 'filter_by_branch'));
		// add_filter('parse_query', array($this,'product_filter'), 20);
	}

	function filter_by_branch()
	{
		global $wpdb;
		global $typenow;
		global $current_user;

	    if (isset($_GET['post_type'])) {
	        $type = $_GET['post_type'];
	    }

	    $user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);

		if ( $user_role !== 'shop_manager' ) {
		    if ('product' == $type){
		        $branch_location = $wpdb->get_results('SELECT * FROM `hmp_branch_location`', ARRAY_A);
		        ?>
		        <select name="branch">
		        <option value="">Show branch</option>
		        <?php
		            $current_v = isset($_GET['branch'])? $_GET['branch']:'';
		            if ( !empty($branch_location) ):
		            foreach ($branch_location as $branch) {
		                printf
		                    (
		                        '<option value="%s"%s>%s</option>',
		                        $branch['ID'],
		                        $branch['ID'] == $current_v? ' selected="selected"':'',
		                        $branch['branch_name']
		                    );
		                }
		           	endif;
		        ?>
		        </select>
		        <?php
		    }
		}

	    if ( 'shop_order' == $type ) {
	    	if ( $user_role !== 'shop_manager' ) {
	        $branch_location = $wpdb->get_results('SELECT * FROM `hmp_branch_location`', ARRAY_A);
	        ?>
	        <select name="branch" id="filter_by_branch">
	        <option value="">Show branch</option>
	        <?php
	            $current_v = isset($_GET['branch'])? $_GET['branch']:'';
	            if ( !empty($branch_location) ):
	            foreach ($branch_location as $branch) {
	                printf
	                    (
	                        '<option value="%s"%s>%s</option>',
	                        $branch['ID'],
	                        $branch['ID'] == $current_v? ' selected="selected"':'',
	                        $branch['branch_name']
	                    );
	                }
	           	endif;
	        ?>
	        </select>
	        <?php } ?>
			
			 <?php
				$current_code = isset($_GET['tracking_code'])? $_GET['tracking_code']:'';
				$user_ID = get_current_user_id();
				$branch = get_user_meta( $user_ID,'branch_location', true );

				$args = array(
					'post_type'	=>	'shop_order',
					'meta_key'	=>	'branch_location',
					'meta_value'=>	$branch
				);
				$the_query = new WP_Query( $args );
				if ( $the_query->have_posts() ):
					$tracking_code = array();
		            while ( $the_query->have_posts() ) {
		            	$the_query->the_post();
		            	$tracking_code[] = get_post_meta( get_the_ID(), 'order_tracking_code', true );
		            }
		        endif;
		        // print_r($tracking_code); die();
			?>
			<select name="tracking_code" id="filter_by_code">
        		<option value="">Show Tracking Codes</option>
        		<?php
        			if ( !empty($tracking_code) ):
		            foreach ( $tracking_code as $key => $code ) {
		                printf
		                    (
		                        '<option value="%s"%s>%s</option>',
		                        $code,
		                        $code == $current_code? ' selected="selected"':'',
		                        $code
		                    );
		                }
		           	endif;
        		?>
        	</select>

	        <?php
	        // wc_enqueue_js( "jQuery('select#filter_by_branch, #filter_by_code, #filter-by-date, #bulk-action-selector-top').css('width', '150px').chosen();	" );

	    }

	}

	function product_filter($query)
	{
		global $pagenow;
		
		if (isset($_GET['post_type'])) {
	        $type = $_GET['post_type'];
	    }

		if ( 'product' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['branch']) && $_GET['branch'] != '') {
	        $query->query_vars['meta_key'] = 'branch_location';
	        $query->query_vars['meta_value'] = $_GET['branch'];
	        // print_r($query);
	        // die('');
	    }

		if ( 'shop_order' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['tracking_code']) && $_GET['tracking_code'] != '') {
	        $query->query_vars['meta_key'] = 'order_tracking_code';
	        $query->query_vars['meta_value'] = $_GET['tracking_code'];
	    }

	}

}

$filter_product_by_branch = new HMP_Filter_Product_by_Branch();