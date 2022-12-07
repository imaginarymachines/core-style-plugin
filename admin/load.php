<?php
/**
 * Load admin
 * Based on: https://github.com/WordPress/performance/blob/9e11a9194e9fe77a92ecada27cb87ceecd2a9ae7/admin/load.php
 */

/**
 * Adds the settings page to the Settings menu.
 *
 * @since 0.0.1
 */
function imcsp_prefix_add_modules_page() {


	$hook_suffix = add_options_page(
		__( 'Performance Modules', 'performance-lab' ),
		__( 'Performance', 'performance-lab' ),
		'manage_options',
		CORE_STYLE_PLUGIN_SCREEN,
		'imcsp_prefix_render_modules_page'
	);

	// Add the following hooks only if the screen was successfully added.
	if ( false !== $hook_suffix ) {
		add_action( "load-{$hook_suffix}", 'imcsp_prefix_load_modules_page', 10, 0 );
		add_action( 'plugin_action_links_' . plugin_basename( CORE_STYLE_PLUGIN_MAIN_FILE ), 'imcsp_prefix_plugin_action_links_add_settings' );
	}

	return $hook_suffix;
}
add_action( 'admin_menu', 'imcsp_prefix_add_modules_page' );

/**
 * Initializes settings sections and fields for the modules page.
 *
 * @global array $wp_settings_sections Registered WordPress settings sections.
 *
 * @since 1.0.0
 *
 * @param array|null $modules     Associative array of available module data, keyed by module slug. By default, this
 *                                will rely on {@see imcsp_prefix_get_modules()}.
 * @param array|null $focus_areas Associative array of focus area data, keyed by focus area slug. By default, this will
 *                                rely on {@see imcsp_prefix_get_focus_areas()}.
 */
function imcsp_prefix_load_modules_page( $modules = null, $focus_areas = null ) {
	global $wp_settings_sections;

	// Register sections for all focus areas, plus 'Other'.
	if ( ! is_array( $focus_areas ) ) {
		$focus_areas = imcsp_prefix_get_focus_areas();
	}
	$sections          = $focus_areas;
	$sections['other'] = array( 'name' => __( 'Other', 'performance-lab' ) );
	foreach ( $sections as $section_slug => $section_data ) {
		add_settings_section(
			$section_slug,
			$section_data['name'],
			null,
			CORE_STYLE_PLUGIN_SCREEN
		);
	}


	$settings = Core_Style_Plugin::get_settings();
	foreach ( $modules as $module_slug => $module_data ) {
		$module_settings = isset( $settings[ $module_slug ] ) ? $settings[ $module_slug ] : array();
		$module_section  = isset( $sections[ $module_data['focus'] ] ) ? $module_data['focus'] : 'other';

		// Mark this module's section as added.
		$sections[ $module_section ]['added'] = true;

		add_settings_field(
			$module_slug,
			$module_data['name'],
			function() use ( $module_slug, $module_data, $module_settings ) {
				imcsp_prefix_render_modules_page_field( $module_slug, $module_data, $module_settings );
			},
			CORE_STYLE_PLUGIN_SCREEN,
			$module_section
		);
	}

	// Remove all sections for which there are no modules.
	foreach ( $sections as $section_slug => $section_data ) {
		if ( empty( $section_data['added'] ) ) {
			unset( $wp_settings_sections[ CORE_STYLE_PLUGIN_SCREEN ][ $section_slug ] );
		}
	}
}

/**
 * Renders the modules page.
 *
 * @since 1.0.0
 */
