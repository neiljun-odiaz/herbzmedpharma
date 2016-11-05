<?php

/*
Plugin Name:	Herbs Med Pharma System Variables
Description:	System Variables such as Membership type and Branches
Version: 		1.0
Author: 		Neiljun I. Odiaz
*/

class HerbsMedPharmaVariables
{

	function __construct() {
		register_activation_hook( __FILE__, array($this, 'install_tables') );

		add_action( 'init',  array($this, 'saveRegistrationForm') );

		add_action( 'admin_menu', array($this,'admin_menu_page_variables') );

		add_action( 'admin_enqueue_scripts', array($this,'system_variables_scripts') );
	}

	function install_tables() 
	{
		global $wpdb;
		$branch = 'CREATE TABLE IF NOT EXISTS `hmp_branch_location` (
			  `ID` int(11) NOT NULL AUTO_INCREMENT,
			  `branch_name` varchar(100) NOT NULL,
			  `branch_meta` text NOT NULL,
			  PRIMARY KEY (`ID`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';
		
		$types = 'CREATE TABLE IF NOT EXISTS `hmp_membership_type` (
			  `ID` int(11) NOT NULL AUTO_INCREMENT,
			  `rank_name` varchar(100) NOT NULL,
			  `rank_meta` text NOT NULL,
			  `rank_order` int(5) NOT NULL,
			  PRIMARY KEY (`ID`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;';
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $branch );
		dbDelta( $types );
	}

	/* 
	 * Backend side styles and scripts
	 */
	function system_variables_scripts() {
		wp_enqueue_style( 'style-admin-variable', plugins_url( '/css/backend.css' , __FILE__ ) );
		wp_enqueue_style( 'jquery-ui-style', '//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css' );
		wp_enqueue_script('backend-script-variable', plugins_url( '/js/backend.js' , __FILE__ ), array('jquery'));
		wp_enqueue_script('jquery-ui-tabs');

		wp_enqueue_style( 'select-2-css-variable', plugins_url( '/css/select2.css' , __FILE__ ) );
		wp_enqueue_script( 'select-2-js-variable', plugins_url( '/js/select2.min.js' , __FILE__ ) );
	}

	function admin_menu_page_variables() {
		add_menu_page( 'HMP System Variables', 'HMP Variables', 'manage_options', 'hmp-system-variables', array($this,'system_variable_function') );
	}

	/* 
	 * System Variables
	 */
	function system_variable_function() {
		global $wpdb;
		?>
		<div class="wrap">
			<h2>HMP System Variables</h2>
			
			<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Membership Type</a></li>
				<li><a href="#tabs-2">Branch Location</a></li>
				<li><a href="#tabs-3">Dealer Package</a></li>
			</ul>
			<div id="tabs-1">
				
				<table class="hmp-form-table type-list" cellspacing="0">
					<thead>
						<tr>
							<th>Type Name</th>
							<th>Discount</th>
							<th>Retail Profit</th>
							<th>Gen. Limit</th>
							<th>Allowance</th>
							<th>Recruits</th>
							<th>Leg Sales</th>
							<th>Group Sales</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$membership_types = $wpdb->get_results('SELECT * FROM `hmp_membership_type`', ARRAY_A);
							if ( !empty($membership_types) ):
							foreach ( $membership_types as $type ):
							$meta = unserialize( $type['rank_meta'] );
						?>
						<tr>
							<td><?=$type['rank_name']?></td>
							<td><?=$meta['discount']?>%</td>
							<td><?=$meta['profit']?>%</td>
							<td><?=$meta['limit']?></td>
							<td>₱ <?=number_format($meta['allowance'],2)?></td>
							<td><?=$meta['recruits']?></td>
							<td>₱ <?=number_format($meta['sales'],2)?></td>
							<td>₱ <?=number_format($meta['group_sale'],2)?></td>
							<td><a title="Edit Membership Type" class="button fa fa-edit" href="?page=hmp-system-variables&action=edit&type_id=<?=$type['ID']?>"></a> <a  title="Delete Membership Type" class="button fa fa-trash-o" href="?page=hmp-system-variables&action=delete&type_id=<?=$type['ID']?>"></a> </td>
						</tr>
						<?php
							endforeach;
							endif;
						?>
					</tbody>
				</table>
				<br/>
				<form action="?page=hmp-system-variables" method="post" class="add-new-membership-type-form">
					<?php
						if ( isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['type_id']) && !empty($_GET['type_id']) ) {
							$membership_types = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `hmp_membership_type` WHERE `ID` = %d LIMIT 1', $_GET['type_id'] ), ARRAY_A);
							if ( !empty($membership_types) ):
								foreach ( $membership_types as $type ):
									$id = $_GET['type_id'];
									$type_name = $type['rank_name'];
									$meta = unserialize( $type['rank_meta'] );
									$discount = $meta['discount'];
									$profit = $meta['profit'];
									$limit = $meta['limit'];
									$allowance = $meta['allowance'];
									$recruits = $meta['recruits'];
									$sales = $meta['sales'];
									$group_sale = $meta['group_sale'];
									$order = $type['rank_order'];
								endforeach;
							endif;
						}
					?>
					<h3>Membership Type Information</h3>
					<table class="form-table add-new-type">
						<tbody>
							<tr>
								<th><label for="type-name">Membership Type:</label></th>
								<td><input type="text" name="type-name" id="type-name" value="<?=@$type_name?>" /></td>
							</tr>
							<tr>
								<th><label for="type-discount">Discount (%):</label></th>
								<td><input type="text" name="type-discount" id="type-discount" value="<?=@$discount?>" /></td>
							</tr>
							<tr>
								<th><label for="type-profit">Retail Profit (%):</label></th>
								<td><input type="text" name="type-profit" id="type-profit" value="<?=@$profit?>" /></td>
							</tr>
							<tr>
								<th><label for="generation-limit">Generation Limit:</label></th>
								<td><input type="text" name="generation-limit" id="generation-limit" value="<?=@$limit?>" /></td>
							</tr>
							<tr>
								<th><label for="leadership-allowance">Leadership Allowance:</label></th>
								<td><input type="text" name="leadership-allowance" id="leadership-allowance" value="<?=@$allowance?>" /></td>
							</tr>
							<tr>
								<th><label for="">Requirements:</label></th>
								<td class="requirements">
									<label for="req_recruits">No. Of Recruits:</label>
									<input type="text" name="req_recruits" id="req_recruits" value="<?=@$recruits?>" />
									<label for="req_sale">Personal Sale:</label>
									<input type="text" name="req_sale" id="req_sale" value="<?=@$sales?>" />
									<label for="req_group_sale">Group Sale:</label>
									<input type="text" name="req_group_sale" id="req_group_sale" value="<?=@$group_sale?>" />
								</td>
							</tr>
							<tr>
								<th><label for="membership-order">Order:</label></th>
								<td><input type="text" name="membership-order" id="membership-order" value="<?=@$order?>" /></td>
							</tr>
							<tr>
								<th><input type="submit" value="Save" class="button-primary"/></th>
							</tr>
						</tbody>
					</table>
					<?php 
						if ( isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['type_id']) && !empty($_GET['type_id']) ) :
							wp_nonce_field('update_rank','hmp_nonce');
							echo @'<input type="hidden" value="'.$id.'" name="type_id">';
						else:
							wp_nonce_field('add_new_rank','hmp_nonce');
						endif;
					?>
				</form>
				
