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

/**
 * Include the core that handles the common bits.
 */
require_once CORE_STYLE_PLUGIN_DIR . 'class-core-style-plugin.php';

Core_Style_Plugin::add_hooks();
