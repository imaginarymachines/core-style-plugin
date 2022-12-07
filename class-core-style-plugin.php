<?php

/**
 * Initalize plugin
 */
class Core_Style_Plugin {

	const OPTION_NAME = 'core_style_plugin_settings';
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

	public static function settings_default() {
		$defaults = [
			'api_key' => '',
		];

		return $defaults;
	}

	/**
	 * Get the settings for the plugin.
	 *
	 * @return array
	 */
	public static function get_settings() {
		$settings = get_option( self::OPTION_NAME, self::settings_default() );

		return $settings;
	}

	/**
	 * Save the settings for the plugin.
	 *
	 * @param array $update Array of settings to update.
	 */
	public static function save_settings( array $update ) {
		$data = [];
		$defaults = self::settings_default();
		foreach ($update as $key => $value) {
			if( ! array_key_exists( $key, $defaults ) ) {
				continue;
			}
			$data[$key] = $value;
		}
		if ( update_option( self::OPTION_NAME, $data )){
			return $data;

		}
		return false;
	}

}
