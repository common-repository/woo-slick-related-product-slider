<?php
/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Slick Related Product Slider For Woocommerce
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Wpwrps_Script {
	
	function __construct() {
		
		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wpwrps_style_css') );
		
		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wpwrps_script_js') );
	}
	/**
	 * Function to add style at front side
	 * 
	 * @package Slick Related Product Slider For Woocommerce
	 * @since 1.0
	 */
	function wpwrps_style_css() {
		// Slick CSS
		wp_register_style( 'wpwrps_style', WPWRPS_VERSION_URL.'assets/css/wpwrps-public.css', array(), WPWRPS_VERSION );
		wp_enqueue_style('wpwrps_style');
	
		if( !wp_style_is( 'wpos-slick-style', 'registered' ) ) {
			wp_register_style( 'wpos-slick-style', WPWRPS_VERSION_URL.'assets/css/slick.css', array(), WPWRPS_VERSION );
			wp_enqueue_style('wpos-slick-style');
		}
	}
	
	/**
	 * Function to add script at front side
	 * 
	 * @package Slick Related Product Slider For Woocommerce
	 * @since 1.0
	 */
	function wpwrps_script_js() {

		// Registring slick slider script
		if( !wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-slick-jquery', WPWRPS_VERSION_URL.'assets/js/wpwrps-slick.min.js', array('jquery'), WPWRPS_VERSION, true );
		}
		// Public script
		wp_register_script( 'wpwrps-public-jquery', WPWRPS_VERSION_URL.'assets/js/wpwrps-public.js', array('jquery'), WPWRPS_VERSION, true );
		wp_localize_script( 'wpwrps-public-jquery', 'Wpwrps', array(
															'is_mobile' 		=>	(wp_is_mobile()) ? 1 : 0,
															'is_rtl' 			=>	(is_rtl()) ? 1 : 0,
														));
	}
}

$wpwrps_script = new Wpwrps_Script();