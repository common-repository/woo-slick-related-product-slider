<?php
/**
 * Function to check woocommerce compatibility
 * 
 * @package Slick Related Product Slider For Woocommerce
 * @since 1.0
 */
function wpwrps_wc_version($version = '3.0'){
    global $woocommerce;
    if( version_compare( $woocommerce->version, $version, ">=" ) ) {
      return true;
    }
    return false;
}

/**
 * Function to unique number value
 * 
 * @package Slick Related Product Slider For Woocommerce
 * @since 1.0
 */
function wpwrps_get_unique() {
    static $unique = 0;
    $unique++;

    return $unique;
}

/**
 * Function to after single product slider filter
 * 
 * @package Slick Related Product Slider For Woocommerce
 * @since 1.0
 */
add_action( 'woocommerce_after_single_product', 'wpwrps_product_single_page_slider', 10 );
function wpwrps_product_single_page_slider($atts) {

	global $woocommerce_loop, $product;
	$output = null;
 	//$content = "";
	extract(shortcode_atts(array(
		'cats' 				=> '',
		'tax' 				=> 'product_cat',	
		'limit' 			=> '-1',	
		'slide_to_show' 	=> '4',
		'slide_to_scroll' 	=> '1',
		'autoplay' 			=> 'true',
		'autoplay_speed' 	=> '3000',
		'speed' 			=> '300',
		'arrows' 			=> 'true',
		'dots' 				=> 'true',
		'rtl'  				=> '',
		'slider_cls'		=> 'products',
	), $atts));
	// unique id in slider
	$unique = wpwrps_get_unique();
	
	//Slider Class Add Ul
	$cat 		= (!empty($cats)) ? explode(',',$cats) 	: '';
	$slider_cls = !empty($slider_cls) ? $slider_cls : 'products';
	
	//Linkled Products 
	$crosssell_ids = get_post_meta( get_the_ID(), '_crosssell_ids' ); 
	$crosssell_ids = $crosssell_ids[0];
	$upsells = get_post_meta( get_the_ID(), '_upsell_ids' ); 
	$upsells = $upsells[0];
	if(!empty($crosssell_ids) && !empty($upsells)){
		$link_product = array_merge($crosssell_ids,$upsells);
	}elseif(!empty($crosssell_ids)){
		$link_product = $crosssell_ids;
	}else{
		$link_product = $upsells;
	}	
	

	//Category Select Product
	$terms = get_the_terms(get_the_ID(), 'product_cat' );
    if(!empty($terms)){
        foreach ($terms as $term) {
        	$woo_cat_id = $term->term_id;
        }
 	 }

	// For RTL
	if( empty($rtl) && is_rtl() ) {
		$rtl = 'true';
	} elseif ( $rtl == 'true' ) {
		$rtl = 'true';
	} else {
		$rtl = 'false';
	}
	
	//Enqueue Script Added
	wp_enqueue_script( 'wpos-slick-jquery' );
	wp_enqueue_script( 'wpwrps-public-jquery' );
	
	// Slider configuration
	$slider_conf = compact('slide_to_show', 'slide_to_scroll', 'autoplay', 'autoplay_speed', 'speed', 'arrows','dots', 'rtl', 'slider_cls'); 
	
	ob_start();	 
	
	//Return function
	$content = ob_get_clean();
	if($content) { $output .= $content; }
	
	// setup query
	$args = array(
		'post_type' 			=> 'product',
		'post_status' 			=> 'publish',
		'ignore_sticky_posts'   => 1,
		'posts_per_page'		=> -1,
		'post__in' 				=> $link_product,
	);
	
	// Category Tax Query Parameter
	if(!empty($woo_cat_id) && empty($link_product)) {			
		$args['tax_query'] = array(
									array( 
											'taxonomy' 	=> 'product_cat',
											'field' 	=> 'id',
											'terms' 	=> $woo_cat_id, 
								));
	}		
		
	//WP Query Database
	$products = new WP_Query( $args );		
	if(!empty($link_product) || !empty($woo_cat_id)){	
		if ( $products->have_posts() ) : ?>
			
			<div class="wpwrps-product-slider-wrap wpwrps-clearfix">
			<h2><?php _e( 'Related products', 'slick-related-product-slider-for-woocommerce' ); ?></h2>
			<div class="woocommerce wpwrps-product-slider" id="wpwrps-product-slider-<?php echo $unique; ?>">
			<?php 
				woocommerce_product_loop_start(); 
			while ( $products->have_posts() ) : $products->the_post(); 
			if(wpwrps_wc_version()){
					wc_get_template_part( 'content', 'product' ); 
				} else{?>
				<?php woocommerce_get_template_part( 'content', 'product' ); ?>
				<?php }?>
			<?php 
			endwhile; // end of the loop. 
				woocommerce_product_loop_end(); 
			?>
			</div>
			<div class="wpwrps-slider-conf" data-conf="<?php echo htmlspecialchars(json_encode($slider_conf)); ?>"></div>
		</div>
<?php
		endif; 
	}else{
		echo '<div class="wpwrps-product-title">';
			echo '<h2>Not Releted Products Found</h2>';
			echo '</div>';
	}
	wp_reset_postdata();	
	return $output; 
}
