<?php

/*
Plugin Name: 	HMP Order Actions
Plugin URI: 	
Description: 	Custom Plugin on Woocommerce to filter product by branch
Version: 		1.0
Author: 		Jess Anthony G. Compacion
Author URI:
*/

class HMP_Order_Action
{
	function __construct()
	{
		add_filter( 'manage_edit-shop_order_columns', array($this,'remove_order_shop_action'), 20, 1 );
		add_action('manage_shop_order_posts_custom_column',array($this,'custom_columns'),20,1);
	}

	function remove_order_shop_action( $columns ) {
		unset( $columns['order_actions'] );
		unset( $columns['order_notes'] );
		// unset( $columns['order_items'] );
		unset( $columns['shipping_address'] );
		unset( $columns['customer_message'] );
		$columns['order_code'] = __( 'Tracking Code' );
		$columns['order_branch'] = __( 'Branch' );
		$columns['order_status'] = __( 'Status' );
		$columns['custom_order_action'] = __( 'Actions' );
		return $columns;
	}

	function custom_columns($column)
	{
		global $the_order,$post,$wpdb;

		if ( $column == 'custom_order_action' ) {
			do_action( 'woocommerce_admin_order_actions_start', $the_order);

			$actions = array();

			if( current_user_can( 'manage_options' ) ) {

				if ( in_array( $the_order->status, array( 'pending', 'on-hold' ) ) ) {
					$actions['processing'] = array(
						'url' 		=> wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_processing&order_id=' . $post->ID ), 'woocommerce-mark-order-processing' ),
						'name' 		=> __( 'Validate Payment', 'woocommerce' ),
						'action' 	=> "processing",
						'icon'		=>	'fa fa-check-square-o'
					);
				}

			}

			if ( in_array( $the_order->status, array( 'processing' ) ) ) {
				$actions['complete'] = array(
					'url' 		=> wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_complete&order_id=' . $post->ID ), 'woocommerce-mark-order-complete' ),
					'name' 		=> __( 'Order Receive', 'woocommerce' ),
					'action' 	=> "complete",
					'icon'		=>	'fa fa-shopping-cart'
				);
			}
				

			$actions['view'] = array(
				'url' 		=> admin_url( 'post.php?post=' . $post->ID . '&action=edit' ),
				'name' 		=> __( 'View', 'woocommerce' ),
				'action' 	=> "view",
				'icon'		=>	'fa fa-eye'
			);

			$actions = apply_filters( 'woocommerce_admin_order_actions', $actions, $the_order );

			foreach ( $actions as $action ) {
				printf( '<a class="button tips %s %s" href="%s" data-tip="%s">%s</a>', esc_attr( $action['icon'] ), esc_attr( $action['action'] ), esc_url( $action['url'] ), esc_attr( $action['name'] ), esc_attr( '' ) );
			}

			do_action( 'woocommerce_admin_order_actions_end', $the_order );
		}

		if ( $column == 'order_branch' ) {
			$order_id = $post->ID;
			$branch = get_post_meta( $order_id, 'branch_location', true );
			$branch_location = $wpdb->get_results('SELECT * FROM `hmp_branch_location` WHERE `ID` = "'.$branch.'" LIMIT 1', ARRAY_A);
			foreach ( $branch_location as $location ) {
				echo $location['branch_name'];
			}
		}

		if ( $column == 'order_code' ) {
			$order_id = $post->ID;
			echo get_post_meta( $order_id, 'order_tracking_code', true );
			// print_r(get_the_term_list( $post->ID, 'shop_order_status' ));
		}

		if ( $column == 'order_status' ) {
			// switch ($the_order->status) {
			// 	case 'on-hold':
			// 		echo 'Payment Not Yet Verified';
			// 		break;
			// 	case 'processing':
			// 		echo 'Payment Verified';
			// 		break;
			// 	case 'complete':
			// 		echo 'Order Received';
			// 		break;
			// 	default:
			// 		echo '';
			// 		break;
			// }
		}

	}
}

$hmp_order_action = new HMP_Order_Action();