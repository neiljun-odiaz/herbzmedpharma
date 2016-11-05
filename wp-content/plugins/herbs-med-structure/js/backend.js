jQuery(document).ready(function($) {
	$('#tabs').tabs();

	//hover states on the static widgets
	$('#dialog_link, ul#icons li').hover(
		function() { $(this).addClass('ui-state-hover'); },
		function() { $(this).removeClass('ui-state-hover'); }
	);
	
	if ( $('#filter_by_branch').length ) {
		$('#filter_by_branch').chosen();
		$('#filter_by_code').chosen();
		$('#dropdown_customers_chzn').hide();
	}
	
});