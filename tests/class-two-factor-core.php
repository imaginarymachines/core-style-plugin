<?php
/**
 * Two Factor Core Class Tests.
 *
 * @package Two_Factor
 */

/**
 * Class Test_ClassTwoFactorCore
 *
 * @package Two_Factor
 * @group core
 */
class Test_ClassTwoFactorCore extends WP_UnitTestCase {

	/**
	 * Original User ID set in set_up().
	 *
	 * @var int
	 */
	private $old_user_id;

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
		Two_Factor_Core::add_hooks();

		$this->assertGreaterThan(
			0,
			has_action(
				'plugins_loaded',
				array( 'Two_Factor_Core', 'load_textdomain' )
			)
		);

	}


}
