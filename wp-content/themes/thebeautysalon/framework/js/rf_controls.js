/**
 * Admin Control Scripts
 *
 * This file takes care of the javascript necessary for the
 * admin area of your theme
 *
 */
 
jQuery(document).ready( function ($) {

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
	 * Control Panel Sections
	 */
	
		// Hide all sections initially
		$(".rf_controls .container").hide();
	
		// Hide Uneeded Sections
		hash = window.location.hash.substr(1);
		if(hash != '') {
			$(".rf_controls .container#"+hash+"").show();
			$(".menu a").removeClass('active');
			$(".menu a[href='#"+hash+"']").addClass('active');
		}
		else {
			$(".rf_controls .container:first").show();
		}

		// Menu navigation
		$(".menu a").click( function () {
			$(".menu a").removeClass('active');
			$(this).addClass('active');
	
			section = $(this).attr('href').substr(1);
	
			$(".container").hide();
			$(".container#"+section).show();
			
			return false
		})


	/**
	 * Radio and checkboxes
	 */

		$("input.swatch-input").miniColors({
			change: function (hex) {
				$(this).parent().find(".swatch").css("background", hex);
			}
		});

	/**
	 * Multiple Select Boxes
	 */
	 
		$(".rf_controls select[multiple]").asmSelect({
			"sortable" : true,
		});

		// Multiselect save order
		if($(".asmList").length > 0) {
			$.each($(".asmList"), function() {
				list = $(this);
				select = $(this).prev();
	
				values = list.next().attr("data-selection")
				if(values != undefined) {
					order = explode(",", values)
					if(order.length > 0) {
						$.each(order, function(index, value) {
							id = select.find("option[value='"+value+"']").attr("rel");
							list.find("li[rel='"+id+"']").appendTo(list);
							list.next().find("option[value='"+value+"']").appendTo(list.next());
							//list.nextAll('select:first').find("option[value='"+value+"']").attr('selected', 'selected')
						})
					}
				}
			})
		}

	/**
	 * Uploaders
	 */
		if($(".rf_controls .upload-button").length > 0) {
			$('.upload-button').click(function() {
				formfield = $(this).attr('data-id');
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
				return false;
			});
			
			window.send_to_editor = function(html) {
				imgurl = $('img',html).attr('src');
				jQuery('input#'+formfield).val(imgurl);
				tb_remove();
			}
		}

	/**
	 * Example Videos
	 */
	 
	 $(".video").colorbox({
		inline:true,
		href: function() { return "#"+$(this).attr("href")  },
		onComplete: function() {
			$("#"+$(this).attr("href")).get(0).play()
		},
	});
	
	
	/**
	 * Framework Update Check
	 */	
	$("#check_framework_update").live('click', function() {
		$.ajax({
			type: 'post',
			url: ajaxurl,
			dataType: 'json',
			data: { action: 'rf_check_framework_update' },
			beforeSend: function() {
				disableElement( $("#check_framework_update") )
			}, 
			success: function(response) {				
				if(response.update == 'yes') {
					$("#check_framework_update").remove()
				}
				else {
					enableElement( $("#check_framework_update") )				
				}
				$('#framework_update_check_results').html(response.output)
			}
		})
	})
	
	
	
	/**
	 * Framework Update
	 */	
	$("#update_framework").live('click', function() {
		$.ajax({
			type: 'post',
			url: ajaxurl,
			dataType: 'json',
			data: { action: 'rf_update_framework' },
			beforeSend: function() {
				disableElement( $("#update_framework") )
			}, 
			success: function(response) {				
				$("#update_framework").remove()
				$('#framework_update_check_results').html('<h2>Update Successful</h2><p>You are now running the latest version of the framework</p>')	
			}
		})
		
		return false
	})
	
	
	/**
	 * Theme Update Check
	 */	
	$("#check_theme_update").live('click', function() {
		$.ajax({
			type: 'post',
			url: ajaxurl,
			dataType: 'json',
			data: { action: 'rf_check_theme_update' },
			beforeSend: function() {
				disableElement( $("#check_theme_update") )
			}, 
			success: function(response) {				
				if(response.update == 'yes') {
					$("#check_theme_update").remove()
				}
				else {
					enableElement( $("#check_theme_update") )				
				}
				$('#theme_update_check_results').html(response.output)
			}
		})
	})
	
	
	
	/**
	 * Theme Update
	 */	
	$("#update_theme").live('click', function() {
		var answer = confirm("Are you sure you want to update your theme?\n\nThis is a safe operation. If something goes wrong your WordPress data is still safe (posts, pages, etc).\n\nHowever, the modifications you've made inside the themes folder will all be lost.")
		if(answer) {
			$.ajax({
				type: 'post',
				url: ajaxurl,
				dataType: 'json',
				data: { action: 'rf_update_theme' },
				beforeSend: function() {
					disableElement( $("#update_theme") )
				}, 
				success: function(response) {				
					$("#update_theme").remove()
					$('#theme_update_check_results').html('<h2>Update Successful</h2><p>You are now running the latest version of the theme</p>')	
				}
			})
		}
		
		return false
	})
	
	

});



function disableElement( element ) {
	element.attr('disabled', 'disabled') 
	element.addClass('disabled')
	element.html('loading')
} 


function enableElement( element ) {
	element.removeAttr('disabled') 
	element.removeClass('disabled')
	element.html( element.attr('data-original') )
} 




/**
 * Explodes strings into an array, based on a given delimiter
 */

function explode (delimiter, string, limit) {

    var emptyArray = {
        0: ''
    };

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

    var splitted = string.toString().split(delimiter.toString());
    var partA = splitted.splice(0, limit - 1);
    var partB = splitted.join(delimiter.toString());
    partA.push(partB);
    return partA;
}