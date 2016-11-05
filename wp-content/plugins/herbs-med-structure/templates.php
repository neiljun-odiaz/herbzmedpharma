<?php

class PageTemplates
{
	
	function __construct(){
		
		add_shortcode( "login-interface", array($this,'loginInterface') );
		add_shortcode( "member-informations", array($this,'membersInformations') );
		add_shortcode( "membership-form", array($this,'membershipForm') );
		
	}
	
	/* 
	 * Login Page
	 */
	function loginInterface(){
		?>
		<div class="member_loginform">
		<?php
		wc_print_notices();
		woocommerce_login_form(
			array(
				'message'  => __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing &amp; Shipping section.', 'woocommerce' ),
				'redirect' => get_permalink( wc_get_page_id( 'member-information' ) ),
				'hidden'   => false
			)
		);

		?>
		<div class="clearfix">&nbsp;</div>
		<p>Not yet a Member? Click <a href="<?=home_url('membership-registration')?>">here</a> to register.</p>
		</div>
		<?php
	}
	
	/* 
	 * Members Information
	 */
	function membersInformations(){
		global $wpdb;
		
		if ( class_exists('HerbsMedPharma') ) {
			$herbsmed = new HerbsMedPharma;
		}
		$user_ID = ( isset($_GET['id']) ) ? sanitize_text_field($_GET['id']) : get_current_user_id();
		
		// Check if Member Exists
		$check_user_data = get_userdata( $user_ID );
		
		if ( $check_user_data === false || $check_user_data->roles[0] !== 'member' ) {
		
		?>
			<h2>Member ID not existed!</h2>
		<?php
		
		} else {
		
			$user_data = get_user_meta( $user_ID );
			$fullname = ( !empty($user_data['first_name'][0]) && !empty($user_data['last_name'][0]) ) ? $user_data['first_name'][0] . ' ' . $user_data['last_name'][0] : get_userdata( $user_ID )->display_name;

			$highest_rank = '';
			$membership_types = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `hmp_membership_type` WHERE `ID` = %d LIMIT 1', $user_data['highest_rank'][0] ), ARRAY_A);
			if ( !empty($membership_types) ):
				foreach ( $membership_types as $type ):
					$highest_rank = $type['rank_name'];
				endforeach;
			endif;

			$sponsor_name = get_user_meta( $user_data['sponsor'][0], 'first_name', true ) . ' ' . get_user_meta( $user_data['sponsor'][0], 'last_name', true );
			$upline_name = get_user_meta( $user_data['upline_name'][0], 'first_name', true ) . ' ' . get_user_meta( $user_data['upline_name'][0], 'last_name', true );



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

		?>
			<div class="information-table">
				<h2>Basic Member Information</h2>
				<p class="info">
					<label for="">Name of Member:</label>
					<span class="value"><?=$fullname;?></span>
				</p>
				<p class="info">
					<label for="">Highest Rank Achieved:</label>
					<span class="value"><?=$highest_rank;?></span>
				</p>
				<p class="info">
					<label for="">Date Registered:</label>
					<span class="value"><?php echo date("F d, Y", strtotime(get_userdata($user_ID)->user_registered)); ?></span>
				</p>
				<p class="info">
					<label for="">Member's ID Number:</label>
					<span class="value"><?=$user_data['member_id'][0];?></span>
				</p>
				<p class="info">
					<label for="">Current Paid Rank:</label>
					<span class="value">-</span>
				</p>
				<p class="info">
					<label for="">Current Month Points:</label>
					<span class="value">0</span>
				</p>
				<p class="info">
					<label for="">Accumulated Travel Points:</label>
					<span class="value">0</span>
				</p>
				<p class="info">
					<label for="">Accumulated Royalty Fund:</label>
					<span class="value">PHP <?=number_format('0', 2, '.', ',')?></span>
				</p>
				<p class="info">
					<label for="">Current Income:</label>
					<span class="value">PHP <?=number_format('0', 2, '.', ',')?></span>
				</p>
				<p class="info">
					<label for="">Sponsor Name:</label>
					<span class="value"><?=$sponsor_name;?></span>
				</p>
				<p class="info">
					<label for="">Upline Name:</label>
					<span class="value"><?=$upline_name;?></span>
				</p>
				<?php if (!$user_data['car_incentive_qualifier'][0]) { ?>
				<p class="info">
					<label for="">Car Incentive Qualifier:</label>
					<span class="value"><?=$user_data['car_incentive_qualifier'][0];?></span>
				</p>
				<?php } ?>
				
				<?php 
					if ( $user_data['membership_type'][0] !== '1' && $user_data['membership_type'][0] !== '6' ) {
					$downlines = $herbsmed->getMemberDownlines($user_ID); 
				?>
				
				<div class="direct-members">
					<h2>List Of All Direct Downlines</h2>
					<p class="info">Number of downline members: <span class="value"><?=count($downlines)?></span></p>
					<table class="downline-members">
						<thead>
							<th class="number">#</th>
							<th class="level">Level</th>
							<th class="idnum">ID Number</th>
							<th class="name">Name</th>
							<th class="rank">Current Rank</th>
							<th class="points">Travel PTS</th>
							<th class="fund">Royalty Fund</th>
							<th class="income">Current Income</th>
						</thead>
						<tbody>
							<?php $x=1; foreach ( $downlines as $downline ): ?>
							<tr class="<?=($x%2)?'even':'odd';?>">
								<td class="number"><?=$x?></td>
								<td class="level"><?=$downline['level']?></td>
								<td class="idnum">
									<?php if ( $downline['has_downline'] ) { ?>
									<a href=""><?=$downline['member_id']?></a>
									<?php } else { ?>
									<?=$downline['member_id']?>
									<?php } ?>
								</td>
								<td class="name"><?=$downline['name']?></td>
								<td class="rank"><?=$downline['period_rank']?></td>
								<td class="points"><?=$downline['travel_points']?></td>
								<td class="fund">Php <?=number_format($downline['savings_fund'], 2, '.', ',')?></td>
								<td class="income">Php <?=number_format($downline['income'], 2, '.', ',')?></td>
							</tr>
							<?php $x++; endforeach; ?>
						</tbody>
					</table>
				</div>

				<?php } ?>
				
			</div>
		<?php
		
		}
	}
	
