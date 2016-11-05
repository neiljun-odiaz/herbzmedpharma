<?php
/*
	Plugin Name: 	HMP Total Sale
	Description: 	Count total sale of specific user and its leg.
	Author: 	 	Jess Anthony G. Compacion
	Version: 		1.0
*/

class HMP_Total_Sale
{
	function __construct()
	{
		// var_dump($this->getSale(2,'yearly'));
		register_activation_hook( __FILE__, array($this, 'install_tables') );
		add_action('woocommerce_order_status_completed',array($this,'statusComplete'));
		add_action('woocommerce_checkout_order_processed',array($this,'statusComplete'));
	}
	
	function install_tables()
	{
		global $wpdb;
		$schema = 'CREATE TABLE IF NOT EXISTS `hmp_total_sale` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `user_id` int(11) NOT NULL,
				  `order_id` int(11) NOT NULL,
				  `total` double NOT NULL,
				  `date_purchased` datetime NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;';
				
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $schema );
	}
	
	# If admin click COMPLETE button in order menu page, it will add information
	# in the table named hmp_total_sale (user_id,order_id,total,total_purchased)
	# make sure every user has usermeta:
	#			meta_key: 	'recruit_by'   - field
	#			meta_value: '1'            - userid of the one who recruiter.

	function statusComplete($orderid)
	{
		$order 	= new WC_Order($orderid);

		$total = $order->get_total();

		$post = get_post($orderid);

		$userid = get_post_meta($orderid,'_customer_user',true);

		if ( !$this->username_exists_by_id( $userid ) ) {

			$userid = get_post_meta($orderid,'order_user_referer',true);

		}

		$datePurchased = $post->post_date;

		$this->addCurrentDayTotalSale($userid,$orderid,$total,$datePurchased);
		
	}

	function username_exists_by_id($user_ID)
	{
	    global $wpdb;

	    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE ID = %d ", $user_ID));
	    
	    return $count;
	}

	function addCurrentDayTotalSale($userid, $orderid, $total, $datePurchased)
	{
		global $wpdb;	

		$data = array(
				'user_id'		=> $userid,
				'order_id'		=> $orderid,
				'total'			=> $total,
				'date_purchased'=> $datePurchased
			);

		$wpdb->insert('hmp_total_sale',$data);
	}

	function isPromoted($userid,$range,$hop)
	{
		$overallTotal = $this->getTotalSale($userid,$hop);

		if($overallTotal>=$range)
		{
			return true;
		}

		return false;
	}

	function getTotalSale($userid, $hop = 0, $recurrence ='')
	{
		$total = 0;

		$hop -= 1;

		$total += $this->getSale($userid,$recurrence);  # Add the totalSale of current user

		$recruitedUsers = get_users(array('meta_key'=>'sponsor','meta_value'=>$userid));   # Need to have this usermeta each user in registration.

		if($hop>0)
		{
			foreach($recruitedUsers as $recruit)
			{
				$recruitTotalSale = getSale($recruit->ID,$recurrence);

				$total += $recruitTotalSale; # Add the totalSale of recruited user

				$total += $this->getTotalSale($recruit->ID,$hop,$recurrence); #Get the total of generation
			}
		}

		return $total;
	}

	function getSale($userid, $recurrence = '')
	{
		global $wpdb;

		$total = 0;

		$where = '';

		if($recurrence == 'monthly')
		{
			$currentMonth = date('n');

			$where = "AND MONTH(date_purchased) = $currentMonth";

		}elseif($recurrence == 'yearly')
		{
			$currentMonth = date('Y');

			$where = "AND YEAR(date_purchased) = $currentMonth";
		}

		$posts = $wpdb->get_results("SELECT * FROM hmp_total_sale WHERE user_id='$userid' $where");

		foreach($posts as $post)
		{
			$total+=$post->total;
		}

		return $total;

	}

}

$hmpTotalSale = new HMP_Total_Sale();