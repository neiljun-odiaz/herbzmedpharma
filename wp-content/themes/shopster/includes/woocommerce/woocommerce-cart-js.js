jQuery(document).ready(function($) {

	$('body').bind('added_to_cart', icore_update_cart);


    // Update Shopping Cart
    function icore_update_cart()
    {

    	var menu_cart 		= jQuery('#top-container .cart_dropdown'),
    		dropdown_cart 	= menu_cart.find('.dropdown_widget_cart'),
    		subtotal 		= menu_cart.find('.cart_subtotal .amount');

    	var subtotal_new = jQuery('#top-container .dropdown_widget_cart .total .amount').html();

    	subtotal.html(subtotal_new);

    }

});
