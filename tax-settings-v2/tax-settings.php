<?php
/**
 * Plugin Name: Taxonomy Settings
 * Description: An example plugin to create taxonomy specific settings
 * Version: 2.0.0
 * Author: Hans-Helge Buerger
 * Author URI: http://hanshelgebuerger.de
 * GitHub Plugin URI: https://github.com/obstschale/tax-settings
 *
 * Copyright 2014 Hans-Helge Buerger (http://hanshelgebuerger.de)
 * License: MIT
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tax-settings.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tax_settings() {
	$plugin = new Tax_Settings();
}
run_tax_settings();
