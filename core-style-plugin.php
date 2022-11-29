<?php
/**
 * @wordpress-plugin
 * Plugin Name: Core Style Plugin
 * Plugin URI: https://wordpress.org/plugins/core-style-plugin
 * Description: PLUGIN_DESCRIPTION
 * Author: PLUGIN_AUTHOR_NAME
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

/**
 * Include the core that handles the common bits.
 */
require_once CORE_STYLE_PLUGIN_DIR . 'class-two-factor-core.php';

Two_Factor_Core::add_hooks();