function imcsp_prefix_render_modules_page() {
	?>
	<div class="wrap">
		<h1>
			<?php esc_html_e( 'Performance Modules', 'performance-lab' ); ?>
		</h1>

		<form action="options.php" method="post" novalidate="novalidate">
			<?php settings_fields( CORE_STYLE_PLUGIN_SCREEN ); ?>
			<?php do_settings_sections( CORE_STYLE_PLUGIN_SCREEN ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Renders fields for a given module on the modules page.
 *
 * @since 1.0.0
 *
 * @param string $module_slug     Slug of the module.
 * @param array  $module_data     Associative array of the module's parsed data.
 * @param array  $module_settings Associative array of the module's current settings.
 */
function imcsp_prefix_render_modules_page_field( $module_slug, $module_data, $module_settings ) {
	$base_id   = sprintf( 'module_%s', $module_slug );
	$base_name = sprintf( '%1$s[%2$s]', CORE_STYLE_PLUGIN_MAIN_FILE, $module_slug );
	$enabled   = isset( $module_settings['enabled'] ) && $module_settings['enabled'];
	?>
	<fieldset>
		<legend class="screen-reader-text">
			<?php echo esc_html( $module_data['name'] ); ?>
		</legend>
		<label for="<?php echo esc_attr( "{$base_id}_enabled" ); ?>">
			<?php if ( imcsp_prefix_can_load_module( $module_slug ) ) { ?>
				<input type="checkbox" id="<?php echo esc_attr( "{$base_id}_enabled" ); ?>" name="<?php echo esc_attr( "{$base_name}[enabled]" ); ?>" aria-describedby="<?php echo esc_attr( "{$base_id}_description" ); ?>" value="1"<?php checked( $enabled ); ?>>
				<?php
				if ( $module_data['experimental'] ) {
					printf(
						/* translators: %s: module name */
						__( 'Enable %s <strong>(experimental)</strong>', 'performance-lab' ),
						esc_html( $module_data['name'] )
					);
				} else {
					printf(
						/* translators: %s: module name */
						__( 'Enable %s', 'performance-lab' ),
						esc_html( $module_data['name'] )
					);
				}
				?>
			<?php } else { ?>
				<input type="checkbox" id="<?php echo esc_attr( "{$base_id}_enabled" ); ?>" aria-describedby="<?php echo esc_attr( "{$base_id}_description" ); ?>" disabled>
				<input type="hidden" name="<?php echo esc_attr( "{$base_name}[enabled]" ); ?>" value="<?php echo $enabled ? '1' : '0'; ?>">
				<?php
					printf(
						/* translators: %s: module name */
						__( '%s is already part of your WordPress version and therefore cannot be loaded as part of the plugin.', 'performance-lab' ),
						esc_html( $module_data['name'] )
					);
				?>
			<?php } ?>
		</label>
		<p id="<?php echo esc_attr( "{$base_id}_description" ); ?>" class="description">
			<?php echo esc_html( $module_data['description'] ); ?>
		</p>
	</fieldset>
	<?php
}

/**
 * Gets all available focus areas.
 *
 * @since 1.0.0
 *
 * @return array Associative array of focus area data, keyed by focus area slug. Fields for every focus area include
 *               'name'.
 */
function imcsp_prefix_get_focus_areas() {
	return array(
		'images'       => array(
			'name' => __( 'Images', 'performance-lab' ),
		),
		'js-and-css'   => array(
			'name' => __( 'JS & CSS', 'performance-lab' ),
		),
		'database'     => array(
			'name' => __( 'Database', 'performance-lab' ),
		),
		'measurement'  => array(
			'name' => __( 'Measurement', 'performance-lab' ),
		),
		'object-cache' => array(
			'name' => __( 'Object Cache', 'performance-lab' ),
		),
	);
}


/**
 * Adds a link to the modules page to the plugin's entry in the plugins list table.
 *
 * This function is only used if the modules page exists and is accessible.
 *
 * @since 1.0.0
 * @see imcsp_prefix_add_modules_page()
 *
 * @param array $links List of plugin action links HTML.
 * @return array Modified list of plugin action links HTML.
 */
function imcsp_prefix_plugin_action_links_add_settings( $links ) {
	// Add link as the first plugin action link.
	$settings_link = sprintf(
		'<a href="%s">%s</a>',
		esc_url( add_query_arg( 'page', CORE_STYLE_PLUGIN_SCREEN, admin_url( 'options-general.php' ) ) ),
		esc_html__( 'Settings', 'performance-lab' )
	);
	array_unshift( $links, $settings_link );

	return $links;
}
