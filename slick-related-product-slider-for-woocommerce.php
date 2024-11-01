<?php
/**
 * Plugin Name: Slick Related Product Slider For Woocommerce
 * Plugin URI: http://www.wponlinesupport.com/plugins
 * Description: Display Related Product by category/Linked Products for Woocommerce, Up-sell And Cross-sell Product Slider,Category Releted Product Slider. Widget Releted Product Slider.
 * Author: WP OnlineSupport 
 * Text Domain: slick-related-product-slider-for-woocommerce
 * Domain Path: /languages/
 * WC tested up to: 3.5.2
 * Version: 1.0.1
 * Author URI: http://www.wponlinesupport.com/
 *
 * @package WordPress
 * @author WP OnlineSupport
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if( !defined( 'WPWRPS_VERSION' ) ) {
	define( 'WPWRPS_VERSION', '1.0.1' ); // Version of plugin
}
if( !defined( 'WPWRPS_VERSION_DIR' ) ) {
    define( 'WPWRPS_VERSION_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'WPWRPS_VERSION_URL' ) ) {
    define( 'WPWRPS_VERSION_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( !defined( 'WPWRPS_POST_TYPE' ) ) {
    define( 'WPWRPS_POST_TYPE', 'product' ); // Plugin post type
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Slick Related Product Slider For Woocommerce
 * @since 1.0
 */
function wpwrps_load_textdomain() {
	// Check main plugin is active or not
	if( class_exists('WooCommerce') ) {
		load_plugin_textdomain( 'slick-related-product-slider-for-woocommerce', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
	}
}
// Action to load plugin text domain
add_action('plugins_loaded', 'wpwrps_load_textdomain');

/**
 * Check WooCommerce plugin is active
 *
 * @package Slick Related Product Slider For Woocommerce
 * @since 1.0
 */
function wpwrps_check_activation() {

	if ( !class_exists('WooCommerce') ) {
		// is this plugin active?
		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			// deactivate the plugin
	 		deactivate_plugins( plugin_basename( __FILE__ ) );
	 		// unset activation notice
	 		unset( $_GET[ 'activate' ] );
	 		// display notice
	 		add_action( 'admin_notices', 'wpwrps_admin_notices' );
		}
	}
}

// Check required plugin is activated or not
add_action( 'admin_init', 'wpwrps_check_activation' );

/**
 * Admin notices
 * 
 * @package Slick Related Product Slider For Woocommerce
 * @since 1.0
 */
function wpwrps_admin_notices() {
	
	if ( !class_exists('WooCommerce') ) {
		echo '<div class="error notice is-dismissible">';
		echo sprintf( __('<p><strong>%s</strong> recommends the following plugin to use.</p>', 'slick-related-product-slider-for-woocommerce'), 'Slick Related Product Slider For Woocommerce' );
		echo sprintf( __('<p><strong><a href="%s" target="_blank">%s</a> </strong></p>', 'slick-related-product-slider-for-woocommerce'), 'https://wordpress.org/plugins/woocommerce/', 'WooCommerce' );
		echo '</div>';
	}
}

// Including Script files
require_once( WPWRPS_VERSION_DIR . '/includes/class-wpwrps-script.php' );
// Including function files
require_once(WPWRPS_VERSION_DIR . '/includes/wpwrps-functions.php' );
// Including widgets  files
require_once(WPWRPS_VERSION_DIR . '/includes/widget/wpwrps-slider-widgets.php' );


