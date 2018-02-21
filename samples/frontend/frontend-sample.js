;(function ($) {
//set window width
var topW = window.innerWidth ? window.innerWidth : $(window).width();

$(document).ready(function() {
	//menu button animation
	$('.hamb-ico').click(function(){
		$(this).toggleClass('open');
		$('.top-bar-section').toggleClass('open');
		$('.mobile-menu-wrap').toggleClass('open');
	});
	$('.top-bar-section ul li>a').click(function(){
		$('.hamb-ico').removeClass('open');
		$('.mobile-menu-wrap').removeClass('open');
	});

	// svg fill change
	$('img.contact-img').each(function(){
        var $img = $(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');
    
        $.get(imgURL, function(data) {
            // Get the SVG tag, ignore the rest
            var $svg = $(data).find('svg');
    
            // Add replaced image's ID to the new SVG
            if(typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if(typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass+' replaced-svg');
            }
    
            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');
            
            // Check if the viewport is set, else we gonna set it if we can.
            if(!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
            }
    
            // Replace image with new SVG
            $img.replaceWith($svg);
    
        }, 'xml');
    });

    // smooth menu scroll
    $('.top-bar-menu a[href^="#"]').on('click', function (e) {
        e.preventDefault();

        var target = this.hash;
        var $target = $(target);
        var scroll;

        scroll =  ($target.offset().top) - 70;
       
        $('html, body').stop().animate({
            'scrollTop': scroll
        }, 900, 'swing');
    });

    //portfolio slider init
    $('.folio-gal-wrap').slick({
    	centerMode: true,
    	focusOnSelect: true,
    	centerPadding: '25%',
    	dots: true,
    	slide: '.slide',
    	responsive: [
    		{
    			breakpoint: 641,
    			settings: {
    				centerPadding: '10%',
    			}
    		}
    	]
    });

    // portfolio slider opening
    $('.portfolio-wrap .portfolio-item').click(function(){
		var tabId = $(this).attr('gal-attr'),
			activeH = $('.folio-gal-wrap.active').height();

		if ($(this).hasClass('active')) {
			$(this).removeClass('active');
			$('.portfolio-wrap .portfolio-item-wrap .folio-gal-wrap').removeClass('active');
		} else {
			$('.portfolio-wrap .portfolio-item').removeClass('active');
			$('.portfolio-wrap .portfolio-item-wrap .folio-gal-wrap').removeClass('active');
			$(this).addClass('active');
			$("#"+tabId).addClass('active');
		}

		// smooth scroll gallery animation
		function gallSmoothScroll(){
			scrl =  ($('#' + tabId).offset().top);
			$('html, body').stop().animate({
	            'scrollTop': scrl
	        }, 900, 'swing');
		}
		setTimeout(gallSmoothScroll, 500);
	});

    // close gallery animation
	$('.folio-gal-wrap .close-btn').click(function(){
		$('.portfolio-wrap .portfolio-item').removeClass('active');
		$('.portfolio-wrap .portfolio-item-wrap .folio-gal-wrap').removeClass('active');
	});
    
});

//top screen animation functions
function headingAnim(){
	$('.top-banner-hid .cont-wrap h3').addClass('fadeInUp animated')
}
function contAnim(){
	$('.top-banner-hid .cont-wrap p').addClass('fadeInUp animated')
}

// check visibility function
function isVisible( row, container ){
    
    var elementTop = $(row).offset().top,
        elementHeight = $(row).height(),
        containerTop = container.scrollTop(),
        containerHeight = container.height();
    
    return ((((elementTop - containerTop) + elementHeight) > 0) && ((elementTop - containerTop) < containerHeight));
}

$(window).scroll(function() {
	// top screen animation
	if( $(window).scrollTop() > 0 && topW > 1024 ) {
		$('.top-banner-vis').addClass('fadeOutUp');
		$('.hid-cont-wrap')
		.addClass('animated');
		setInterval(headingAnim, 1000);
		setInterval(contAnim, 1500);
	} else {
		$('.top-banner-vis')
		.removeClass('fadeOutUp')
		.addClass('fadeIn');
		$('.hid-cont-wrap')
		.removeClass('animated');
	}

	// check if block on the screen
	$('.block-wrap').each(function(){
		if(isVisible($(this), $(window))){

			if ($(this).attr('id') == 'criteria') {
				$('#criteria .text-intro p').addClass('fadeIn animated');
			}

			if ($(this).attr('id') == 'contact') {

				$('#contact .cont-col').each(function(i){
				    var row = $(this);
				  	setTimeout(function() {
				        row
				        .addClass('fadeInUp animated');
				    }, 1000*i);
				});

				setTimeout(function() {
			        $('#contact .contact-img')
			        .addClass('green');
			    }, 3000);
			} 
		}; 
	});

	/* Check the location of each desired element */
    $('.portfolio-item-wrap, .member-wrap').each( function(i){
        
        var bottom_of_object = $(this).offset().top;
        var bottom_of_window = $(window).scrollTop() + $(window).height();
        
        /* If the object is completely visible in the window, fade it it */
        if( bottom_of_window > bottom_of_object ){
            $(this).addClass('vis');
        }
        
    });
    $('.block-wrap').each( function(i){
    	var $id = $(this).attr('id');
    	var $selector = '#' + $id;
        if ($id != 'about') {
        	var bottom_of_object = $(this).offset().top;
	        var bottom_of_window = $(window).scrollTop() + $(window).height();
	        
	        if( bottom_of_window > bottom_of_object ){
	        	setInterval( function(){
	            	$($selector).find('.section-title').addClass('visible');
	        	}, 500);
	        }
        }        
    });
    $('#about .section-title').each( function(i){
        
        var bottom_of_object = $(this).offset().top;
        var bottom_of_window = $(window).scrollTop() + $(window).height();
        
        /* If the object is completely visible in the window, fade it it */
        if( bottom_of_window > bottom_of_object ){
            setInterval( function(){
				$('#about .section-title').addClass('visible');
			}, 1000);
        }
        
    });

    $('#about .about-text, #about .about-img').each( function(i){
        
        var bottom_of_object = $(this).offset().top;
        var bottom_of_window = $(window).scrollTop() + $(window).height();
        
        /* If the object is completely visible in the window, fade it it */
        if( bottom_of_window > bottom_of_object ){
			setInterval( function(){
				$('#about .about-text').addClass('fadeInUp animated');
				$('#about .about-img').addClass('fadeInRight animated');
			}, 1500);
        }
        
    });

    $('.crit-row').each( function(i){
        var row = $(this);
        var bottom_of_object = row.offset().top;
        var bottom_of_window = $(window).scrollTop() + $(window).height();
        
        setTimeout(function() {
        /* If the object is completely visible in the window, fade it it */
        if( bottom_of_window > bottom_of_object ){
        	row
	        .addClass('fadeInLeft animated');
	        setTimeout(function() {
	        	row
	        	.find('h3')
	        	.addClass('green');
	        }, 1000);
	        setTimeout(function() {
	        	row
	        	.find('p')
	        	.addClass('fadeIn animated');
	        }, 2000);
        }
    	}, 1500*i);
        
    });

});

// full height homepage top banner
function aboutThumbnail(){
	var mainH = $( window ).height(),
		wrap  = $('.top-banner-wrap');
	wrap.css({'height' : mainH});
}

if ( topW > 1024 ) {
	aboutThumbnail();
}

$( window ).resize(function() {
	if ( topW > 1024) {
		aboutThumbnail();
	}
});

function galMargin(){
	var wW = $(window).width();
	// console.log(wW);
	$('.portfolio-wrap .portfolio-item-wrap.item-1 .folio-gal-wrap')
	.css('margin-left', 'calc(-100vw / 2 + ' + wW + 'px / 2 - 15px / 2)');
	$('.portfolio-wrap .portfolio-item-wrap.item-2 .folio-gal-wrap')
	.css('margin-left', 'calc(-100vw / 2 + ' + wW + 'px / 2 - ' + wW + 'px / 3 - 15px / 2)')
	$('.portfolio-wrap .portfolio-item-wrap.item-3 .folio-gal-wrap')
	.css('margin-left', 'calc(-100vw / 2 + ' + wW + 'px / 2 - ' + wW + 'px / 3 - ' + wW + 'px / 3 - 15px / 2)')
}

if ( $(window).width() > 640 && $(window).width() < 1200) {
	galMargin();
}

$( window ).resize(function() {
	if ( $(window).width() > 640 && $(window).width() < 1200) {
		galMargin();
	}
});


}(jQuery));