jQuery( document ).ready( function() {
	makeSortable();
	
	jQuery('.add-page').live('click', function() {
		
		element = jQuery('.page').clone();
		element.find('.product_categories').html('')
		
		jQuery(element).insertBefore('.add-page')
		caluclate_page_numbers();
		makeSortable()
		
	})
	
	
	jQuery('.delete-page').live('click', function() {
		boxes = jQuery('.page-creator .page').length
		if(boxes > 1) {
			jQuery(this).parents('.page:first').remove();		
		}
		
		jQuery(this).parents('.page:first').find('.product_category').prependTo('#product_categories')

		caluclate_page_numbers();
		makeSortable()
		savePositions()
	})
	
	if( jQuery('.page-creator #pages').hasClass('populate') ) {
		jQuery.each( jQuery('#pages .product_categories'), function() {
			element = jQuery(this)
			ids = jQuery(this).attr('data-ids')
			ids = explode(',',ids)
			jQuery.each(ids, function(i, id){
				jQuery('.product_category[data-id='+id+']').appendTo(element)
			})
		})
	}
	
	
})


	function caluclate_page_numbers() {
		jQuery.each(jQuery('.page-creator .page'), function(i) {
			num = i+1;
			jQuery(this).find('.page-number').html('page ' + num)
		})
	}


	function makeSortable() {
		jQuery( ".product_categories" ).sortable( {
			connectWith: ".product_categories",
			placeholder: "placeholder",
			update : function(event, ui) { savePositions(event, ui) }
		});
		jQuery( ".product_categories" ).disableSelection();
	};
	
	
	function savePositions(event, ui) {
		positions = '{'
		jQuery.each(jQuery('.page-creator .page'), function(i) {
			
			if( i == 0 ) {
				positions = positions + '"'+i+'":{';
			}
			else {
				positions = positions + ',"'+i+'":{';
			}

			
			
			positions = positions + ' "left":{';
			elementlength = jQuery(this).find('.page_left .product_category').length
			if( elementlength > 0) {
				
				jQuery.each( jQuery(this).find('.page_left .product_category'), function(n){
					id = jQuery(this).attr('data-id')
					if( n+1 == elementlength ) {
						positions = positions + '"' + n + '":"' + id + '"';
					}
					else {
						positions = positions + '"' + n + '":"' + id + '",';
					}
					
				}) 
			
			}
			positions = positions + '}';
			
			
			
			positions = positions + ', "right":{';
			elementlength = jQuery(this).find('.page_right .product_category').length
			if( elementlength > 0) {
				
				jQuery.each( jQuery(this).find('.page_right .product_category'), function(n){
					id = jQuery(this).attr('data-id')
					if( n+1 == elementlength ) {
						positions = positions + '"' + n + '":"' + id + '"';
					}
					else {
						positions = positions + '"' + n + '":"' + id + '",';
					}
					
				}) 
			
			}
			positions = positions + '}';
			
			
			
			positions = positions + '}';
			
		})
		
		positions = positions + '}'				
		
		jQuery.ajax({
			url: ajaxurl,
			type: 'post',
			data: {
				positions: positions,
				action : 'rf_save_product_build'
			},
			success: function( response ) {
			}
		})
	}
	

	function explode (delimiter, string, limit) {

    if ( arguments.length < 2 || typeof delimiter == 'undefined' || typeof string == 'undefined' ) return null;
	if ( delimiter === '' || delimiter === false || delimiter === null) return false;
	if ( typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object'){
		return { 0: '' };
	}
	if ( delimiter === true ) delimiter = '1';
	
	// Here we go...
	delimiter += '';
	string += '';
	
	var s = string.split( delimiter );
	

	if ( typeof limit === 'undefined' ) return s;
	
	// Support for limit
	if ( limit === 0 ) limit = 1;
	
	// Positive limit
	if ( limit > 0 ){
		if ( limit >= s.length ) return s;
		return s.slice( 0, limit - 1 ).concat( [ s.slice( limit - 1 ).join( delimiter ) ] );
	}

	// Negative limit
	if ( -limit >= s.length ) return [];
	
	s.splice( s.length + limit );
	return s;
}
	
/*

		jQuery.each(jQuery('.page-creator .page'), function(i) {
			positions[i] = new Array();
			positions[i]['left'] = new Array()
			positions[i]['right'] = new Array()
			
			jQuery.each( jQuery(this).find('.page_left .product_category'), function(n){
				id = jQuery(this).attr('data-id')
				positions[i]['left'][n] = id	
			}) 
			jQuery.each( jQuery(this).find('.page_right .product_category'), function(n){
				id = jQuery(this).attr('data-id')
				positions[i]['right'][n] = id	
			}) 
			
		})
*/