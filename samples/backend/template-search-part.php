<?php
// map view
$location_var = get_field('madrid_marker', 'option');
if( !empty($location_var) ) : ?>
	<div class="map-wrap">
		<div class="acf-map map-area-search hide-markers">
			<div style="visibility:hidden !important;" class="marker" data-lat="<?php echo $location_var['lat']; ?>" data-lng="<?php echo $location_var['lng']; ?>"></div>
		</div>
	</div>
<?php endif; ?>

<!-- filter view -->
<form class="full-serch" id="full-serch" action="">
	<div class="row full-filter">
		<div class="large-2 medium-6 small-12 columns matchHeight" id="avail-filter">
			<h3 class="form-filter-title">Availabilty</h3>
			<div class="input-group">
				<div class="row">
					<div class="medium-6 columns">
						<input type="checkbox" name="availability" value="jan-1"> 1/1<br>
						<input type="checkbox" name="availability" value="feb-1"> 2/1<br>
						<input type="checkbox" name="availability" value="mar-1"> 3/1<br>
						<input type="checkbox" name="availability" value="apr-1"> 4/1<br>
						<input type="checkbox" name="availability" value="may-1"> 5/1<br>
						<input type="checkbox" name="availability" value="jun-1"> 6/1<br>
						<input type="checkbox" name="availability" value="jul-1"> 7/1<br>
					</div>
					<div class="medium-6 columns">
						<input type="checkbox" name="availability" value="aug-1"> 8/1<br>
						<input type="checkbox" name="availability" value="aug-15"> 8/15<br>
						<input type="checkbox" name="availability" value="sep-1"> 9/1<br>
						<input type="checkbox" name="availability" value="oct-1"> 10/1<br>
						<input type="checkbox" name="availability" value="nov-1"> 11/1<br>
						<input type="checkbox" name="availability" value="dec-1"> 12/1<br>
						<input type="checkbox" name="availability" value="now" checked="checked"> Now<br>
					</div>
				</div>
			</div>
		</div>
		<div class="large-4 medium-6 small-12 columns matchHeight" id="loc-filter">
			<h3 class="form-filter-title">Locations</h3>
			<div class="input-group">
				<div class="row">
					<div class="medium-6 columns">
						<strong>Madison</strong><br>
						<input type="checkbox" name="location" value="east"> East<br>
						<input type="checkbox" name="location" value="west"> West<br>
						<input type="checkbox" name="location" value="downtown"> Downtown/Campus<br>
					</div>
					<div class="medium-6 columns">
						<strong>Suburban</strong><br>
						<input type="checkbox" name="location" value="middleton"> Middleton<br>
						<input type="checkbox" name="location" value="monona"> Monona<br>
						<input type="checkbox" name="location" value="verona"> Verona<br>
						<input type="checkbox" name="location" value="stoughton"> Stoughton<br>
					</div>
				</div>
			</div>
		</div>
		<div class="large-2 medium-6 small-12 columns matchHeight" id="room-filter">
			<h3 class="form-filter-title">Bedrooms</h3>
			<div class="input-group">
				<div class="row">
					<div class="medium-6 columns">
						<input type="checkbox" name="rooms" value="studio"> Studio<br>
						<input type="checkbox" name="rooms" value="1"> 1<br>
						<input type="checkbox" name="rooms" value="2"> 2<br>
					
						<input type="checkbox" name="rooms" value="3"> 3<br>
						<input type="checkbox" name="rooms" value="4"> 4<br>
						<input type="checkbox" name="rooms" value="5"> 5<br>
					</div>
				</div>
			</div>
		</div>
		<div class="large-2 medium-6 small-12 columns matchHeight" id="amenity-filter">
			<h3 class="form-filter-title">Amenities</h3>
			<div class="input-group">
				<?php
				$terms = get_terms( 'ammenities' );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : 
					foreach ( $terms as $term ) : ?>
						<?php echo '<input type="checkbox" name="amenity" value="' . $term->term_id . '"> ' . $term->name . '<br>'; ?>
					<?php endforeach; ?>
				<?php endif; ?>	
			</div>
		</div>
		<div class="large-2 medium-6 small-12 columns matchHeight" id="price-filter">
			<h3 class="form-filter-title">Rent Amount</h3>
			<div class="input-group">
				<input type="radio" name="price" value="1"> $600-$700<br>
				<input type="radio" name="price" value="2"> $700-$800<br>
				<input type="radio" name="price" value="3"> $800-$900<br>
				<input type="radio" name="price" value="4"> $900-$1,000<br>
				<input type="radio" name="price" value="5"> $1,000-$1,500<br>
				<input type="radio" name="price" value="6"> $1,500 and above
			</div>
		</div>
	</div>
