(function($) {
	/*new_map*/
	function new_map( $el ) {
		// var
		var $markers = $el.find('.marker');
		// vars
		var args = {
			zoom		: 10,
			center		: new google.maps.LatLng(0, 0),
			mapTypeId	: google.maps.MapTypeId.ROADMAP,
			disableDefaultUI : true,
			scrollwheel      : false,
			zoomControl      : true,
			draggable        : true
		};
		// create map	        	
		var map = new google.maps.Map( $el[0], args);
		// add a markers reference
		map.markers = [];
		// add markers
		$markers.each(function(){
			add_marker( $(this), map );
		});
		// center map
		center_map( map );
		// return
		return map;
	}
	/* add_marker */

	function add_marker( $marker, map ) {
		// var
		var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );
		// create marker
		var marker = new google.maps.Marker({
			position	: latlng,
			map			: map
		});
		// add to array
		map.markers.push( marker );
		// if marker contains HTML, add it to an infoWindow
		if( $marker.html() )
		{
			// create info window
			var infowindow = new google.maps.InfoWindow({
				content		: $marker.html()
			});
			// show info window when marker is clicked
			google.maps.event.addListener(marker, 'click', function() {
				infowindow.open( map, marker );
			});
		}
	}
	/*center_map*/
	function center_map( map ) {
		// vars
		var bounds = new google.maps.LatLngBounds();
		// loop through all markers and create bounds
		$.each( map.markers, function( i, marker ){
			var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
			bounds.extend( latlng );
		});
		// only 1 marker?
		if( map.markers.length == 1 )
		{
			// set center of map
			map.setCenter( bounds.getCenter() );
			map.setZoom( 9 );
		}
		else
		{
			// fit to bounds
			map.fitBounds( bounds );
		}
	}
	// global var
	var map = null;
  
  // code fore search functioning
	$(document).ready(function(){

		$('.acf-map').each(function(){
			// create map
			map = new_map( $(this) );
		});

		$('#full-serch input').click(function(){
			$( "#full-serch" ).submit();
		});

		$('#full-serch').submit(function(event){
			event.preventDefault();
			var location 	   = [],
				  room 	 	     = [],
				  amenity  	   = [],
				  availability = [];
      
      // get filters value
			if ( $('#price-filter input').is(':checked') ){
				var price  = $('#price-filter input:checked').val();
			}
			$('#room-filter input:checked').each(function(i) {
				room[i] = $(this).val();
			})
			$('#loc-filter input:checked').each(function(i) {
				location[i] = $(this).val();
			})
			$('#avail-filter input:checked').each(function(i) {
				availability[i] = $(this).val();
			})
			$('#amenity-filter input:checked').each(function(i) {
				amenity[i] = $(this).val();
			})
      
      // ajax call
			$.ajax({
				url      : site_domain.site_domain_url,
				method   : 'POST',
				dataType : 'JSON',
				data     : {
					'action'   	   : 'apartments_search_request',
					'price'	   	   : price,
					'room'     	   : room,
					'location' 	   : location,
					'amenity'	     : amenity,
					'availability' : availability
				},
				success : function( callback ) {
					$('.acf-map').remove();
					$('.map-wrap').html( callback.map );
					new_map( $('.acf-map') );     
					$('.cont-wrap').html( callback.apartments );
				},
				error : function( errorThrown ) {
					console.log(errorThrown);
				}
			});  
		});
	});
})(jQuery);