<?php

/*
Plugin Name:	Herbs Med Pharma System
Description:	Back End System for Herbs Med Pharma
Version: 		1.0
Author: 		Neiljun I. Odiaz
*/

include_once('templates.php');
include_once('member-list.php');

class HerbsMedPharma
{

	function __construct() {
		register_activation_hook( __FILE__, array($this,'add_user_roles') );
		add_action( 'admin_menu', array($this,'admin_member_list') );
		add_action( 'init',  array($this, 'initialCallback') );
		
		add_action( 'wp_enqueue_scripts', array($this,'front_end_scripts'), 12 );
		add_action( 'admin_enqueue_scripts', array($this,'backend_end_scripts') );

		add_action( 'views_edit-shop_order', array($this,'hmp_update_order_counter') );
		
		// disable entering of billing & shipping address on the checkout page
		add_action('woocommerce_checkout_init', array($this,'fused_disable_billing_shipping'));
		
		/**
		* Add the field to the checkout
		*/
		add_action( 'woocommerce_after_checkout_billing_form', array($this, 'member_registration_fields') );
		
		/**
		* Process the checkout
		*/
		// add_action( 'init', array($this,'replace_woocommerce_process_registration') );
		add_action( 'init', array($this,'woocommerce_process_registration') );
		
		/**
		* Update the order meta with field value
		*/
		add_action( 'woocommerce_checkout_update_order_meta', array($this, 'member_fields_update_order_meta') );
		
		// Add Custom User Field for Branch Location
		add_action( 'show_user_profile', array($this, 'add_custom_user_profile_fields') );
		add_action( 'edit_user_profile', array($this, 'add_custom_user_profile_fields') );

		// Save Custom User Fields
		add_action( 'personal_options_update', array($this, 'save_custom_user_profile_fields') );
		add_action( 'edit_user_profile_update', array($this, 'save_custom_user_profile_fields') );

		// Add Custom Metabox for the Branch Location of the Product
		add_action( 'add_meta_boxes', array($this,'branch_location_meta_box') );

		// Save Custom Branch Location of the Product
		add_action( 'save_post', array($this,'save_branch_location_meta_box') );
		add_action( 'woocommerce_before_calculate_totals', array($this,'add_membership_discount') );
		add_action( 'woocommerce_cart_calculate_fees', array($this,'add_membership_reg_fee') );
		add_action( 'woocommerce_thankyou', array($this,'order_custom_tracking_code') );
		add_action('woocommerce_checkout_order_processed',array($this,'checkout_processed'));
		
	}
	
	function add_user_roles() {
		add_role( 'member', 'Member', array( 'read' => true, 'level_0' => true ) );
	}
	
	
	/* 
	 * Front-end/Client side styles and scripts
	 */
	function front_end_scripts() {
		wp_enqueue_style( 'style-application-form', plugins_url( '/css/frontend.css' , __FILE__ ) );
		wp_enqueue_style( 'jquery-ui-css', 'https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css' );
		wp_enqueue_style( 'select-2-css', plugins_url( '/css/select2.css' , __FILE__ ) );
		wp_enqueue_script( 'script-application-form', plugins_url( '/js/scripts.js' , __FILE__ ), array( 'jquery','jquery-ui-datepicker' ) );
		wp_enqueue_script( 'select-2-js', plugins_url( '/js/select2.min.js' , __FILE__ ) );
	}
	
	/* 
	 * Backend side styles and scripts
	 */
	function backend_end_scripts() {
		wp_enqueue_style( 'style-admin', plugins_url( '/css/backend.css' , __FILE__ ) );
		wp_enqueue_style( 'font-awesome-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css' );
		wp_enqueue_script('backend-script', plugins_url( '/js/backend.js' , __FILE__ ), array('jquery'));
	}
	
	/* 
	 * Menu Pages for Admin Dashboard
	 */
	function admin_member_list() {
		// add_menu_page( 'Member List', 'Member List', 'manage_options', 'member-list', array($this,'member_list_function'), 'dashicons-admin-users', 73 );
		add_submenu_page('users.php', 'Members List', 'Members List', 'manage_options', 'member-list', array($this,'member_list_function') );
	}
	
