<?php

/*==============Full Search Template=================*/

add_action('wp_ajax_apartments_search_request', 'apartments_search_callback');
add_action('wp_ajax_nopriv_apartments_search_request', 'apartments_search_callback');

function apartments_search_callback() {
	global $wpdb;
	$price_val = 0;
	if (isset($_POST['price'])) {
		$price_val = $_POST['price'];
	}

	$room = 0;
	$room   = ( isset($_POST['room']) ) ? $_POST['room'] : "";

	$location = 0;
	$location   = ( isset($_POST['location']) ) ? $_POST['location'] : "";

	$availability = 0;
	$availability   = ( isset($_POST['availability']) ) ? $_POST['availability'] : "";

	$amenity = 0;
	if (isset($_POST['amenity'])) {
		$amenity = $_POST['amenity'];
	}

	$marker_gen = get_field( "madrid_marker", "options" );

	$map        = '<div class="acf-map map-area-search hide-markers">
					<div style="visibility:hidden;" class="marker" data-lat="' . $marker_gen["lat"] . '" data-lng="' . $marker_gen["lng"] . '">guhvgfj</div>
				</div>';
	$apartments = '';
	$result_full     = array();

	// query for locations

	$post_id_array_full = array();

	if( !empty($location) ) {
		foreach( $location as $loc ) {
			$rows = $wpdb->get_results( $wpdb->prepare( 
				"
				SELECT * 
				FROM {$wpdb->prefix}postmeta
				WHERE (meta_key = %s AND meta_value = %s)
				OR (meta_key = %s AND meta_value = %s)
				", 'madison_district_apartment', $loc, 'suburban_district_apartment', $loc
			));

			foreach( $rows as $row ) {
				$post_id_array_full[] = $row->post_id;		
			}
		}
	}

	// query for rooms

	$post_id_array_rooms = array();

	if( !empty($room) ) {
		foreach( $room as $room_one ) {
			$rows_room = $wpdb->get_results( $wpdb->prepare( 
				"
				SELECT * 
				FROM {$wpdb->prefix}postmeta
				WHERE meta_key LIKE %s
				AND meta_value = %s
				", 'apartment_types_%_type', $room_one
			));

			foreach( $rows_room as $row_room ) {
				$post_id_array_rooms[] = $row_room->post_id;		
			}
		}
	}

	// query for price
	$post_id_array_price = array();
	if($price_val){
		switch ($price_val) {

			case 1:
			$start_price = 50;
			$end_price = 699;
			break;

			case 2:
			$start_price = 700;
			$end_price = 799;
			break;

			case 3:
			$start_price = 800;
			$end_price = 899;
			break;

			case 4:
			$start_price = 900;
			$end_price = 999;
			break;

			case 5:
			$start_price = 1000;
			$end_price = 1499;
			break;

			case 6:
			$start_price = 1500;
			$end_price = 99999999;
			break;
		}

		$rows_price = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT * 
			FROM {$wpdb->prefix}postmeta
			WHERE meta_key LIKE %s
			AND CAST(meta_value AS SIGNED) BETWEEN %s AND %s
			", 'apartment_types_%_price', $start_price, $end_price
		));

		foreach( $rows_price as $row_price ) {
			$post_id_array_price[] = $row_price->post_id;		
		}
	}

	// create final array for view
	if( !empty($location) && !empty($room) && !empty($price_val) ) {
		$post_id_array = array_intersect( $post_id_array_full, $post_id_array_rooms, $post_id_array_price );
	}
	elseif( !empty($location) && !empty($room) ) {
		$post_id_array = array_intersect( $post_id_array_full, $post_id_array_rooms );
	}
	elseif( !empty($location) && !empty($price_val) ) {
		$post_id_array = array_intersect( $post_id_array_full, $post_id_array_price );
	}
	elseif( !empty($room) && !empty($price_val) ) {
		$post_id_array = array_intersect( $post_id_array_rooms, $post_id_array_price );
	}
	elseif( !empty($location) ) {
		$post_id_array = $post_id_array_full;
	}
	elseif( !empty($room) ) {
		$post_id_array = $post_id_array_rooms;
	}
	elseif( !empty($price_val) ) {
		$post_id_array = $post_id_array_price;
	}
	else {
		$post_id_array = array( "" );
	}
	$args = array(
		'post_type'     => 'apartments',
		'order'         => 'ASC',
		'orderby'       => 'menu_order',
		'posts_per_page'=> -1
	);

	if( !empty($room) || !empty($location) || !empty($price_val) ) {
		$args["post__in"] = ( !empty($post_id_array) ) ? $post_id_array : array( "" );
	}

	if($amenity){
		$args['tax_query'][] = array(
			'taxonomy' => 'ammenities',
			'field'    => 'term_id',
			'terms'    => $amenity,
			'operator' => 'AND'
		);
	}

	if($availability){
		$args['tax_query'][] = array(
			array(
				'taxonomy' => 'availibility',
				'field'    => 'slug',
				'terms'    => $availability,
				'compare'  => 'LIKE'
			)
		);
	}

	if ( ( $availability != 0 ) || ( $price_val != 0 ) || ( $room != 0 ) || ( $location != 0 ) || ( $amenity != 0 ) ) :
		$the_query = new WP_Query( $args );

		if( $the_query->have_posts() ) :
			
			// MAP VIEW   

			$address_array = array();

			while ( $the_query->have_posts() ) :
				$the_query->the_post();
				$loc = get_field( 'location_apartment' );
				$thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'marker_thumb');
				//adress
				$str_mark = $loc['address'];
				$cut_res_mark = preg_replace('/^([^,]*).*$/', '$1', $str_mark);
				//type
				$type_mark_obj = get_field_object('apartment_type');
				$value_type_mark = get_field('apartment_type');
				$type_mark = $type_mark_obj['choices'][ $value_type_mark ];

				$address_array[] = array(
					"title"   => get_the_title(),
					"thumb"   => $thumb[0],
					"address" => $cut_res_mark,
					"ap_ID"	  => get_the_id(),
					"link"	  => get_the_permalink(),
					"lng"     => $loc['lng'],
					"lat"     => $loc['lat']
				);
			endwhile;

			$map_start = '<div class="acf-map map-area-search">';
			$map_view  = '';
			$map_end   = '</div>';

			foreach( $address_array as $marker ) :
				$map_view .= '<div class="marker" data-lat="' . $marker["lat"] . '" data-lng="' . $marker["lng"] . '">';
				if ( $marker["thumb"] ) {
					$map_view .= '<img src="' . $marker["thumb"] . '" alt="Marker Thumbnail"><br>';
				}
				$map_view .= $marker["address"] . '<br>';
				if( have_rows('apartment_types', $marker["ap_ID"]) ) {
					$map_view .= '<div class="type-wrap-list">';
						while( have_rows('apartment_types', $marker["ap_ID"]) ) { the_row();
							$type_obj = get_sub_field_object('type');
							$type_val = get_sub_field('type');
							$type     = $type_obj['choices'][ $type_val ];
							$price    = get_sub_field('price');
							
							$map_view .= '<strong>' . $type . '($' . $price . ')</strong>';
						}
					$map_view .= '</div>';
				};
				$map_view .= '<a href="' . $marker["link"] . '" title="' . $marker["address"] . '">View Property</a>';
				$map_view .= '</div>';
			endforeach;

			$map = $map_start . $map_view . $map_end;

			// APARTMENTS VIEW
			while ( $the_query->have_posts() ) :
				$the_query->the_post();

					if ( get_field('madison_district_apartment') == 'none' ) {
						$location_apartment = get_field('suburban_district_apartment');
					} else {
						$location_apartment = get_field('madison_district_apartment');
					};

					if ($location_apartment == 'east') {
						$heading_title = get_field('madison_east_color', 'option');

					} elseif ($location_apartment == 'west') {
						$heading_title = get_field('madison_west_color', 'option');

					} elseif ($location_apartment == 'downtown') {
						$heading_title = get_field('downtown_crampus_color', 'option');

					} elseif ($location_apartment == 'middleton') {
						$heading_title = get_field('middleton_color', 'option');

					} elseif ($location_apartment == 'monona') {
						$heading_title = get_field('monona_color', 'option');

					} elseif ($location_apartment == 'verona') {
						$heading_title = get_field('verona_color', 'option');

					} elseif ($location_apartment == 'stoughton') {
						$heading_title = get_field('stoughton_color', 'option');
					};

					$type_obj  = get_field_object( 'apartment_type' ); 
					$value = get_field('apartment_type');
					$type = $type_obj['choices'][ $value ];

					$price = get_field( 'price_apartment' );
					$loc   = get_field( 'location_apartment' );

					$address_array[] = array(
						"adr" => $loc['address']
					);

					$str = $loc['address'];
					$cut_res = preg_replace('/^([^,]*).*$/', '$1', $str);

					$apartments .= '<div class="row collapse apartments-list-item">';
					if ( get_the_post_thumbnail() ) {
						$apartments .= '<div class="medium-5 columns dsds">';
						$apartments .= '<a href="'.get_the_permalink().'">';
						$apartments .= get_the_post_thumbnail();
						$apartments .= '</a>';
						$apartments .= '</div>';
						$apartments .= '<div class="medium-7 columns">';
					} else {
						$apartments .= '<div class="medium-12 columns">';
					}
					$apartments .= '<h2 style="background-color:' . $heading_title . '">' . $cut_res . '</h2>';

					if( have_rows('apartment_types') ) {
						$apartments .= '<div class="type-wrap-list">';
							while( have_rows('apartment_types') ) { the_row();
								$type_obj = get_sub_field_object('type');
								$type_val = get_sub_field('type');
								$type     = $type_obj['choices'][ $type_val ];
								$price    = get_sub_field('price');

								$apartments .= '<h3>' . $type . '($' . $price . ')</h3>';
							}
						$apartments .= '</div>';
					};

					$descr = get_the_content();
					$apartments .= '<p class="short-descr">';
					$apartments .= getWords($descr, 35) . '...';
					$apartments .= '<a class="view-prop-btn" href="' . get_the_permalink() . '">View Property</a>';
					$apartments .= '</p>';

					$terms = get_the_terms( get_the_ID(), 'availibility' );
	                if ( $terms && ! is_wp_error( $terms ) ) {
	                    $apartments .= '<p class="avail"><strong>Availibility: </strong>';
	                        foreach ( $terms as $term ) {
	                            $apartments .= '<span>' . $term->name . '</span>';
	                        }
	                    $apartments .= '</p>';
	                }
					$apartments .= '</div></div>';
					
			endwhile;
		else :
			$apartments .= '<h3 class="not-found-heading">Sorry, but nothing matched your search criteria.</h3>';
		endif;

	endif;

	// Callback
	$result_full = array(
		'map'        => $map,
		'apartments' => $apartments,
		'res'		 => $post_id_array
	);

	echo json_encode( $result_full );

	wp_die();
}

?>