	function membershipForm() {
		global $wpdb;
		$membership_types = $wpdb->get_results('SELECT * FROM `hmp_membership_type`', ARRAY_A);
		$types = array();
		$types[''] = '';

		$branch_location = $wpdb->get_results('SELECT * FROM `hmp_branch_location`', ARRAY_A);
		$branches = array();
		$branches[''] = '';
		foreach ( $branch_location as $branch ):
			$id = $branch['ID'];
			$name = $branch['branch_name'];
			$branches[$id] = $name;
		endforeach;
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

		?>
		<div class="clearfix"></div>
		<form action="<?php echo home_url('checkout'); ?>" method="post" class="member_registration register" autocomplete="off">
			<?php
			echo '<div id="membership_registration_fields">';
			echo '<h4 >' . __('Membership Type') . '</h4>';
			?>
				<p class="form-row">
					<label for="membership_type">Please select your desired membership</label>
					<select name="membership_type" id="membership_type" class="membership-select-type">
						<option value="">Select Membership Type</option>
						<?php foreach ( $membership_types as $types ): $meta = unserialize( $types['rank_meta'] );?>
						<option value="<?=$types['ID']?>" data-discount="<?=$meta['discount'];?>" data-max-price="0<?php //if ( class_exists('HerbsMedPharma') ) { $var = new HerbsMedPharma(); echo $var->get_max_price_total($types['ID']); } ?>" data-min-price="<?=intval($meta['group_sale']);?>"><?=$types['rank_name']?></option>
						<?php endforeach; ?>
					</select>
				</p>
				<div id="outwrite_dealer_container" class="outwrite">
					<h4 class="outwrite_title">Out Write Dealer?</h4>
					<input type="radio" name="outwrite_dealer" id="outwrite_dealer_yes" value="yes"/><label for="outwrite_dealer_yes">Yes</label>
					<input type="radio" name="outwrite_dealer" id="outwrite_dealer_no" value="no"/><label for="outwrite_dealer_no">No</label>
				</div>
				
				<div id="outwrite_membership_type">
					<h4>Please Select Products where Total Price NOT less than <span class="total_min_price"></span> but NOT more than <span class="total_max_price"></span>.</h4>
					<table>
						<thead>
							<tr>
								<td class="product_name">Product Name</td>
								<td class="product_price">Retail Price</td>
								<td class="product_quantity">Quantity</td>
							</tr>
						</thead>
						<tbody>
							<?php 
								$args =  array( 'post_type' => 'product' );
								$products = new WP_Query( $args );
								if ( $products->have_posts() ) {
									while( $products->have_posts() ) {
										$products->the_post();
										global $product;
										if ( floatval($product->price) > 0 ) {
							?>
							<tr class="product_tr_wrapper" id="product_<?php the_ID(); ?>">
								<td class="product_name">
									<input type="checkbox" class="product_id" name="product[]" id="product_id_<?php the_ID(); ?>" data-prod_price="<?=$product->price?>" data-qty="0" value="<?php the_ID(); ?>"/>
									<label for="product_id_<?php the_ID(); ?>"><?=get_the_title();?></label>
								</td>
								<td class="product_price" data-retail-price="<?=$product->price?>">₱ <?=number_format($product->price,2);?></td>
								<td class="product_quantity">
									<div class="quantity buttons_added">
										<input type="button" value="-" class="minus">
										<input type="number" step="1" name="quantity[]" value="0" title="Qty" class="input-text qty text" size="4">
										<input type="button" value="+" class="plus">
									</div>
								</td>
							</tr>
							<?php
										}
									}
								}
								wp_reset_postdata();
							?>
							<tr>
								<td colspan="2" align="right" style="font-size: 14px;">SUB TOTAL:</td>
								<td align="center"><span id="subtotal_product_price">₱ 0</span></td>
							</tr>
							<tr>
								<td colspan="2" align="right" style="font-size: 14px;">DISCOUNT:</td>
								<td align="center"><span id="membership_discount"></span></td>
							</tr>
							<tr>
								<td colspan="2" align="right" style="font-size: 16px; font-weight: bold;"><strong>TOTAL:</strong></td>
								<td align="center"><span id="total_product_selected_price">₱ 0</span></td>
							</tr>
						</tbody>
					</table>
					<p>Note: This will overwrite your existing product items on cart.</p>
				</div>
				
				<?php
				echo '<div id="dealer_packages_container"><h4>Select Dealer Package</h4>';
				echo '<p class="package"><input type="radio" value="Package 1" name="dealer_packages" id="dealer_package_one" />';
				echo '<label for="dealer_package_one">Dealer Package 1 </label><a href="#package1" class="view-package-products fancybox" id="one">View Products</a></p>';

				echo '<p class="package"><input type="radio" value="Package 2" name="dealer_packages" id="dealer_package_two" />';
				echo '<label for="dealer_package_two">Dealer Package 2</label><a href="#package2" class="view-package-products fancybox" id="two">View Products</a></p>';

				echo '<p class="package"><input type="radio" value="Package 3" name="dealer_packages" id="dealer_package_three" />';
				echo '<label for="dealer_package_three">Dealer Package 3</label><a href="#package3" class="view-package-products fancybox" id="three">View Products</a></p></div>';
				
				echo '<h4 >' . __('Account Information') . '</h4>';
				woocommerce_form_field( 'membership_username', array(
						'label'       	=> __('Desired Username'),
						'required'    	=> true,
						'clear'       	=> false,
						'type'        	=> 'text',
						'custom_attributes'	=>	array( 'autocomplete' => 'off' ),
						'class'       	=> array('membership-user-name required_field'),
					), $_POST['membership_username'] );
				woocommerce_form_field( 'membership_password', array(
						'label'       	=> __('Password'),
						'required'    	=> true,
						'clear'       	=> false,
						'type'        	=> 'password',
						'custom_attributes'	=>	array( 'autocomplete' => 'off' ),
						'class'       	=> array('membership-password required_field'),
					), $_POST['membership_password'] );
					
				echo '<h4 >' . __('Personal Information') . '</h4>';
				woocommerce_form_field( 'member_lastname', array(
						'label'       	=> __('Lastname'),
						'required'    	=> true,
						'clear'       	=> false,
						'type'        	=> 'text',
						'class'       	=> array('membership-lastname required_field'),
					), $_POST['member_lastname'] );
				woocommerce_form_field( 'member_firstname', array(
						'label'       	=> __('Firstname'),
						'required'    	=> true,
						'clear'       	=> false,
						'type'        	=> 'text',
						'class'       	=> array('membership-firstname required_field'),
					), $_POST['member_firstname'] );
				woocommerce_form_field( 'member_address', array(
						'label'       	=> __('Address'),
						'required'    	=> true,
						'clear'       	=> false,
						'type'        	=> 'textarea',
						'class'       	=> array('membership-address required_field'),
					), $_POST['member_address'] );
				woocommerce_form_field( 'member_branch', array(
						'label'       	=> __('Branch Office'),
						'required'    	=> true,
						'clear'       	=> false,
						'type'        	=> 'select',
						'class'       	=> array('membership-branch '),
						'options'     	=> $branches,
					), $_POST['member_branch'] );
				woocommerce_form_field( 'member_birthday', array(
						'label'       	=> __('Date of Birth'),
						'required'    	=> true,
						'clear'       	=> false,
						'type'        	=> 'text',
						'class'       	=> array('membership-birthday datepicker required_field'),
					), $_POST['member_birthday'] );
				woocommerce_form_field( 'member_email', array(
						'label'       	=> __('Email Address'),
						'required'    	=> false,
						'clear'       	=> false,
						'type'        	=> 'text',
						'class'       	=> array('membership-email'),
					), $_POST['member_email'] );
				woocommerce_form_field( 'civil_status', array(
						'label'       	=> __('Civil Status'),
						'required'    	=> true,
						'clear'       	=> false,
						'type'        	=> 'select',
						'class'       	=> array('membership-civil-status '),
						'options'     	=> array(
							'single' 	=> __('Single'),
							'married' 	=> __('Married'),
							'widow' 	=> __('Widow'),
							'separated' 	=> __('Separated')
						),
					), $_POST['civil_status'] );
				woocommerce_form_field( 'sponsor_name', array(
						'label'       	=> __('Sponsor Name'),
						'required'    	=> true,
						'clear'       	=> false,
						'type'        	=> 'select',
						'class'       	=> array('membership-sponsor '),
						'options'		=> $users
					), $_POST['sponsor_name'] );
				woocommerce_form_field( 'upline_name', array(
						'label'       	=> __('Upline Name'),
						'required'    	=> true,
						'clear'       	=> false,
						'type'        	=> 'select',
						'class'       	=> array('membership-upline '),
						'options'		=> $users
					), $_POST['upline_name'] );
				
				echo '<h4>Policies and Procedures</h4>';
				echo '<p>Any individual of legal age, who will sign up the application form and pay the amount thereof, becomes a duly registered independent distributor of Herbs Med Pharma whose membership are subject to the company’s rules and regulations.</p>';
				 
				echo '<h4>Terms and Conditions</h4>';
				echo '<ol>
					<li>Applicant should accomplish the independent Distributor Application form and submit the duly signed application form to its accredited product centers or Herbs Med Pharma office.</li>
					<li>All distributors are independent business owners of Herbs Med Pharma. There is no employee-employer relationship between distributors and the company. All distributors must comply legally the local and national ordinance and regulations about the operation of their business. New distributors are encourage to attend the series of trainings and seminar modules initiated by Herbs Med Pharma. There is no exclusive area for marketing or sponsoring activities.</li>
					<li>Sponsoring/Structuring <br/> Generally, request for a change of line of sponsorship is strictly prohibited unless a distributor will resign with an official letter send to the company. However, approval of the said change of sponsorship will only be effectual after 6 months of resignation. In the event that two sponsors are claiming a certain distributor, the company will only recognize the sponsor who submitted first the registration to the company.</li>
					<li>Products Labeling and Packaging <br/> Distributors are strictly prohibited in repackaging, renaming and repricing the product.</li>
					<li>Commision Payout <br/> Herbs Med Pharma will only pay the recorded commissions and bonuses only to those who have complied with the approved application and agreement of distributorship. </li>
					<li>Termination or Cancellation of Distribution <br/> A distributor may voluntarily terminate his distributorship status by sending a written notice to Herbs Med Pharma.</li>
					<li>Terms and conditions may change without prior notice.</li>
				</ol>';
				
				echo '<p><input type="checkbox" value="yes" id="i_understand" />';
				echo '<label for="i_understand">I understand, agree and submit to Herbs Med Pharma Policies and procedures.<abbr class="required" title="required">*</abbr></label></p>';
					
				echo '</div>';
				
				?>
			
				<!-- Spam Trap -->
				<div style="left:-999em; position:absolute;"><label for="trap"><?php _e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

				<p class="form-row">
					<?php wp_nonce_field( 'woocommerce-member-register', 'member-register' ); ?>
					<input type="submit" class="button" id="submit_register" value="<?php _e( 'Register', 'woocommerce' ); ?>" />
				</p>

			</form>
			
			<div style="display:none">
				<div id="package1" class="fb-packages" style="width: 500px;">
					<h2>Products for Dealer Package 1</h2>
					<ul>
						<li>Nanz Skin Perfection – 1 Clarifying Solution for Women (₱ 1,800)</li>
						<li>Nanz Skin Perfection – 1 24K Gold Cream (₱ 1,200)</li>
					</ul>
					<p>Plus ₱ 200.00 for Registration Fee</p>
					<p><strong>Total Price: ₱ 2,300.00</strong></p>
				</div>
				<div id="package2" class="fb-packages" style="width: 500px;">
					<h2>Products for Dealer Package 2</h2>
					<ul>
						<li>Nanz Skin Perfection – 1 Clarifying Solution for Women (₱ 1,800)</li>
						<li>Nanz Skin Perfection – 1 24K Gold Cream (₱ 1,200)</li>
						<li>Nanz Skin Perfection – 1 Body Bleaching Cream (₱ 1,800)</li>
						<li>Nanz Skin Perfection – 1 24K Gold Soap (₱ 150)</li>
					</ul>
					<p>Plus ₱ 200.00 for Registration Fee</p>
					<p><strong>Total Price: ₱ 2,700.00</strong></p>
				</div>
				<div id="package3" class="fb-packages" style="width: 500px;">
					<h2>Products for Dealer Package 3</h2>
					<ul>
						<li>Nanz Skin Perfection – 1 Clarifying Solution for Men (₱ 1,800)</li>
						<li>Nanz Skin Perfection – 1 24K Gold Soap (₱ 150)</li>
						<li>Nanz Skin Perfection – 1 Body Bleaching Cream (₱ 1,800)</li>
						<li>Nanz Skin Perfection – Hair Treatment (₱ 450)</li>
					</ul>
					<p>Plus ₱ 200.00 for Registration Fee</p>
					<p><strong>Total Price: ₱ 1,995.00</strong></p>
				</div>
			</div>
		<?php
	}
	
	// List of months
	function monthList() 
	{
		$months = array( '01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June', '07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December' );
		return $months;
	}
}

$templates = new PageTemplates;