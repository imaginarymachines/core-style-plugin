<?php

/**
 * Initalize plugin
 */
class Core_Style_Plugin {

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

	/**
	 * Sanitizes the plugin's setting.
	 *
	 * @since 1.0.0
	 *
	 * @param array $values Setting value.
	 * @return array Sanitized modules setting value.
	 */
	public static function sanitize_settings( $values ) {
		if ( ! is_array( $values ) ) {
			$values = array();
		}
		$data     = array();
		$defaults = self::settings_default();
		foreach ( $defaults as $key => $default ) {
			if ( ! array_key_exists( $key, $values ) ) {
				$data[ $key ] = $default;
			} else {
				$data[ $key ] = $values[ $key ];
			}
		}

		return $data;
	}

	/**
	 * Get the default settings for the plugin.
	 *
	 * @return array
	 */
	public static function settings_default() {
		$defaults = array(
			'api_key' => '',
		);

		return $defaults;
	}

	/**
	 * Get the settings for the plugin.
	 *
	 * @return array
	 */
	public static function get_settings() {
		$settings = get_option( CORE_STYLE_PLUGIN_SETTING, self::settings_default() );

		/**
		 * Filter the settings for the plugin.
		 *
		 * @param array $settings Array of settings.
		 */
		return apply_filters( 'core_style_plugin_settings', $settings );
	}

	/**
	 * Save the settings for the plugin.
	 *
	 * Important: does not sanitize data or merge with defaults.
	 *
	 * @param array $update Array of settings to update.
	 * @return bool
	 */
	public static function save_settings( array $update ) {
		/**
		 * Chnage the settings for the plugin before they are saved.
		 *
		 * @param array $update Array of settings to be saved.
		 */
		$update = apply_filters( 'core_style_plugin_pre_save_settings', $update );
		return update_option( CORE_STYLE_PLUGIN_SETTING, $update );
	}

}
