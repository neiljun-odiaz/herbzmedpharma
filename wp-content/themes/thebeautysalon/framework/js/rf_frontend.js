/**
 * Admin Control Scripts
 *
 * This file takes care of the javascript necessary for the 
 * frontend of your theme
 *
 */
 
jQuery(document).ready( function($) {
	resizing = false
	normalized_height = 0
	/**
	 * Radio and checkboxes
	 */
		$("div.checkbox").live("click", function() {
			if($(this).hasClass('status-off')) {
				$(this).removeClass('status-off').addClass('status-on');
				$(this).find("input").attr("checked", true);
			}
			else if ($(this).hasClass('status-on')) {
				$(this).removeClass('status-on').addClass('status-off');
				$(this).find("input").attr("checked", false);
			}
		});
	
		$("div.radio").live("click", function() {
			$(this).parent(".radios").find(".radio").removeClass('status-on').addClass('status-off');
			$(this).parent(".radios").find(".radio input").removeAttr('checked');
	
			$(this).removeClass('status-off').addClass('status-on');
			$(this).find("input").attr("checked", true);
	
		});

	/**
	 * Toggle Shortcode Controls
	 */
	 $(".toggle h4").click( function() {
		effect = $(this).parent().attr("data-effect")
		if(effect == 'fade') {
			$(this).parent().find(".toggle-content").fadeToggle( function() {
				$(this).parent().toggleClass("open").toggleClass("closed");
			});
		
		}
		else if (effect == 'none') {			
			$(this).parent().find(".toggle-content").toggle()
			$(this).parent().toggleClass("open").toggleClass("closed");
	
		} 
		else {
			$(this).parent().find(".toggle-content").slideToggle( function() {
				$(this).parent().toggleClass("open").toggleClass("closed");
			});
		}
	})
	

	/**
	 * User Friendly Input Clearing
	 */
	 $.each($("input, textarea"), function(index, element)  {
		if($(this).val() == "") {
			$(this).val($(this).attr("data-original"))
		}
	})
	
	$("input,textarea").focus(function() {
		if($(this).val() == $(this).attr("data-original")) {
			$(this).val("")
			if( $(this).hasClass('example') ) {
				$(this).removeClass('example')
				$(this).attr('data-example', 'yes')
			}
		}
	})
	
	$("input,textarea").blur(function() {
		if($(this).val() == "") {
			$(this).val($(this).attr("data-original"))
			if( $(this).attr('data-example') == 'yes' ) {
				$(this).removeAttr('data-example')
				$(this).addClass('example')
			}			
		}
	})	
	
	
	/**
	 * Image Hovers
	 */
	 
	// Animation
	$('.hoverfade').hover(
		function () {
			$(this).find('img').stop().animate({
				opacity: 0.60
			}, 300);
		},
		function () {
			$(this).find('img').stop().animate({
				opacity: 1.0
			}, 100);
		}
	);
	
	


	/**
	 * Post Sliders 
	 */	

	$('.postslider .pagination span').click( function() {
		$(this).parents('.postslider').find('.slide').hide()
		target = parseInt($(this).attr('data-target')) - 1
		
		$(this).parents('.postslider').find('.slide:eq(' + target + ')').show()
		$(this).parents('.postslider').find('.pagination span').removeClass('current');
		$(this).addClass('current')
	})

	/**
	 * Making sure menus don't overhang to the right 
	 */

	$('#header-nav ul li').mouseover( function() {
		
		offset = $(this).find('.children, .sub-menu').offset()
		menuRight = offset.left +  $(this).find('.children, .sub-menu').width()
		windowRight = $(window).width()
		if( windowRight < menuRight ) {
			$(this).find('.children, .sub-menu').css({'left' : 'auto', 'right' : '-11px'})
		}
	
	})
	
	$.each( $('#header-nav ul > li, .widget ul > li'), function(){
		if ( $(this).find('.children, .sub-menu').length > 0 ) {
			$(this).addClass('has-sub-menu')
		}
	})

	$('#header-nav .menu > li.has-sub-menu, #header-nav .menu ul > li.has-sub-menu ').append('<span class="triangle-down"></span>')
	
	$('#header-nav .menu > li.current_page_item, #header-nav .menu > li.current_menu_item').append('<span class="triangle-container"><span class="triangle-up"></span></span>')
	


})
	
	
	




function disableSubmit(form) {
	form.find('input[type="submit"]').attr('disabled', 'disabled').addClass('disabled')
}

function enableSubmit(form) {
	form.find('input[type="submit"]').removeAttr('disabled').removeClass('disabled')
}

function rf_show_form_errors(errors, form) {

	jQuery.each( errors, function(key, value) {
		form.find('*[name="'+ key +'"]').after('<div class="message error">' + value + '</div>')
	})
	
}




/**
 * Explodes a string into an array based on a given delimiter
 */
function explode (delimiter, string, limit) {

    var emptyArray = {
        0: ''
    };

    // third argument is not required
    if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {
        return null;
    }

    if (delimiter === '' || delimiter === false || delimiter === null) {
        return false;
    }

    if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object') {
        return emptyArray;
    }

    if (delimiter === true) {
        delimiter = '1';
    }

    if (!limit) {
        return string.toString().split(delimiter.toString());
    }
    // support for limit argument
    var splitted = string.toString().split(delimiter.toString());
    var partA = splitted.splice(0, limit - 1);
    var partB = splitted.join(delimiter.toString());
    partA.push(partB);
    return partA;
}

/**
 * Checks if an item is an a given array
 */
function in_array (needle, haystack, argStrict) {
    var key = '',
        strict = !! argStrict;

    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }

    return false;
}






