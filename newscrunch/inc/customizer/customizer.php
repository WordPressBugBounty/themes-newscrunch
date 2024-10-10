<?php 
/**
 * Newscrunch Customizer Controls
 *
 * @package Newscrunch
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Newscrunch_Customizer' ) ) :

	/**
	 * The Newscrunch Customizer class
	*/
	class Newscrunch_Customizer {

		/**
		 * Setup class
		*/
		public function __construct() {

			add_action( 'customize_register', 					array( $this, 'custom_controls' ) );
			add_action( 'customize_register', 					array( $this, 'controls_helpers' ) );
			add_action( 'after_setup_theme',  					array( $this, 'register_options' ) );
			add_action( 'customize_controls_enqueue_scripts', 	array( $this, 'custom_customize_enqueue' ) );

		}


		/**
		 * Adds custom controls
		*/
		public function custom_controls( $wp_customize ) {

			// Load customize control classes
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/custom-controls/customizer-text-radio/text-radio-control.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/custom-controls/toggle/class-toggle-control.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/custom-controls/customizer-tabs/class/class-newscrunch-customize-control-tabs.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/custom-controls/customizer-repeater/class/customizer-repeater-control.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/custom-controls/customizer-slider/customizer-slider.php' );	
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/custom-controls/customizer-image-radio/customizer-image-radio.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/custom-controls/customizer-alpha-color-picker/class-customize-alpha-color-control.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/custom-controls/dropdown-posts/dropdown-posts-control.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/custom-controls/multiple-category-dropdown/multiple-category-dropdown-control.php' );

			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/custom-controls/sortable/class-sortable-control.php' );

			// Register custom controls
			$wp_customize->register_control_type('Newscrunch_Toggle_Control');
			$wp_customize->register_control_type( 'Newscrunch_Control_Sortable' );
		}


		/**
		 * Adds customizer helpers
		*/
		public function controls_helpers() {
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/sanitize-callback.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/active-callback.php' );
		}


		/**
		 * Adds customizer options
		*/
		public function register_options() {

			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/general-settings.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/advertisement.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/top-header.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/repeater-default-value.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/site-identity.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/theme-header.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/theme-footer.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/bottom-footer.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/main-banner.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/news-highlight.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/left-content-right-sidebar.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/left-sidebar-right-content.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/missed-section.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/scroll-to-top.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/archives-options.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/single-post-options.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/featured-video.php' );
			require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/settings/reorder-sections.php' );
		}


		/**
		 * Load scripts for customizer
		*/
		public function custom_customize_enqueue() {
			/* Enqueue the CSS files */
			wp_enqueue_style( 'newscrunch-customize-css', NEWSCRUNCH_TEMPLATE_DIR_URI .'/inc/customizer/assets/css/customize.css' );
			require_once('custom_style.php');
		}

	}

endif;

new newscrunch_Customizer();