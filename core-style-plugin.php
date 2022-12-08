<?php
/**
 * @wordpress-plugin
 * Plugin Name: Core Style Plugin
 * Plugin URI: https://pluginmachine.com/plugin/page
 * Description: words-describes-plugin
 * Author: Josh Pollock
 * Version: 0.1.0
 * Author URI: https://pluginmachine.com
 * Network: True
 * Text Domain: core-style-plugin
 */

/**
 * Shortcut constant to the path of this file.
 */
define( 'CORE_STYLE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Version of the plugin.
 */
define( 'CORE_STYLE_PLUGIN_VERSION', '0.1.0' );

// PERFLAB_MAIN_FILE
define( 'CORE_STYLE_PLUGIN_MAIN_FILE', __FILE__ );
// PERFLAB_MODULES_SETTING
define( 'CORE_STYLE_PLUGIN_SETTING', 'core_style_plugin_settings' );
// PERFLAB_MODULES_SCREEN
define( 'CORE_STYLE_PLUGIN_SCREEN', 'core-style-plugin' );


/**
 * Include the core that handles the common bits.
 */
require_once CORE_STYLE_PLUGIN_DIR . 'class-core-style-plugin.php';

/**
 * Load the plugin.
 */
Core_Style_Plugin::add_hooks();

/**
 * Load the admin if we are in the admin.
 */
if ( is_admin() ) {
	include CORE_STYLE_PLUGIN_DIR . 'class-core-style-plugin-admin.php';
	$admin = new Core_Style_Plugin_Admin();
	$admin->add_hooks();
}
