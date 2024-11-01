jQuery(document).ready(function($){
	$( '.wpwrps-product-slider' ).each(function( index ) {

		var slider_id   = $(this).attr('id');
		var slider_conf = $.parseJSON( $(this).closest('.wpwrps-product-slider-wrap').find('.wpwrps-slider-conf').attr('data-conf'));
		var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';

		jQuery('#'+slider_id+' .'+slider_cls).slick({
			dots			: (slider_conf.dots) == "true" ? true : false,
			infinite		: true,
			arrows			: (slider_conf.arrows) == "true" ? true : false,
			speed			: parseInt(slider_conf.speed),
			autoplay		: (slider_conf.autoplay) == "true" ? true : false,
			autoplaySpeed	: parseInt(slider_conf.autoplay_speed),
			slidesToShow	: parseInt(slider_conf.slide_to_show),
			slidesToScroll	: parseInt(slider_conf.slide_to_scroll),
			//rtl             : (slider_conf.rtl) == "true" ? true : false,
			rtl             : (Wpwrps.rtl == 1) 					? true : false,
			mobileFirst    	: (Wpwrps.is_mobile == 1) 			? true : false,
			responsive: [{
				breakpoint: 1023,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1,
					infinite: true,
					dots: false
				}
			},{

				breakpoint: 767,	  			
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 479,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: false
				}
			},
			{
				breakpoint: 319,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: false
				}	    		
			}]
		});
	});
	
	$( '.wpwrps-product-slider-widget' ).each(function( index ) {

		var slider_id   = $(this).attr('id');
		var slider_conf = $.parseJSON( $(this).closest('.wpwrps-product-slider-widget-wrap').find('.wpwrps-slider-conf-widget').attr('data-conf'));
		var slider_cls	= slider_conf.slider_cls ? slider_conf.slider_cls : 'products';

		jQuery('#'+slider_id+' .'+slider_cls).slick({
			dots			: (slider_conf.dots) == "true" ? true : false,
			infinite		: true,
			arrows			: (slider_conf.arrows) == "true" ? true : false,
			speed			: parseInt(slider_conf.speed),
			autoplay		: (slider_conf.autoplay) == "true" ? true : false,
			autoplaySpeed	: parseInt(slider_conf.autoplay_speed),
			slidesToShow	: parseInt(slider_conf.slide_to_show),
			slidesToScroll	: parseInt(slider_conf.slide_to_scroll),
			//rtl             : (slider_conf.rtl) == "true" ? true : false,
			rtl             : (Wpwrps.rtl == 1) 					? true : false,
			mobileFirst    	: (Wpwrps.is_mobile == 1) 			? true : false,
			responsive: [{
				breakpoint: 1023,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1,
					infinite: true,
					dots: false
				}
			},{

				breakpoint: 767,	  			
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 479,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: false
				}
			},
			{
				breakpoint: 319,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
					dots: false
				}	    		
			}]
		});
	});

});