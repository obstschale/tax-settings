<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://github.com/obstschale/tax-settings
 * @since      1.0.0
 *
 * @package    Tax-Settings
 * @subpackage Tax-Settings/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Tax-Settings
 * @subpackage Tax-Settings/includes
 * @author     Hans-Hege Buerger
 */
class Tax_Settings {
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Load the dependencies and set the hooks for the Settings page and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - TS_Settings. Creates new Settings page
	 * - TS_Category_Message. Defines all hooks for the public side of the site.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ts-category-message.php';

		/**
		 * The class responsible for defining all actions that occur in the backend.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ts-settings.php';

	}

	/**
	 * Register all of the hooks related to the settings functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$settings = new TS_Settings();
		add_action( 'admin_init', array( $settings, 'admin_init' ) );
		add_action( 'admin_menu', array( $settings, 'add_menu' ) );
	}
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$cat_message = new TS_Category_Message();
		add_action( 'wp_enqueue_scripts', array( $cat_message, 'enqueue_styles' ) );
		add_filter( 'the_content', array( $cat_message, 'show_message' ), 10, 1 );
	}

}
