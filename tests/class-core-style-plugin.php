<?php

/**
 * Test Core_Style_Plugin_Core class
 */
class Test_ClassTwoFactorCore extends WP_UnitTestCase {


	/**
	 * Set up error handling before test suite.
	 *
	 * @see WP_UnitTestCase_Base::set_up_before_class()
	 */
	public static function wpSetUpBeforeClass() {
		set_error_handler( array( 'Test_ClassTwoFactorCore', 'error_handler' ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_set_error_handler
	}

	/**
	 * Clean up error settings after test suite.
	 *
	 * @see WP_UnitTestCase_Base::tear_down_after_class()
	 */
	public static function wpTearDownAfterClass() {
		restore_error_handler();
	}

	/**
	 * Print error messages and return true if error is a notice
	 *
	 * @param integer $errno error number.
	 * @param string  $errstr error message text.
	 *
	 * @return boolean
	 */
	public static function error_handler( $errno, $errstr ) {
		if ( E_USER_NOTICE !== $errno ) {
			echo 'Received a non-notice error: ' . esc_html( $errstr );

			return false;
		}

		return true;
	}

	/**
	 * Verify adding hooks.
	 *
	 * @covers Two_Factor_Core::add_hooks
	 */
	public function test_add_hooks() {
		Core_Style_Plugin::add_hooks();

		$this->assertGreaterThan(
			0,
			has_action(
				'plugins_loaded',
				array( 'Core_Style_Plugin', 'load_textdomain' )
			)
		);

	}

	/**
	 * Verify sanitizing settings.
	 *
	 * @covers Two_Factor_Core::sanitize_settings
	 */
	public function test_sanitize_settings() {
		$prepared = Core_Style_Plugin::sanitize_settings(
			array(
				'api_key' => '123',
				'foo'     => 'bar',
			)
		);
		// Has api_key
		$this->assertArrayHasKey(
			'api_key',
			$prepared
		);
		// Not has foo
		$this->assertArrayNotHasKey(
			'foo',
			$prepared
		);
	}

	/**
	 * Verify default settings are returned by get_settings.
	 *
	 * @covers Two_Factor_Core::settings_default
	 */
	public function test_settings_default() {
		// delete saved
		delete_option( CORE_STYLE_PLUGIN_SETTING );
		$values = Core_Style_Plugin::get_settings();
		$this->assertEquals(
			Core_Style_Plugin::settings_default(),
			$values
		);
	}

	/**
	 * Verify saved settings are returned by get_settings.
	 *
	 * @covers Two_Factor_Core::settings_default
	 */
	public function test_settings_saved() {
		// delete saved
		delete_option( CORE_STYLE_PLUGIN_SETTING );
		// save
		update_option(
			CORE_STYLE_PLUGIN_SETTING,
			array(
				'api_key' => '456',
			)
		);
		$values = Core_Style_Plugin::get_settings();
		$this->assertEquals(
			array(
				'api_key' => '456',
			),
			$values
		);
	}


}
