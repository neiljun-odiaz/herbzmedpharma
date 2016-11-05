jQuery( function( $ ) {

	/* 
	 * Membership Form
	 */
	$('.showregistration').click( function() {
		$( 'form.member_registration' ).slideToggle();
		$( 'form.login' ).slideToggle();
		return false;
	});
	
	$('#membership_type').select2({
	    placeholder: "Select Membership Type"
	});

	$('#membership_type').on('change', function(e){
		var type = e.added.text;
		
		if ( type == 'Franchise Center' ) {
			$('#outwrite_dealer_container').slideUp('fast');
			$('#outwrite_membership_type').slideUp('fast');
			return false;
		}
			
		$('#outwrite_membership_type').find('.total_min_price').html('₱ '+addCommas($(this).find(':selected').data('min-price').toFixed(2)));
		$('#outwrite_membership_type').find('.total_max_price').html('₱ '+addCommas($(this).find(':selected').data('max-price').toFixed(2)));		
			
		if ( type == 'Dealer' ) {
			$('#outwrite_dealer_container').slideDown('fast');
			$('#outwrite_membership_type').slideUp('fast');
		} else {
			$('#outwrite_dealer_container').slideUp('fast');
			$('#outwrite_membership_type').slideDown('fast');
		}

		$('#outwrite_membership_type').find('#membership_discount').html( $(this).find(':selected').data('discount') + '%' );

	});
	
	$('.outwrite > input[name=outwrite_dealer]').on('click', function(){
		if ( $(this).val() == 'no' ) {
			$('#dealer_packages_container').slideDown('fast');
			$('#outwrite_membership_type').slideUp('fast');
		} else {
			$('#dealer_packages_container').slideUp('fast');
			$('#outwrite_membership_type').slideDown('fast');
		}
	});
	
	$('.view-package-products').on('click', function(){
		
	});

	$('#civil_status').select2({
	    placeholder: "Select Civil Status"
	});

	$('#upline_name').select2({
	    placeholder: "Select Your Upline"
	});

	$('#sponsor_name').select2({
	    placeholder: "Select Your Sponsor"
	});

	$('#member_branch').select2({
	    placeholder: "Select Your Branch"
	});

	$('#user_referrer').select2({
	    placeholder: "Select your referral person",
	    allowClear: true
	});

	$('#member_birthday').datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: '1950:2014',
		minDate: new Date(1950, 1, 1)
	});

	$('#submit_register').on('click', function(){
		var this_form = $(this).parents('.member_registration.register');
		var hasError = 0;
		
		this_form.find('.required_field').each(function(index, val){
		
			if ( $.trim($(this).find('.input-text').val()) == "" ) {
				$(this).find('.input-text').css('border','1px solid rgb(255, 74, 74)');
				$(this).find('.input-text').css('backgroundColor','rgb(255, 231, 233)');
				hasError++;
				console.log( $(this).find('label').text() );
			} else {
				$(this).find('.input-text').css('border','1px solid #CCC');
				$(this).find('.input-text').css('backgroundColor','#FFF');
			}
			
		});

		if ( $('#membership_type').select2("val") == "" ) {
			hasError++;
			$('#s2id_membership_type').find('.select2-choice').css('border','1px solid rgb(255, 74, 74)');
		} else {
			$('#s2id_membership_type').find('.select2-choice').css('border','1px solid #aaa');
		}

		if ( $('#civil_status').select2("val") == "" ) {
			hasError++;
			$('#s2id_civil_status').find('.select2-choice').css('border','1px solid rgb(255, 74, 74)');
		} else {
			$('#s2id_civil_status').find('.select2-choice').css('border','1px solid #aaa');
		}

		if ( $('#upline_name').select2("val") == "" ) {
			hasError++;
			$('#s2id_upline_name').find('.select2-choice').css('border','1px solid rgb(255, 74, 74)');
		} else {
			$('#s2id_upline_name').find('.select2-choice').css('border','1px solid #aaa');
		}

		if ( $('#sponsor_name').select2("val") == "" ) {
			hasError++;
			$('#s2id_sponsor_name').find('.select2-choice').css('border','1px solid rgb(255, 74, 74)');
		} else {
			$('#s2id_sponsor_name').find('.select2-choice').css('border','1px solid #aaa');
		}

		if ( $('#member_branch').select2("val") == "" ) {
			hasError++;
			$('#s2id_member_branch').find('.select2-choice').css('border','1px solid rgb(255, 74, 74)');
		} else {
			$('#s2id_member_branch').find('.select2-choice').css('border','1px solid #aaa');
		}
		
		if ( hasError > 0 ) {
			alert('Please Fill In the Required Fields Above.');
			$('html, body').animate({
				scrollTop: $("#membership_registration_fields").offset().top
			}, 1000);
			return false; 
		}

		// Check if customer has select any of the Packages if they chose Dealer > Out write Dealer
		if ( $('#membership_type').select2("data").text == 'Dealer' && $('#outwrite_dealer_container').find('input[name=outwrite_dealer]:checked').val() == 'no' ) {
			if  ( typeof $('input[name=dealer_packages]:checked').val() === "undefined" ) {
				alert('You must select a Dealer Package above to procceed Member registration.');
				$('html, body').animate({
					scrollTop: $("#membership_registration_fields").offset().top
				}, 1000);
				return false;
			}
		}

		//Check if customer has selected some products
		if ( $('#membership_type').select2("data").text !== 'Dealer' && $('.product_id:checked').length == 0 ) {
			alert('You must select products to procceed Member registration.');
			$('html, body').animate({
				scrollTop: $("#membership_registration_fields").offset().top
			}, 1000);
			return false;
		}
		
		if ( !$('#i_understand').is(':checked') ) {
			alert('You must the above Terms and Condition by checking the checkbox.');
			return false;
		}
		
	});
	
	$('.product_quantity').on('change','.qty.text', function(){
		getProductTotalPrice( $(this) );
	});
	
	$('.product_name').on('change','.product_id', function(){
		getProductTotalPrice( $(this) );
	});
	
	function getProductTotalPrice( this_ ) {
		setTimeout(function(){
			var subtotal_price = 0;
			var total_price = 0;
			$('.product_tr_wrapper').each(function(index,val){
				var qty = parseInt( $(this).find('.qty.text').val() );
				var product_id = $(this).find('.product_name > input[type=checkbox]').val();
				var product_name = $(this).find('.product_name > label').text();
				var isChecked = $(this).find('.product_name > input[type=checkbox]').is(':checked');
				var price = $(this).find('.product_name > input[type=checkbox]').data('prod_price');
				var discount = $('#membership_type').find(':selected').data('discount');

				if ( isChecked ) {
					var new_price = price - parseFloat(( price * parseFloat(discount/100) ));
					total_price = total_price + parseFloat(new_price *  qty);
					subtotal_price = subtotal_price + parseFloat(price *  qty);
				} else {
					total_price = total_price + 0;
					subtotal_price = subtotal_price + 0;
				}

			});
			$('#total_product_selected_price').html('₱ '+addCommas(total_price.toFixed(2)));
			$('#subtotal_product_price').html('₱ '+addCommas(subtotal_price.toFixed(2)));
		}, 100);
	}
	
	/* 
	 * Membership Form
	 */
	
	/* $('#add_to_cart').on('click', function(e) {
		e.preventDefault();
		addToCart(46,3);
		return false;
	});
	
	function addToCart(p_id,qty) {
		$.get('?add-to-cart=' + p_id + '&quantity=' + qty, function(e) {
			
		});
	} */
	
	function addCommas(nStr) {
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}

});