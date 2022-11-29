<?php


/**
 * Test plugin is
 */
class Tests_Plugin_Setup extends WP_UnitTestCase {

	/**
	 * Check that the TWO_FACTOR_DIR constant is defined.
	 */
	public function test_constant_defined() {

		$this->assertTrue( defined( 'CORE_STYLE_PLUGIN_DIR' ) );

	}

	/**
	 * Check that the files were included.
	 */
	public function test_classes_exist() {

		$this->assertTrue( class_exists( 'Core_Style_Plugin' ) );

	}
}
