<?php
/**
 * Two Factore Core Class.
 *
 * @package Two_Factor
 */

/**
 * Class for creating two factor authorization.
 *
 * @since 0.1-dev
 *
 * @package Two_Factor
 */
class Two_Factor_Core {


	/**
	 * Set up filters and actions.
	 *
	 * @since 0.1-dev
	 */
	public static function add_hooks() {
		add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );
	}

	/**
	 * Loads the plugin's text domain.
	 *
	 * Sites on WordPress 4.6+ benefit from just-in-time loading of translations.
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( 'core-style-plugin' );
	}

}
