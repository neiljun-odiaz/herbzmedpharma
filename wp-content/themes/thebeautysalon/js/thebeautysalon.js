(function($,sr){
 
  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
      var timeout;
 
      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null; 
          };
 
          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);
 
          timeout = setTimeout(delayed, threshold || 100); 
      };
  }
	// smartresize 
	jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };
 
})(jQuery,'smartresize');


function advanceGallery( i ) {
	
		the_gallery = jQuery('.gallery[data-num='+i+']')
		next = get_next_item( the_gallery )
		
		fadeSpeed = the_gallery.attr('data-fadespeed')
		
		
		the_gallery.find('.item-box.current').find('.wp-post-image').fadeIn(fadeSpeed)
		
		if( jQuery('.gallery').width() > 680 ) {
		the_gallery.find('.item-box').removeClass('current')
		the_gallery.find('.item-box:eq('+ next +')').addClass('current')
		the_gallery.find('.item-box:eq('+ next +')').find('.wp-post-image').fadeOut( fadeSpeed )
		}
			
}


function get_next_item( the_gallery ) {

	if( the_gallery.attr('data-random') == 'yes') {
	
		current = the_gallery.find('.item-box.current:visible').index()
		do {
			item_count = the_gallery.find('.item-box:visible').length - 1
			random = rand(0,item_count)
			next = the_gallery.find('.item-box:eq('+ random +')').index()
		}
		while( current == next)
		
	}
	else {

		if ( the_gallery.find('.item-box.current:visible').length == 0 ) {
			next = the_gallery.find('.item-box:visible:first').index();
		}
		else {
			next = the_gallery.find('.item-box.current').nextAll('.item-box:visible:first')
	
			if( next.length == 0) {
				next = 0
			}
			else {
				next = next.index()
			}
		}
		
	}
		
	return next
}


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


function rand (min, max) {

    var argc = arguments.length;
    if (argc === 0) {
        min = 0;
        max = 2147483647;
    } else if (argc === 1) {
        throw new Error('Warning: rand() expects exactly 2 parameters, 1 given');
    }
    return Math.floor(Math.random() * (max - min + 1)) + min;

}

jQuery(window).load( function() {

	jQuery('.gallery-loading').remove()
	jQuery('.gallery').fadeIn()

	max = 0
	jQuery.each( jQuery('.item-box'), function() {
		h = jQuery(this).height()	
		w = jQuery(this).width() 	
		if(h > max) {
			max = h
		}
				
		jQuery(this).css({ 'height' : h+'px', 'width' : w+'px' })
	})
	
	jQuery.each( jQuery('.item-box'), function() {
		h = jQuery(this).height()	
		if(h < 10) {
			h = max
		}
				
		jQuery(this).css({ 'height' : h+'px'})
	})
	
	set_gallery_items_width()
			
})

gallery = new Array;
advance_speed = new Array;
current_product_page = 0

