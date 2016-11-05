jQuery(document).ready(function($) {
	$('#tabs').tabs();

	//hover states on the static widgets
	$('#dialog_link, ul#icons li').hover(
		function() { $(this).addClass('ui-state-hover'); },
		function() { $(this).removeClass('ui-state-hover'); }
	);
	
	$('#dealer_products').select2({
	    placeholder: "Select Products",
		dropdownAutoWidth: true,
		width: 'element'
	});

	
});