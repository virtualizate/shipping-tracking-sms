<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://virtualizate.com.co
 * @since             1.0.0
 * @package           STS
 *
 * @wordpress-plugin
 * Plugin Name:       Shipping Tracking SMS for WooCoomerce
 * Plugin URI:        https://virtualizate.com.co/plugins/shipping-tracking-sms/
 * Description:       Administración y notificación por SMS de envíos de productos a través de empresas de envíos colombianas.
 * Version:           1.0.0
 * Author:            Sergio Rondón | Grupo Virtualizate
 * Author URI:        http://virtualizate.com.co/
 * Text Domain:       shipping-tracking-sms
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/virtualizate/shipping-tracking-sms
 * 
 * Copyright: 		  	  ©2020 Grupo Virtualizate
 * License:           	  GPL-2.0+
 * License URI:       	  http://www.gnu.org/licenses/gpl-2.0.txt
 * WC requires at least:  2.6
 * WC tested up to: 	  4.2
 * Tested up to: 		  5.3
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
if(!defined( 'STS_VERSION')){
	define( 'STS_VERSION', '1.0.0' );
}

if(!defined( 'STS_SLUG')) {
	define( 'STS_SLUG', 'shipping-tracking-sms' );
}

if (!defined( 'STS_PREFIX')) {
	define( 'STS_PREFIX', 'sts' );
}

if (!defined( 'STS_CAPABILITY')) {
	define( 'STS_CAPABILITY', 'install_plugins' );
}

if (!defined( 'STS_URL')) {
	define( 'STS_URL', plugin_dir_url(__FILE__));
}

if (!defined('STS_PATH')) {
	define( 'STS_PATH', plugin_dir_path(__FILE__) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sts-activator.php
 */
function activate_sts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sts-activator.php';
	STS_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sts-deactivator.php
 */
function deactivate_sts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sts-deactivator.php';
	STS_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sts' );
register_deactivation_hook( __FILE__, 'deactivate_sts' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sts() {

	$plugin = new STS();
	$plugin->run();

}
run_sts();
