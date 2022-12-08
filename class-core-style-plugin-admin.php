<?php

/**
 * Setup Settings/ Admin of the plugin.
 */
class Core_Style_Plugin_Admin {

	public function add_hooks() {
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Registers the plugin's setting.
	 *
	 * @since 0.0.1
	 */
	public static function register_settings() {
		$args = array(
			'type'              => 'array',
			'sanitize_callback' => array( Core_Style_Plugin::class, 'sanitize_settings' ),
			'default'           => Core_Style_Plugin::settings_default(),
		);
		register_setting(
			CORE_STYLE_PLUGIN_SCREEN,
			CORE_STYLE_PLUGIN_SETTING,
			$args
		);
	}

	/**
	 * Adds the settings page to the Settings menu.
	 *
	 * @since 0.0.1
	 */
	public function add_page() {

		// Register settings and sections
		$this->register_fields();

		// Add the page
		$hook_suffix = add_options_page(
			__( 'Core Style Plugin', 'core-style-plugin' ),
			__( 'Settings', 'core-style-plugin' ),
			'manage_options',
			CORE_STYLE_PLUGIN_SCREEN,
			array( $this, 'render_page' )
		);

		// This adds a link in the plugins list table
		add_action(
			'plugin_action_links_' . plugin_basename( CORE_STYLE_PLUGIN_MAIN_FILE ),
			array(
				$this,
				'plugin_action_links_add_settings',
			)
		);

		return $hook_suffix;
	}

	/**
	 * All settings sections and fields.
	 */
	protected function sections() {
		return array(
			// Section id
			'api' => array(
				// Section title
				'name'     => __( 'API', 'core-style-plugin' ),
				// Settings fields in this section
				'settings' => array(
					// Setting id
					'api_key' => array(
						// Setting label
						'label' => __( 'Api Key', 'core-style-plugin' ),
						// Setting type
						// text|email|url
						'type'  => 'text',
					),
				),
			),

		);
	}

	/**
	 * Initializes settings sections and fields for the settings page.
	 *
	 * @since 0.01
	 */
	public function register_fields() {

		$sections = $this->sections();
		// Get saved settings
		$saved = Core_Style_Plugin::get_settings();
		// Register sections
		foreach ( $sections as $section_slug => $section_data ) {
			add_settings_section(
				$section_slug,
				$section_data['name'],
				null,
				CORE_STYLE_PLUGIN_SCREEN
			);
			// Register fields in this section
			foreach ( $section_data['settings'] as $id => $setting ) {
				$value = isset( $saved[ $id ] ) ? $saved[ $id ] : null;
				add_settings_field(
					$id,
					$setting['label'],
					function()use ( $setting, $id, $value ) {
						$this->render_field(
							$setting['label'],
							$id,
							$value,
							isset( $setting['required'] ) ? $setting['required'] : false,
						);
					},
					CORE_STYLE_PLUGIN_SCREEN,
					$section_slug
				);
			}
		}

	}

	/**
	 * Renders a field.
	 *
	 * @param string      $label
	 * @param string      $name Field identifier, used to make name attr
	 * @param string|null $value Optional. Field value. Default null.
	 * @param bool        $required Optional. Whether the field is required
	 * @param string      $type text|email|url @todo add more types
	 */
	public function render_field( $label, $name, $value = null, $required = false, $type = 'text' ) {
		$name  = CORE_STYLE_PLUGIN_SETTING . '[' . $name . ']';
		$label = sprintf(
			"<label for='%s'>%s</label>",
			esc_attr( $name ),
			esc_html( $label ),
		);
		$field = sprintf(
			'<input type="%s" name="%s" %s />',
			esc_attr( $type ),
			esc_attr( $name ),
			sprintf(
				' %s %s',
				$value ? 'value="' . esc_attr( $value ) . '"' : '',
				$required ? 'required' : ''
			)
		);

		$wrapper = sprintf(
			'<div class="field-wrapper">%s %s</div>',
			$label,
			$field
		);
		echo $wrapper;
	}


	/**
	 * Renders the settings page.
	 *
	 * @since 0.0.1
	 */
	public function render_page() {
		?>
			<div class="core-style-plugin-wrap">
				<h1>
					<?php esc_html_e( 'Core Style Plugin', 'core-style-plugin' ); ?>
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
	 * Adds a link to the setting page to the plugin's entry in the plugins list table.
	 *
	 * @since 1.0.0
	 *
	 * @param array $links List of plugin action links HTML.
	 * @return array Modified list of plugin action links HTML.
	 */
	function plugin_action_links_add_settings( $links ) {
		// Add link as the first plugin action link.
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( add_query_arg( 'page', CORE_STYLE_PLUGIN_SCREEN, admin_url( 'options-general.php' ) ) ),
			esc_html__( 'Settings', 'core-style-plugin' )
		);
		array_unshift( $links, $settings_link );

		return $links;
	}


}
