<?php
/**
 * Checkout login form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( is_user_logged_in() ) return;

$info_message = apply_filters( 'woocommerce_checkout_login_message', __( 'Already a Member?', 'woocommerce' ) );

// $info_message .= ' If you\'re still not a Member,';
// $info_message .= ' <a href="'.home_url('membership-registration').'" class="showregistration">' . __( 'Click here to Register.', 'woocommerce' ) . '</a>';

?>

<p class="woocommerce-info"><?php echo esc_html( $info_message ); ?> <a href="#" class="showlogin"><?php _e( 'Click here to login', 'woocommerce' ); ?></a>. If you're still not a Member, <a href="<?php echo home_url('membership-registration'); ?>"><?php _e( 'Click here to Register.', 'woocommerce' ); ?></a></p>

<?php
	woocommerce_login_form(
		array(
			'message'  => __( 'If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing &amp; Shipping section.', 'woocommerce' ),
			'redirect' => get_permalink( woocommerce_get_page_id( 'checkout') ),
			'hidden'   => true
		)
	);
?>