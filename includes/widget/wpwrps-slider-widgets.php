<?php
/**
 * Widget API: Widget Class
 *
 * @package Slick Related Product Slider For Woocommerce
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function wpwrpc_product_slider_widget() {
    register_widget( 'wpwrps_Code_Widget' );
}
// Action to register widget
add_action( 'widgets_init', 'wpwrpc_product_slider_widget' );

class wpwrps_Code_Widget extends WP_Widget {

    /**
     * Sets up a new widget instance.
     *
     * @package Slick Related Product Slider For Woocommerce
     * @since 1.0
     */
    function __construct() {
         
        $widget_ops = array('classname' => 'wpwrps-product-slider-generator', 'description' => __('Display wpwrps product slider in Widget.', 'slick-related-product-slider-for-woocommerce') );
        parent::__construct( 'wpwrps_Code', __('Slick Related Product Slider For Woocommerce', 'slick-related-product-slider-for-woocommerce'), $widget_ops );
    }

 
    /**
     * Handles updating settings for the current widget instance.
     *
     * @package Slick Related Product Slider For Woocommerce
     * @since 1.0
     */
    function update($new_instance, $old_instance) {
        $instance                   = $old_instance;
        $instance['title']          = $new_instance['title'];
        
        return $instance;
    }

     /**
     * Outputs the settings form for the widget.
     *
     * @package Slick Related Product Slider For Woocommerce
     * @since 1.0
     */
    function form($instance) {
 
        $defaults  = array(
                'title'             => 'Related Product Slider',
        );
        $instance       = wp_parse_args( (array) $instance, $defaults );
        $title          = isset($instance['title']) ? esc_attr($instance['title']) : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"> 
                <?php esc_html_e( 'Title:', 'widgets-user-plugins' ); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>
        <?php
        }

    /**
    * Outputs the content for the current widget instance.
    *
    * @package Slick Related Product Slider For Woocommerce
    * @since 1.0
    */
    function widget( $blog_args, $instance ) {
        extract($blog_args, EXTR_SKIP);
        $title           = empty($instance['title']) ? 'Related Product Slider' : apply_filters('widget_title', $instance['title']);
        $cats            = empty($instance['cats']) ? '' : apply_filters('widget_cats', $instance['cats'] );
        $tax             = empty($instance['tax']) ? 'product_cat' : apply_filters('widget_tax', $instance['tax'] );
        $limit           = empty($instance['limit']) ? '5' : apply_filters('widget_limit', $instance['limit'] );
        $slide_to_show   = empty($instance['slide_to_show']) ? '1' : apply_filters('widget_slide_to_show', $instance['slide_to_show'] );
        $slide_to_scroll = empty($instance['slide_to_scroll']) ? '1' : apply_filters('widget_slide_to_scroll', $instance['slide_to_scroll'] );
        $autoplay        = empty($instance['autoplay']) ? 'true' : apply_filters('widget_autoplay', $instance['autoplay'] );
        $autoplay_speed  = empty($instance['autoplay_speed']) ? '3000' : apply_filters('widget_autoplay_speed', $instance['autoplay_speed'] );
        $speed           = empty($instance['speed']) ? '300' : apply_filters('widget_speed', $instance['speed'] );
        $arrows          = empty($instance['arrows']) ? 'true' : apply_filters('widget_arrows', $instance['arrows'] );
        $dots            = empty($instance['dots']) ? 'true' : apply_filters('widget_dots', $instance['dots'] );
        $rtl             = empty($instance['rtl']) ? '' : apply_filters('widget_rtl', $instance['rtl'] );
        $slider_cls      = empty($instance['slider_cls']) ? 'products' : apply_filters('widget_slider_cls', $instance['slider_cls'] );
       
        echo $before_widget;

        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
        // unique id in slider
        $unique = wpwrps_get_unique();
        
        //Slider Class Add Ul
        $cat        = (!empty($cats)) ? explode(',',$cats)  : '';
        
        // Slider configuration
        $slider_conf = compact('slide_to_show', 'slide_to_scroll', 'autoplay', 'autoplay_speed', 'speed', 'arrows','dots', 'rtl', 'slider_cls'); 
        
        //Linkled Products 
        $crosssell_ids = get_post_meta( get_the_ID(), '_crosssell_ids' ); 
        if(!empty($crosssell_ids)){
            $crosssell_ids = $crosssell_ids[0];
        }
        $upsells = get_post_meta( get_the_ID(), '_upsell_ids' ); 
        if(!empty($upsells)){
            $upsells=$upsells[0];
        }
        $link_product = array_merge($crosssell_ids,$upsells);
    
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

        // Taking some globals
        global $post;
        global $wp_version;
        global $woocommerce_loop, $product;
        
        // setup query
        $args = array(
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page'        => $limit,
            'order'                 => 'DESC',
            'post__in'              => $link_product,
        );
        
        // Category Tax Query Parameter
        if(!empty($woo_cat_id) && empty($link_product)) {           
            $args['tax_query'] = array(
                                    array( 
                                        'taxonomy'  => 'product_cat',
                                        'field'     => 'id',
                                        'terms'     => $woo_cat_id, 
                                    ));
        }
        
        // query database
        $products = new WP_Query( $args );      
        if(!empty($link_product) || !empty($woo_cat_id)){
        if ( $products->have_posts() ) : ?>
        <div class="wpwrps-product-slider-widget-wrap">
            <h2>Releted Product</h2>
            <div class="woocommerce wpwrps-product-slider-widget" id="wpwrps-product-slider-widget-<?php echo $unique; ?>">
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
            <div class="wpwrps-slider-conf-widget" data-conf="<?php echo htmlspecialchars(json_encode($slider_conf)); ?>"></div>
        </div>
        <?php 
        endif;
        }
        else { ?>
            <div class="wpwrps-product-title">
                <h2>Not Releted Products Found</h2>
            </div>
        <?php } 
        echo $after_widget;
        wp_reset_query(); // Reset WP Query 
    }
}