			</div>
			<div id="tabs-2">
				<table class="hmp-form-table branch-location-list" cellspacing="0">
					<thead>
						<tr>
							<th>Branch Name</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$branch_location = $wpdb->get_results('SELECT * FROM `hmp_branch_location`', ARRAY_A);
							if ( !empty($branch_location) ):
							foreach ( $branch_location as $branch ):
							$meta = unserialize( $branch['branch_meta'] );
						?>
						<tr>
							<td><?=$branch['branch_name']?></td>
							<td><a title="Edit Branch" class="button fa fa-edit" href="?page=hmp-system-variables&action=edit&branch_id=<?=$branch['ID']?>#tabs-2"></a> <a  title="Delete Branch" class="button fa fa-trash-o" href="?page=hmp-system-variables&action=delete&branch_id=<?=$branch['ID']?>#tabs-2"></a> </td>
						</tr>
						<?php
							endforeach;
							endif;
						?>
					</tbody>
				</table>
				<br/>
				<form action="?page=hmp-system-variables#tabs-2" method="post" class="add-new-branch-form">
					<?php
						if ( isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['branch_id']) && !empty($_GET['branch_id']) ) {
							$branch_location = $wpdb->get_results( $wpdb->prepare('SELECT * FROM `hmp_branch_location` WHERE `ID` = %d', $_GET['branch_id']) , ARRAY_A);
							if ( !empty($branch_location) ):
								foreach ( $branch_location as $branch ):
									$id = $_GET['branch_id'];
									$name = $branch['branch_name'];
									$meta = unserialize( $branch['branch_meta'] );
								endforeach;
							endif;
						}
					?>
					<h3>Branch Information</h3>
					<table class="form-table add-new-branch">
						<tbody>
							<tr>
								<th><label for="branch-name">Branch Name:</label></th>
								<td><input type="text" name="branch-name" id="branch-name" value="<?=@$name?>"/></td>
							</tr>
							<tr>
								<th><input type="submit" value="Save" class="button-primary"/></th>
							</tr>
						</tbody>
					</table>
					<?php 
						if ( isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['branch_id']) && !empty($_GET['branch_id']) ) :
							wp_nonce_field('update_branch','hmp_nonce'); 
							echo @'<input type="hidden" value="'.$id.'" name="branch_id">';
						else :
							wp_nonce_field('add_new_branch','hmp_nonce'); 
						endif;
					?>
				</form>
			</div>
			<div id="tabs-3">
				<h3>Hello World</h3>
				<select name="dealer_products" id="dealer_products" multiple="multiple">
					<?php
						$args = array( 'post_type' => 'product' );
						$products = new WP_Query( $args );
						while ( $products->have_posts() ) {
							$products->the_post();
					?>
					<option value="<?=get_the_ID();?>"><?=get_the_title();?></option>
					<?php } ?>
				</select>
			</div>
			</div>
			
		</div>
		<?php
	}

	/* 
	 * Save Registration Form
	 */
	function saveRegistrationForm() {
		global $wpdb;
		if ( isset($_POST['hmp_nonce']) )
		{
			if ( wp_verify_nonce( $_POST['hmp_nonce'], 'add_new_rank' )  ) {
				$name = sanitize_text_field($_POST['type-name']);
				$discount = sanitize_text_field($_POST['type-discount']);
				$profit = sanitize_text_field($_POST['type-profit']);
				$limit = sanitize_text_field($_POST['generation-limit']);
				$allowance = sanitize_text_field($_POST['leadership-allowance']);
				$recruits = sanitize_text_field($_POST['req_recruits']);
				$sales = sanitize_text_field($_POST['req_sale']);
				$group = sanitize_text_field($_POST['req_group_sale']);
				$order = sanitize_text_field($_POST['membership-order']);
				
				$meta_values = array(
					'discount'		=>	$discount,
					'profit'		=>	$profit,
					'limit'			=>	$limit,
					'allowance'		=>	$allowance,
					'recruits'		=>	$recruits,
					'sales'			=>	$sales,
					'group_sale'	=>	$group,
				);
				
				$data = array(
					'rank_name'	=>	$name,
					'rank_meta'	=>	serialize($meta_values),
					'rank_order'=>	$order
				);
				
				$wpdb->insert( 'hmp_membership_type', $data );
			}

			if ( wp_verify_nonce( $_POST['hmp_nonce'], 'update_rank' )  ) {
				$name = sanitize_text_field($_POST['type-name']);
				$discount = sanitize_text_field($_POST['type-discount']);
				$profit = sanitize_text_field($_POST['type-profit']);
				$limit = sanitize_text_field($_POST['generation-limit']);
				$allowance = sanitize_text_field($_POST['leadership-allowance']);
				$recruits = sanitize_text_field($_POST['req_recruits']);
				$sales = sanitize_text_field($_POST['req_sale']);
				$group = sanitize_text_field($_POST['req_group_sale']);
				$order = sanitize_text_field($_POST['membership-order']);
				
				$meta_values = array(
					'discount'		=>	$discount,
					'profit'		=>	$profit,
					'limit'			=>	$limit,
					'allowance'		=>	$allowance,
					'recruits'		=>	$recruits,
					'sales'			=>	$sales,
					'group_sale'	=>	$group
				);
				
				$data = array(
					'rank_name'	=>	$name,
					'rank_meta'	=>	serialize($meta_values),
					'rank_order'=>	$order
				);
				
				$wpdb->update( 'hmp_membership_type', $data, array( 'ID' => sanitize_text_field($_POST['type_id']) ) );
			}
			
			if ( wp_verify_nonce( $_POST['hmp_nonce'], 'add_new_branch' )  ) {
				$name = sanitize_text_field($_POST['branch-name']);
				
				if ( !empty($name) ) {
					$meta_values = array();
					$data = array(
						'branch_name'	=>	$name,
						'branch_meta'	=>	serialize($meta_values)
					);
					
					$wpdb->insert( 'hmp_branch_location', $data );
				}
			}
			
			if ( wp_verify_nonce( $_POST['hmp_nonce'], 'update_branch' )  ) {
				$name = sanitize_text_field($_POST['branch-name']);
				
				if ( !empty($name) ) {
					$meta_values = array();
					$data = array(
						'branch_name'	=>	$name,
						'branch_meta'	=>	serialize($meta_values)
					);
					
					$wpdb->update( 'hmp_branch_location', $data, array( 'ID' => sanitize_text_field($_POST['branch_id']) ) );
				}
			}
		}

	}
}

$herbsmedvar = new HerbsMedPharmaVariables;