	/* 
	 * Display Members
	 */
	function member_list_function() {
		global $wpdb;
		$users = new MemberList;
		$users->prepare_items();
		?>
		<div class="wrap">
			<h2>Members List</h2>
		<form method="get">
		  <input type="hidden" name="page" value="member-list" />
		  <?php $users->search_box('search', 'search_id'); ?>
		</form>
		<?php $users->display();  ?>
		</div>
		
		<?php
	}
	
	
	/* 
	 * Function called after initialization
	 */
	function initialCallback() {
		global $current_user;
		// global $woocommerce;
		// $woocommerce->cart->empty_cart();

		$user_ID = get_current_user_id();

		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);

		// Hide Menu Bar except the admin
		if (!current_user_can('manage_options')) {
			show_admin_bar(false);
		}

		if ( $user_role == 'shop_manager' ) {
			add_action( 'admin_menu', array($this,'remove_menus_for_managers'), 20 );
		}

		if ( $user_role == 'administrator' ) {
			add_action( 'admin_menu', array($this,'remove_menus_for_admin'), 20 );
		}
		
		add_filter( 'parse_query', array($this,'hmp_admin_posts_filter') );
		// add_filter( 'pre_get_posts', array($this,'hmp_admin_posts_filter') );

		add_filter('woocommerce_admin_reports', array($this,'hmp_order_reports') );

	}

	function remove_menus_for_managers() {
		global $submenu;
		remove_menu_page( 'edit.php' );
		remove_menu_page( 'edit.php?post_type=portfolio' );
		remove_menu_page( 'edit.php?post_type=product' );
		remove_menu_page( 'edit.php?post_type=page' );
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'tools.php' );
		remove_menu_page( 'admin.php?page=wpcf7' );
		remove_submenu_page( 'edit.php?post_type=shop_order', 'admin.php?page=wc-settings' );
		// remove_submenu_page( 'edit.php?post_type=shop_order','admin.php?page=wc-status' );
		// remove_submenu_page( 'edit.php?post_type=shop_order','admin.php?page=wc-addons' );
		// print_r($submenu['woocommerce'][1]); exit;

	}

	function remove_menus_for_admin() {
		remove_menu_page( 'edit.php?post_type=portfolio' );
		remove_menu_page( 'tools.php' );
	}
	
	/* 
	 * Function for getting the user's down-line members
	 * @param $memberid - ID of the member/user
	 */
	function getMemberDownlines( $memberid ) {
		$user_fields = array( 'ID' );
		$args = array(
			'number'		=> '10',
			'role'			=> 'member',
			'fields'		=> $user_fields,
			'meta_key'		=> 'sponsor',
			'meta_value'	=> $memberid,
			'meta_compare'	=> '='
		);
		
		$wp_user_search = new WP_User_Query( $args );
		$results = $wp_user_search->results;
		$members = array();
		foreach ( $results as $result ) {
			$user_data = get_user_meta( $result->ID );
			$fullname = ( !empty($user_data['first_name'][0]) && !empty($user_data['last_name'][0]) ) ? $user_data['first_name'][0] . ' ' . $user_data['last_name'][0] : get_userdata( $result->ID )->display_name;
			$downlines = array(
				'ID'			=>	$result->ID,
				'name'			=>	$fullname,
				'member_id'		=>	$user_data['member_id'][0],
				'period_rank'	=>	'-',
				'level'			=>	'1',
				'month_points'	=>	'0',
				'travel_points'	=>	'0',
				'savings_fund'	=>	number_format('0',2),
				'income'		=>	number_format('0',2),
				'has_downline'	=>	$this->checkIfHasDownline($result->ID)
			);
			$members[] = $downlines;
		}
		return $members;
	}
	
	/* 
	 * Function to check if a member have donwlines
	 * @param $memberid - ID of the member/user
	 */
	function checkIfHasDownline( $memberid ) {
		$count = count($this->getMemberDownlines( $memberid ));
		if ( $count > 0 ) {
			return true;
		} else {
			return false;
		}
	}
	
	/* 
	 * Function for getting the current month rank of a member
	 * @param $memberid - ID of the member/user
	 */
	function getMemberCurrentRank( $memberid ) {
		
	}
	
	/* 
	 * Function for getting the current month income of a member
	 * @param $memberid - ID of the member/user
	 */
	function getMemberCurrentIncome( $memberid ) {
		
	}
	
	function hmp_admin_posts_filter( $query ) {
		global $current_user, $wpdb, $pagenow;

		$user_ID = get_current_user_id();
		$branch = get_user_meta( $user_ID,'branch_location', true );

		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);

		$type = $_GET['post_Ftype'];

		if ( is_admin() ) {
			$screen = get_current_screen();
			if ( $user_role == 'shop_manager' ) {
				if( $query->query['post_type'] == 'product' && $screen->base == 'edit' ) {
					$query->set('meta_key','branch_location');
					$query->set('meta_value', $branch);
				}
			}

			if( $query->query['post_type'] == 'shop_order' && $screen->base == 'edit' ) {
				$query->set('meta_key', 'branch_location');
				$query->set('meta_value', $branch);
			}

	        if ( $query->query['post_type'] == 'shop_order' && $screen->base == 'edit' && isset($_GET['tracking_code']) && $_GET['tracking_code'] != '' ) {
				$query->set('meta_key', 'order_tracking_code');
				$query->set('meta_value', $_GET['tracking_code']);
			}

			if ( $query->query['post_type'] == 'shop_order' && $screen->base == 'edit' && isset($_GET['branch']) && $_GET['branch'] != '' ) {
				$query->set('meta_key', 'branch_location');
				$query->set('meta_value', $_GET['branch']);
			}

			// if ( 'product' == $type && $pagenow=='edit.php' && isset($_GET['branch']) && $_GET['branch'] != '') {
			if ( $query->query['post_type'] == 'product' && $screen->base == 'edit' && isset($_GET['branch']) && $_GET['branch'] != '') {
		        $query->query_vars['meta_key'] = 'branch_location';
		        $query->query_vars['meta_value'] = $_GET['branch'];
		    }

		}

	}

	function hmp_order_reports( $reports ){
		// print_r($reports); die();
		// unset( $reports['stock'] );
		return $reports;
	}

	
	function hmp_update_order_counter( $views ) {
		global $wpdb, $current_user;
		$user_ID = get_current_user_id();
		$branch = get_user_meta( $user_ID,'branch_location', true );

		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);

		if ( $user_role == 'shop_manager' ) {
			foreach ( $views as $index => $view ) {
				$args = array(
					'post_type'		=>	'shop_order',
				);
				$args['meta_query'] = 	array (
					'meta_key'		=>	'branch_location',
					'meta_value'	=>	$branch
				);

				$status = '';

				switch ( $index ) {
					case 'wc-completed':
						$status = 'completed';
						break;
					case 'wc-on-hold':
						$status = 'on-hold';
						break;					
					case 'wc-processing':
						$status = 'processing';
						break;
					case 'wc-canceled':
						$status = 'cancelled';
						break;
				}

				if ( $index !== 'all' ) {
					$args['tax_query']	=	array( 
						array(
							'taxonomy'	=>	'shop_order_status',
							'field'		=>	'slug',
							'terms'		=>	$status
						)
					);
				}

				// print_r($args);

				$query = new WP_Query($args);

				if( $query->found_posts > 0 ) {
					$views[$index] = preg_replace( '/ <span class="count">\([0-9]+\)<\/span>/', ' <span class="count">('.$query->found_posts.')</span>', $view );
				} else {
					unset($views[$index]);
				}
			}
		}
		
		return $views;
	}

	// Disable some fields
	function fused_disable_billing_shipping($checkout) {

		$checkout->checkout_fields['billing'] = array();
		
		$checkout->checkout_fields['shipping'] = array();
		
		return $checkout;
		
	}
	
	function member_registration_fields( $checkout ) {
 		
 		$user_fields = array( 'ID', 'display_name' );
		$args = array( 'role' => 'member','fields' => $user_fields );
 		$wp_user_search = new WP_User_Query( $args );
		$user_ids = $wp_user_search->get_results();
		$users = array();
		$users[''] = '';
		foreach ($user_ids as $id) :
			$user_id = $id->ID;
			$user_name = '#'. $user_id . ' ' . get_user_meta( $user_id, 'first_name', true ) . ' ' . get_user_meta( $user_id, 'last_name', true );
			$users[$user_id] = $user_name;
		endforeach;

		woocommerce_form_field( 'branch_location', array(
				'label'       	=> __('Branch Location'),
				'required'    	=> true,
				'clear'       	=> false,
				'type'        	=> 'text',
				'class'       	=> array('hidden'),
			), 'cdo' );

	 	if ( !empty( $user_ids ) ) {
			echo '<div id="order_user_referrer"><h3>' . __('Who refer you here?') . '</h3>';
	 	
		    woocommerce_form_field( 'user_referrer', array(
		        'type'          => 'select',
		        'class'         => array('user-referrer form-row-wide'),
		        'label'         => __('Select the person below. Leave it blank if none. Click the "x" button to clear.'),
		        'options'		=> $users
		    ), '');
		}
	 
	    echo '</div>';
		
	}
	
	function username_exists_by_id($user_ID){
	    global $wpdb;
	    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE ID = %d ", $user_ID));
	    return $count;
	}
	
	/* 
	 * Save Registration Form
	 */
	function woocommerce_process_registration () {
		if ( ! empty( $_POST['member-register'] ) ) {
			$nonce = $_POST['member-register'];
			

            if ( wp_verify_nonce( $nonce, 'woocommerce-member-register' ) ){
			
				if ( $this->process_member_registration_fields() > 0 )
					return;
				
				$_username	= $_POST['membership_username'];
				$_password  = $_POST['membership_password'];
				$username   = ! empty( $_username ) ? sanitize_text_field( $_username ) : '';
				$email      = ! empty( $_POST['member_email'] ) ? sanitize_email( $_POST['member_email'] ) : '';
				$password   = $_password;
				
				$new_customer = wp_create_user( $username, $password, $email );

				if ( is_wp_error( $new_customer ) ) {
					wc_add_notice( $new_customer->get_error_message(), 'error' );
					return;
				}

				wp_set_auth_cookie( $new_customer );
				
				$this->member_fields_update_user_meta( $new_customer );

				// Redirect
				// $redirect = esc_url( get_permalink( wc_get_page_id( 'checkout' ) ) );
				
				// wp_redirect( apply_filters( 'woocommerce_registration_redirect', $redirect ) );
				// exit;

				wp_redirect( home_url('checkout'), $status );
				exit;
				
			}
        }
	}
	
	/* 
	 * Check Member Registration Fields
	 */
	function process_member_registration_fields() {
		$error = 0;
		
		// echo '<pre>';
		// print_r($_POST);
		// echo '</pre>';
		// die('');

		if ( isset($_POST['product']) && !empty( $_POST['product'] ) ) {
			foreach ( $_POST['quantity'] as $key => $qty ) {
				if ( $qty == 0 ) unset( $_POST['quantity'][$key] );
			}
			
			for ($p=0;$p<count($_POST['product']);$p++) {
				$this->add_product_to_cart( $_POST['product'][$p], $_POST['quantity'][$p] );
			}
		}

		if ( isset($_POST['dealer_packages']) ) {
			switch ( $_POST['dealer_packages'] ) {
				case 'Package 1':
					$this->add_product_to_cart( 139 );
					break;
				case 'Package 2':
					$this->add_product_to_cart( 142 );
					break;
				case 'Package 3':
					$this->add_product_to_cart( 143 );
					break;
			}
		}
		
		if ( ! $_POST['membership_type'] ) {
			wc_add_notice( __( 'Please select Membership Type.' ), 'error' ); $error++;
		}
			
		if ( ! $_POST['membership_username'] ) {
			wc_add_notice( __( 'Please enter your username.' ), 'error' ); $error++;
		}
			
		if ( ! $_POST['membership_password'] ) {
			wc_add_notice( __( 'Please enter your password.' ), 'error' ); $error++;
		}
			
		if ( ! $_POST['member_lastname'] ) {
			wc_add_notice( __( 'Please enter your Lastname.' ), 'error' ); $error++;
		}
			
		if ( ! $_POST['member_firstname'] ) {
			wc_add_notice( __( 'Please enter Firstname.' ), 'error' ); $error++;
		}
			
		if ( ! $_POST['member_address'] ) {
			wc_add_notice( __( 'Please enter your Address.' ), 'error' ); $error++;
		}
			
		if ( ! $_POST['member_branch'] ) {
			wc_add_notice( __( 'Please select branch location.' ), 'error' ); $error++;
		}
			
		if ( ! $_POST['member_birthday'] ) {
			wc_add_notice( __( 'Please enter your Birthday.' ), 'error' ); $error++;
		}
			
		if ( ! $_POST['civil_status'] ) {
			wc_add_notice( __( 'Please select your Civil Status.' ), 'error' ); $error++;
		}
			
		if ( ! $_POST['sponsor_name'] ) {
			wc_add_notice( __( 'Please enter your Sponsor name.' ), 'error' ); $error++;
		}
			
		if ( ! $_POST['upline_name'] ) {
			wc_add_notice( __( 'Please enter your Upline name.' ), 'error' ); $error++;
		}
		
		return  $error;
	}
	
	/* 
	 * Update Member User Meta
	 * @param $user_id - ID of the member/user
	 */
	function member_fields_update_user_meta( $user_id ) {
		$member_id = 'ID-'.strtoupper(wp_generate_password(6,false));
		
		wp_update_user( array( 'ID' => $user_id, 'role' => 'member' ) );
		update_user_meta( $user_id, 'member_id', $member_id );
		update_user_meta( $user_id, 'membership_type', sanitize_text_field( $_POST['membership_type'] ) );
		update_user_meta( $user_id, 'last_name', sanitize_text_field( $_POST['member_lastname'] ) );
		update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['member_firstname'] ) );
		update_user_meta( $user_id, 'user_address', sanitize_text_field( $_POST['member_address'] ) );
		update_user_meta( $user_id, 'user_branch', sanitize_text_field( $_POST['member_branch'] ) );
		update_user_meta( $user_id, 'civil_status', sanitize_text_field( $_POST['civil_status'] ) );
		update_user_meta( $user_id, 'birthday', sanitize_text_field( $_POST['member_birthday'] ) );
		update_user_meta( $user_id, 'sponsor', sanitize_text_field( $_POST['sponsor_name'] ) );
		update_user_meta( $user_id, 'upline_name', sanitize_text_field( $_POST['upline_name'] ) );
		update_user_meta( $user_id, 'savings_fund', '0' );
		update_user_meta( $user_id, 'travel_points', '0' );
		update_user_meta( $user_id, 'highest_rank', sanitize_text_field( $_POST['membership_type'] ) );
		update_user_meta( $user_id, 'car_incentive_qualifier', 'false' );
	}
	
	function member_fields_update_order_meta( $order_id ) {
		// args to query for your key
		$trans_id = 'ID-'.strtoupper(wp_generate_password(6,false));
		$args = array(
			'post_type' => 'shop_order',
			'meta_query' => array(
				array(
					'key' => 'transaction_no',
					'value' => $trans_id
				)
			),
			'fields' => 'ids'
		);
		$trasaction = new WP_Query( $args );
		if  ( $trasaction->found_posts > 0 ) {
			$trans_id = 'ID-'.strtoupper(wp_generate_password(6,false));
		}
		
		update_post_meta( $order_id, 'transaction_no', $trans_id );
		update_post_meta( $order_id, 'branch_location', sanitize_text_field($_POST['branch_location']) );

		if ( isset( $_POST['user_referrer'] ) ) {
			update_post_meta( $order_id, 'order_user_referer', sanitize_text_field($_POST['user_referrer']) );
		}
		
	}
	
	function add_custom_user_profile_fields( $user ) {
		global $wpdb;
	?>
	 
		<!-- Field Title -->
		<h3><?php _e('Branch Location', 'eribootstrap'); ?></h3>
		 
		<table class="form-table">
			<tr>
				<th>
					<label for="branch_location">
					<?php _e('Branch'); ?>
					</label>
				</th>
				<td>
					<select name="branch_location" id="branch_location">
						<option value=""></option>
					<?php
						$branch_location = $wpdb->get_results('SELECT * FROM `hmp_branch_location`', ARRAY_A);
						if ( !empty($branch_location) ):
						foreach ( $branch_location as $branch ):
					?>
						<option value="<?php echo $branch['ID']; ?>" <?php selected( $branch['ID'], get_user_meta( $user->ID,'branch_location',true ) ); ?>><?php echo $branch['branch_name']; ?></option>
					<?php
						endforeach;
						endif;
					?>
					</select><br>
					<span class="description"><?php _e('Please enter your job title.'); ?></span>

				</td>
			</tr>
		</table>
	<?php
	}
	
	function save_custom_user_profile_fields( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) )
			return FALSE;
		 
		// Update and Save Field
		update_user_meta( $user_id, 'branch_location', $_POST['branch_location'] );
	}
	
	function branch_location_meta_box() {
		$screens = array( 'product' );
		foreach ( $screens as $screen ) {

			add_meta_box(
				'product_branch_location',
				__( 'Product Branch Location' ),
				array($this,'branch_meta_box_callback'),
				$screen,
				'side'
			);

		}
	}

	function branch_meta_box_callback( $post ) {
		global $wpdb;
		wp_nonce_field( 'branch_location_meta', 'branch_nonce' );
		$value = get_post_meta( $post->ID, 'branch_location', true );
		echo '<label for="branch_location">';
		_e( 'Select branch location:' );
		echo '</label> ';
	?>
		<select name="product_branch_location" id="branch_location" style="width: 100%; margin: 10px 0;">
			<option value=""></option>
		<?php
			$branch_location = $wpdb->get_results('SELECT * FROM `hmp_branch_location`', ARRAY_A);
			if ( !empty($branch_location) ):
			foreach ( $branch_location as $branch ):
		?>
			<option value="<?php echo $branch['ID']; ?>" <?php selected( $branch['ID'], $value ); ?>><?php echo $branch['branch_name']; ?></option>
		<?php
			endforeach;
			endif;
		?>
		</select>
	<?php
	}

	function save_branch_location_meta_box( $post_id ) {
		if ( ! isset( $_POST['branch_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['branch_nonce'], 'branch_location_meta' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'product' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		if ( ! isset( $_POST['product_branch_location'] ) ) {
			return;
		}

		$data = sanitize_text_field( $_POST['product_branch_location'] );

		// Update the meta field in the database.
		update_post_meta( $post_id, 'branch_location', $data );

	}
	
	function add_membership_discount( $cart_object ){
		global $woocommerce, $wpdb;
		$user_ID = get_current_user_id();
		$type = get_user_meta( $user_ID, 'membership_type', true );
		$discount = 0;

		$membership_types = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `hmp_membership_type` WHERE `ID` = %d LIMIT 1', $type ), ARRAY_A);
		if ( !empty($membership_types) ):
			foreach ( $membership_types as $type ):
				$meta = unserialize( $type['rank_meta'] );
				$discount = $meta['discount'];
			endforeach;
		endif;
		
		foreach ( $cart_object->cart_contents as $key => $value ) {
			$new_price = floatval($value['data']->price) - floatval( floatval($value['data']->price) * ($discount/100) );
			$value['data']->price = $new_price;
		}

	}

	function add_membership_reg_fee() {
		global $woocommerce;
		$user_ID = get_current_user_id();
		$type = get_user_meta( $user_ID, 'membership_type', true );

		$fee = 200;	
		if ( $type == '2' )
			$woocommerce->cart->add_fee( 'Registration Fee', $fee, true, 'standard' );
		// $woocommerce->cart->fee_total = $fee;
		// print_r( $woocommerce->cart ); die();
	}

	function remove_order_shop_action( $columns ) {
		unset( $columns['order_actions'] );
		$columns['custom_order_action'] = __( 'Actions' );
		return $columns;
	}

	function checkout_processed($order_id, $posted)
	{
		$order = new WC_Order( $order_id );
		$code = strtoupper(wp_generate_password(8,false));
		$orderid = $order->id;
		$user_ID = get_current_user_id();

		$branch = get_user_meta( $user_ID,'user_branch', true );

		update_post_meta($orderid,'branch_location',$branch);
		update_post_meta($orderid,'order_tracking_code',$code);
	}

	function order_custom_tracking_code( $order_id ) {
		$order = new WC_Order( $order_id );
		$orderid = $order->id;
		
		$code = get_post_meta($orderid,'order_tracking_code',true);
		echo '<br><p>Your Tracking code is: </p><h4 style="font-size:18px">'.$code.'</h4>';
		echo '<p>Please present this code when claiming your items.</p><p>&nbsp;</p>';
	}
	
	/*
	 * Add item to cart
	 */
	function add_product_to_cart($product_id, $qty = 1) {
		if ( ! is_admin() ) {
			global $woocommerce;
			$found = false;
			//check if product already in cart
			if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
				foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
					$_product = $values['data'];
					// print_r( $_product ); die('');
					if ( $_product->id == $product_id )
						$found = true;
				}
				// if product not found, add it
				if ( ! $found )
					$woocommerce->cart->add_to_cart( $product_id, $qty );
			} else {
				// if no products in cart, add it
				$woocommerce->cart->add_to_cart( $product_id, $qty );
			}
		}
	}
	
	function get_max_price_total( $typeid ) {
		$membership_types = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `hmp_membership_type` WHERE `ID` = %d LIMIT 1', $typeid ), ARRAY_A);
		if ( !empty($membership_types) ):
			foreach ( $membership_types as $type ):
				$order = $type['rank_order'] + 1;
				$next_type = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `hmp_membership_type` WHERE `rank_order` = %d LIMIT 1', $order ), ARRAY_A);
				if ( !empty($next_type) ):
					foreach ( $next_type as $ntype ):
						$meta = unserialize( $ntype['rank_meta'] );
						return floatval($meta['group_sale']) - 1;
					endforeach;
				endif;
			endforeach;
		endif;
	}
	

}

$herbsmed = new HerbsMedPharma;