jQuery(document).ready( function() {
	jQuery('.gallery').before('<div class="gallery-loading">Gallery Loading</div>')
	jQuery('.gallery').hide()

	jQuery('input[type="submit"]:visible').wrap('<div class="read_more input" />')

	jQuery('.read_more').append('<div class="arrow"></div>')
	
	
	i=1
	jQuery.each( jQuery('.gallery'), function(){
		jQuery(this).attr('data-num', i)
		
		hide_unneeded_gallery_items( jQuery(this) )
		
		advance_speed[i] = jQuery(this).attr('data-wait')

		gallery[i] = setInterval( 'advanceGallery('+i+')', advance_speed[i] );	
		i++
	})	
	
	jQuery('.gallery .item-box').mouseenter( function() {
		num = jQuery(this).parents('.gallery').attr('data-num')
		clearInterval( gallery[num] )
		the_gallery = jQuery('.gallery[data-num='+num+']')
		the_gallery.find('.item-box').removeClass('current')
		the_gallery.find('.item-box').find('.wp-post-image').fadeIn(100)
		jQuery(this).addClass('current')
	})		
	
	jQuery('.gallery .item-box').mouseleave( function() {
		num = jQuery(this).parents('.gallery').attr('data-num')
		jQuery(this).find('.wp-post-image').fadeOut(0)
		gallery[num] = setInterval( 'advanceGallery('+num+')', advance_speed[num] );
	})
	
	jQuery('.gallery-filter span').click( function() {
		thegallery  = jQuery(this).parents('.gallery-filter').nextAll('.gallery:first')
		num = thegallery.attr('data-num')
		clearInterval( gallery[num] );
		
		thegallery.prevAll('.gallery-filter:first').find('span').removeClass('current')
		jQuery(this).addClass('current')
		
		
		target_category = jQuery(this).attr('data-category')
		if( target_category == 'all') {
			thegallery.find('.item-box').hide()
			thegallery.find('.item-box').fadeIn()
		}
		else {
			thegallery.find('.item-box').hide()
			jQuery.each( thegallery.find('.item-box'), function() {
				categories = jQuery.parseJSON( jQuery(this).attr('data-category') )
				if ( in_array(target_category, categories) ) {
					jQuery(this).fadeIn()
				}
			})
		}
		hide_unneeded_gallery_items(thegallery)
		gallery[num] = setInterval( 'advanceGallery('+num+')', advance_speed[num] );
	})
		
			set_products_width()

	jQuery( window ).smartresize( function() {
		set_products_width()
		set_gallery_items_width()
	})
	
	
	jQuery('.next_product_page').click( function() {
		next = current_product_page + 1
		target = jQuery('.product-page:eq(' + next + ')')
		jQuery('.prev_product_page').show()
		
		if(target.length > 0) {
			jQuery('.products-inner').scrollTo( target, 500, {axis: 'x', onAfter: function(){ resize_product_page() } } )
			current_product_page++
		}
		if( current_product_page >= jQuery('.products .page').length ) {
			jQuery('.next_product_page').hide()
		}
		
	
		return false
	})
	
	jQuery('.prev_product_page').click( function() {
		next = current_product_page - 1
		target = jQuery('.product-page:eq(' + next + ')')
		jQuery('.next_product_page').show()

		if(target.length > 0) {
			jQuery('.products-inner').scrollTo( target, 500, {axis: 'x', onAfter: function(){ resize_product_page() } } )
			current_product_page--
		}
		
		if( current_product_page == 0) {
			jQuery('.prev_product_page').hide()
		}
		
			
		return false
	})
			
});


function hide_unneeded_gallery_items(thisgallery) {
	numberposts = thisgallery.attr('data-numberposts') - 1
	thisgallery.find('.item-box:visible:gt('+numberposts+')').hide()
}



function resize_product_page() {
	theheight = jQuery('.product-page:eq(' + current_product_page + ')').height()
	jQuery('.product-pages-container').animate( { 'height' : theheight }, 1000 )
	
}



function set_gallery_items_width() {

	if( jQuery(window).width() < 600 ) {
		jQuery('.item-box, .item-box .item').width('100%').height('100%')
		jQuery.each( jQuery('.item-box'), function(){
			width = jQuery(this).find('img').width();
			height = jQuery(this).find('img').height();
			jQuery(this).find('.inner, img').css('margin', '0 auto')
			jQuery(this).width( width ).css('margin', '0 auto')
			push = (jQuery(window).width() - width) / 2
			jQuery(this).css('margin-left', push + 'px')
			
			iheight = jQuery(this).find('.item').height()
			cheight = jQuery(this).find('.inner').height()
			push = (iheight - cheight ) /2
			
			jQuery(this).find('.inner').css('padding-top', push + 'px')
			
		})
	}
	else {
		jQuery.each( jQuery('.gallery'), function() {
			width = jQuery(this).width()
			columns = parseInt(jQuery(this).attr('data-columns'))
			itemwidth = Math.floor(width / columns)
			remainder = width % columns
			
			jQuery(this).find('.item-box').width( itemwidth ).height(itemwidth)	
			if(remainder > 0) {
				jQuery(this).find('.item-box:nth-child(4n)').width( itemwidth + remainder )
			}
			
	
		})
	}
}



function set_products_width() {
	basic = jQuery('.products').width()
	count = parseInt(jQuery('.products').attr('data-count'))
	fullwidth = basic * count
	jQuery('.product-pages-container').width(fullwidth)
	jQuery('.product-page').width(basic)

}