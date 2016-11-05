<?php

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class MemberList extends WP_List_Table
{
	
	var $member_list = array();
	
	function __construct(){
		global $status, $page, $wpdb;

			parent::__construct( array(
				'singular'  => __( 'member' ),     //singular name of the listed records
				'plural'    => __( 'members' ),   //plural name of the listed records
				'ajax'      => false        //does this table support ajax?
		) );
		$search = (isset($_GET['s'])) ? $_GET['s'] : '';
		$user_fields = array( 'ID' );
		$args = array(
			'number' => '10',
			'role' => 'member',
			'search' => $search,
			'fields' => $user_fields
		);
		
		$wp_user_search = new WP_User_Query( $args );
		$user_ids = $wp_user_search->get_results();
		$users = array();
		foreach($user_ids as $id) {
			$user_id = $id->ID;
			$user_data = get_userdata( $user_id );
			$users[] = array(
				'ID'			=>	$user_id,
				'member_id'		=>	get_user_meta($user_id,'member_id',true),
				'full_name'		=>	$user_data->display_name,
				'user_name'		=>	$user_data->user_login,
				'email'			=>	$user_data->user_email,
				'registered'	=>	$user_data->user_registered,
				'rank'			=>	get_user_meta($user_id,'period_rank',true)
			);
		}
		
		$this->member_list = $users;
	}

	function column_default( $item, $column_name ) {
		return $item[$column_name];
	}

	function get_columns(){
		$columns = array(
			'ID'      		=> '<input type="checkbox" name="" id=""/>',
			'member_id'		=> __( 'Member ID' ),
			'full_name' 	=> __( 'Full Name' ),
			'user_name' 	=> __( 'Username' ),
			'email' 		=> __( 'Email Address' ),
			'registered' 	=> __( 'Registered Date' ),
			'rank' 			=> __( 'Current Paid Rank' )
		);
		return $columns;
	}
	
	function column_ID( $item ) {
		$id = '<input type="checkbox" name="user_id[]" id="" value="'.$item['ID'].'"/>';
		return $id;
	}
	
	function prepare_items() {
		$columns  = $this->get_columns();
		$hidden   = array();
		$per_page = 20;
		$current_page = $this->get_pagenum();
		$total_items = count($this->member_list);
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		
		$this->found_data = array_slice($this->member_list,(($current_page-1)*$per_page),$per_page);
		$this->set_pagination_args( array(
		'total_items' => $total_items,                  //WE have to calculate the total number of items
		'per_page'    => $per_page                     //WE have to determine how many items to show on a page
	  ) ); 
		$this->items = $this->found_data;
	}
	
	function get_sortable_columns() {
        $sortable_columns = array(
            'member_id'     => array('member_id',false),
            'registered'    => array('registered',true)
        );
        return $sortable_columns;
    }
	
}