<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
    <!-- Set up Meta -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header id="header">
        <div class="row large-uncollapse medium-uncollapse small-collapse">
            <div class="medium-4 small-12 columns">
                <div class="logo small-only-text-center">
                    <a href="<?php echo esc_url(home_url()); ?>">
                        <img src="<?php echo(get_header_image()); ?>" alt="<?php echo(get_bloginfo('title')); ?>" />
                    </a>
                </div>
            </div>
            <div class="medium-8 small-12 columns menu-section hide-for-small-only">
                <nav class="top-bar-menu">
                    <section class="top-bar-section">
                        <?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
                    </section>
                </nav>
            </div>
            <div class="hamb-ico hide-for-small-only">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </header>
    <div class="hamb-ico show-for-small-only">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
    <div class="mobile-menu-wrap menu-section show-for-small-only">
        <nav class="top-bar-menu">
            <section class="top-bar-section">
                <?php wp_nav_menu( array( 'theme_location' => 'header-menu' ) ); ?>
            </section>
        </nav>
    </div>

	<?php
	$top_ban_bg = get_field('background_top_banner');
	?>
	<div id="top" class="top-banner-wrap block-wrap" style="background-image: url(<?php echo $top_ban_bg['url']; ?>);">
		<div class="row top-banner-vis animated">
		    <div class="large-8 medium-12 columns">
		    	<?php if (get_field('body_text_top_banner')): ?>
		        	<?php the_field('body_text_top_banner'); ?>
		    	<?php endif ?>
		    </div>
		</div>
		<div class="hid-cont-wrap">
			<div class="row top-banner-hid">
				<?php if (get_field('left_column_text_top_banner')): ?>
					<div class="medium-6 columns">
						<div class="cont-wrap">
							<?php the_field('left_column_text_top_banner'); ?>
						</div>
					</div>
				<?php endif ?>

				<?php if (get_field('right_column_text_top_banner')): ?>
					<div class="medium-6 columns">
						<div class="cont-wrap">
							<?php the_field('right_column_text_top_banner'); ?>
						</div>
					</div>
				<?php endif ?>
			</div>
		</div>
		
	</div>

	<div id="about" class="about-wrap block-wrap">
		<div class="row">
			<div class="small-12 columns text-right">
				<?php if (get_field('title_about')): ?>
					<h2 class="section-title"><?php the_field('title_about'); ?></h2>
				<?php endif ?>
			</div>
			<div class="large-8 medium-12 columns text-right about-text">
				<?php if (get_field('body_text_about')): ?>
					<?php the_field('body_text_about'); ?>
				<?php endif ?>
			</div>
			<div class="large-4 medium-12 columns text-right about-img">
				<?php if (get_field('image_about')):
					$about_img = get_field('image_about'); ?>
					<img src="<?php echo $about_img['url']; ?>" alt="About Image">
				<?php endif ?>
			</div>
		</div>
	</div>

	<?php $crit_bg = get_field('background_acquisition_criteria'); ?>
	<div id="criteria" class="criteria-wrap block-wrap" style="background-image: url(<?php echo $crit_bg['url']; ?>);">
		<div class="row">
			<div class="small-12 columns text-left text-intro">
				<?php if (get_field('title_acquisition_criteria')): ?>
					<h2 class="section-title"><?php the_field('title_acquisition_criteria'); ?></h2>
				<?php endif ?>
				<?php if (get_field('text_acquisition_criteria')): ?>
					<?php the_field('text_acquisition_criteria'); ?>
				<?php endif ?>
			</div>
		</div>
		<?php if( have_rows('left_column_criteria') ): 
			$iteration = 2;
	        $row_start = '<div class="row crit-row">';
	        $row_end = '</div>';?>
	        <?php while( have_rows('left_column_criteria') ): the_row();
	            $title = get_sub_field('title');
	            $descr = get_sub_field('description');
	            ?>
	            <?php if( $iteration == 2 ) echo $row_start; ?>
	            <div class="medium-6 columns crit-item">
	                <h3><?php echo $title; ?></h3>
	                <p><?php echo $descr; ?></p>
	            </div>
	            <?php 
	        	$iteration--;
	            if($iteration == 0) $iteration = 2;
	            if($iteration == 2) echo $row_end; ?>
	        <?php endwhile; ?>
	        <?php if( $iteration != 0 && $iteration != 2 ) echo $row_end; ?>
	    <?php endif; ?>
	</div>

	<?php $arg = array(
	    'post_type'	    	=> 'portfolio',
	    'order'		    	=> 'ASC',
	    'orderby'	    	=> 'menu_order',
	    'posts_per_page'    => -1
	);
	$the_query = new WP_Query( $arg );
	if ( $the_query->have_posts() ) : 
		$i = 0;
		$num = 1; ?>
	    <div id="portfolio" class="portfolio-wrap block-wrap">
			<div class="row">
				<?php if (get_field('title_portfolio')): ?>
					<div class="small-12 columns text-center">
						<h2 class="section-title"><?php the_field('title_portfolio'); ?></h2>
					</div>
				<?php endif ?>
				<div class="portfolio-grid-wrap">
					<?php
					$iteration = 3;
			        $divide = '<div class="line-divide"></div>';
					while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				        	<div class="medium-4 columns portfolio-item-wrap item-<?php echo $num; ?>">
				        		<div class="portfolio-item" gal-attr="gal-<?php echo $i; ?>">
					        		<?php the_post_thumbnail('folio_thumb'); ?>
					        		<div class="portfolio-title text-center">
					        			<h4><?php the_title(); ?></h4>
					        		</div>
				        		</div>
				        		<?php if( have_rows('gallery_portfolio') ): ?>
					        		<div id="gal-<?php echo $i; ?>"	class="folio-gal-wrap">
								        <?php while( have_rows('gallery_portfolio') ): the_row();
								            $img = get_sub_field('photo');
								            ?>
								            <div class="slide" style="background-image: url(<?php echo $img['sizes']['folio_gal']; ?>);">
								            	<!-- <img src="<?php echo $img['sizes']['folio_gal']; ?>" /> -->
								            </div>
								        <?php endwhile; ?>
								        <div class="description-portfolio text-center">
								        	<h3><?php the_title(); ?></h3>
								        	<?php the_content(); ?>
								        </div>
								        <span class="close-btn">close</span>
							    	</div>
							    <?php endif; ?>
				        	</div>
			        	<?php
			        	$i++;
			        	if ($num == 3) { $num = 1; }
			        	else { $num++; }
			        	$iteration--;
	                    if($iteration == 0) $iteration = 3;
	                    if($iteration == 3) echo $divide; ?>
			        <?php endwhile; ?>
			        <?php if( $iteration != 0 && $iteration != 3 ) echo $divide; ?>
				</div>
		    </div>
	    </div>
	<?php endif; wp_reset_query(); ?>

	<?php $arg = array(
	    'post_type'	    	=> 'our_team',
	    'order'		    	=> 'ASC',
	    'orderby'	    	=> 'menu_order',
	    'posts_per_page'    => -1
	);
	$the_query = new WP_Query( $arg );
	if ( $the_query->have_posts() ) :
		$iteration = 6;
	    $row_start = '<div class="slick-slide">';
	    $row_end   = '</div>'; ?>
	    <div id="team" class="team-wrap block-wrap">
			<div class="row">
				<?php if (get_field('title_team')): ?>
					<div class="small-12 columns text-center">
						<h2 class="section-title"><?php the_field('title_team'); ?></h2>
					</div>
					<div class="clearfix"></div>
				<?php endif ?>
		        <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
		        	<div class="large-3 medium-4 columns member-wrap">
		        		<div class="member">
			        		<?php the_post_thumbnail('team_thumb'); ?>
			        		<div class="member-info text-center">
				        		<h4><?php the_title(); ?></h4>
				        		<?php the_content(); ?>
			        		</div>
		        		</div>
		        	</div>
		        <?php endwhile; ?>
		    </div>
	    </div>
	<?php endif; wp_reset_query(); ?>

	<div id="contact" class="row contact-row block-wrap">
		<?php if (get_field('title_contact')): ?>
			<div class="small-12 columns text-left">
				<h2 class="section-title"><?php the_field('title_contact') ?></h2>
			</div>
		<?php endif ?>

		<?php if( have_rows('columns_contact') ): ?>
	        <?php while( have_rows('columns_contact') ): the_row();
	            $content = get_sub_field('column_content');
	            ?>
	            <div class="large-3 medium-4 columns cont-col">
	                <?php echo $content; ?>
	            </div>
	        <?php endwhile; ?>
	    <?php endif; ?>

	    <?php if (get_field('image_contact')):
	    	$cont_img = get_field('image_contact'); ?>
	    	<img class="contact-img" src="<?php echo $cont_img['url']; ?>" alt="Contact Us">
	    <?php endif ?>
		
	</div>

	<footer>
	    <?php if(get_field('copyright_options', 'option')){ ?>
	    <div class="footer-top-wrap">
	        <div class="row">
	            <div class="large-12 columns text-center">
	                <p>Copyright <?php echo date('Y '); the_field('copyright_options', 'option'); ?></p>
	            </div>
	        </div>
	    </div>
	    <?php }?>

	    <?php if(get_field('disclaimer_footer', 'option')){ ?>
	        <div class="row">
	            <div class="large-12 columns text-center">
	                <?php the_field('disclaimer_footer', 'option'); ?>
	            </div>
	        </div>
	    <?php }?>
	</footer>
	<?php wp_footer(); ?>
</body>
</html>