</form>

<!-- apartments list view -->
<div class="row">
	<div class="large-12 medium-12 small-12 columns">
		<div class="cont-wrap">
			<!-- wpQuery -->
			<?php $arg = array(
		        'post_type'     => 'apartments',
				'order'         => 'ASC',
				'orderby'       => 'menu_order',
				'posts_per_page'=> -1,
				'tax_query' => array(
					array(
						'taxonomy' => 'availibility',
						'field'    => 'slug',
						'terms'    => 'now',
						'compare'  => 'LIKE'
					),
				)
		    );
		    $the_query = new WP_Query( $arg ); ?>
		   	<?php if ( $the_query->have_posts() ) : ?>
				<?php while ( $the_query->have_posts() ) : $the_query->the_post();
					$do_not_duplicate = $post->ID; ?>
					<div class="row collapse apartments-list-item">
						<!-- if thumbmail - two columns -->
						<?php if ( get_the_post_thumbnail() ) : ?>
							<div class="medium-5 columns">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail(); ?>
								</a>
							</div>
							<div class="medium-7 columns">
						<?php else : ?> <!-- if no - one columns -->
							<div class="medium-12 columns">
						<?php endif; ?>

						<?php
						// get adress from acf google map field
				        $loc = get_field('location_apartment');
				        $address_array[] = array(
				            "adr" => $loc['address']
				        );
				        $str = $loc['address'];
				        $cut_res = preg_replace('/^([^,]*).*$/', '$1', $str);
				        ?>
				        <h2 style="background-color: <?php echo $heading_title; ?>"><?php echo $cut_res; ?></h2>
						<?php if( have_rows('apartment_types') ): ?>
							<div class="type-wrap-list">
				                <?php while( have_rows('apartment_types') ): the_row();
				                    $type_obj = get_sub_field_object('type');
				                    $type_val = get_sub_field('type');
				                    $type     = $type_obj['choices'][ $type_val ];
				                    $price    = get_sub_field('price');
				                	?>
				                    <h3><?php  echo $type; ?> ($<?php echo $price; ?>)</h3>
				                <?php endwhile; ?>
				            </div>
				        <?php endif; ?>

				        <?php $descr = get_the_content(); ?>
						<p class="short-descr">
							<?php echo getWords($descr, 35) . '...'; ?>
				        	<a class="view-prop-btn" href="<?php echo get_the_permalink(); ?>">View Property</a>
				        </p>
						
						<!-- availibility taxonomy -->
						<?php $terms = get_the_terms( get_the_ID(), 'availibility' );
		                if ( $terms && ! is_wp_error( $terms ) ) : ?>
		                    <p class="avail"><strong>Availibility: </strong>
		                        <?php foreach ( $terms as $term ) : ?>
		                            <span><?php echo $term->name; ?></span>
		                        <?php endforeach; ?>
		                    </p>
		                <?php endif; ?>

						</div>
					</div>
				<?php endwhile; ?>
			<?php wp_reset_query(); ?>
			<?php else : ?>
				<h3 class="not-found-heading">Sorry, but nothing matched your search criteria.</h3>
			<?php endif; ?>
		</div>
	</div>
</div>