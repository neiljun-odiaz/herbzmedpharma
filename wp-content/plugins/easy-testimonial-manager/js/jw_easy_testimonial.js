 jQuery(document).ready(function(){
		var jw_testi_slider1 = jQuery('.testimonial_fader1').bxSlider({
		mode: 'fade',
		auto: true,
		autoControls: false,
		autoHover: true,
		pause: 4000
	});


	jQuery('#pronext1').click(function(){
	  jw_testi_slider1.goToNextSlide();
	  return false;
	});

	jQuery('#proprev1').click(function(){
	  jw_testi_slider1.goToPrevSlide();
	  return false;
	});	
		var jw_testi_slider2 = jQuery('.testimonial_fader2').bxSlider({
		mode: 'fade',
		auto: true,
		autoControls: false,
		autoHover: true,
		pause: 4000
	});		
	
	jQuery('#pronext').click(function(){
	  jw_testi_slider2.goToNextSlide();
	  return false;
	});

	jQuery('#proprev').click(function(){
	  jw_testi_slider2.goToPrevSlide();
	  return false;
	});	
});				 