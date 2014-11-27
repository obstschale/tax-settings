<?php

/**
 * The file that defines the settings page for the plugin
 *
 * A class that creates a new settings page and settings for the plugin and links.
 *
 * @since      1.0.0
 * @package    Tax-Settings
 * @subpackage Tax-Settings/admin
 * @author     Hans-Hege Buerger
 */
class TS_Settings {

	/**
	 * Method which calls responsible methods to setup settings
	 *
	 * @since 1.0.0
	 */
	public function admin_init() {
		$this->register_settings();
		$this->add_sections();
		$this->add_fields();
	}

	/**
	 * This method registers one option for this plugin: 'ts_options'.
	 * The method 'validate_options' is set for validation.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function register_settings() {
		// register settings for new show
		register_setting(
			'ts_options',
			'ts_options',
			array( $this, 'validate_options' )
		);
	}

	/**
	 * This method adds a new section 'ts_settings_sec'.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function add_sections() {
		add_settings_section(
			'ts_settings_sec', // ID
			'Category Settings', // Name
			array( $this, 'category_settings_description' ), // Callback for description
			'ts_set' // Page
		);
	}

	/**
	 * This method add one new settings field to the section 'ts_settings_sec'
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function add_fields() {
		add_settings_field(
			'ts_category_message', // ID
			'Message', // Title
			array( $this, 'category_message' ), // Callback
			'ts_set', // Page
			'ts_settings_sec' // Settings ID
		);
	}

	/**
	 * Callback function which displays a description for
	 * section 'ts_settings_sec'
	 *
	 * @since  1.0.0
	 */
	public function category_settings_description() { ?>
		<p>These setting is global and is used by all categories if no local settings are defined.</p>
	<?php }

	/**
	 * Callback function to display the input field for
	 * the predefined settings field
	 *
	 * @since  1.0.0
	 */
	public function category_message() {
		$options = get_option( 'ts_options' ); ?>
		<input id="ts_message"
			name="ts_options[ts_category_message]"
			size="40"
			type="text"
			value="<?php echo esc_attr($options['ts_category_message']) ?>" />
	<?php }

	/**
	 * Validate function to make sure only sanitized settings are saved in DB
	 *
	 * @since  1.0.0
	 *
	 * @param  array $input settings data / user input
	 * @return array        sanitized settings
	 */
	public function validate_options( $input ) {
		$valid = array();

		$valid['ts_category_message'] = sanitize_text_field( $input['ts_category_message'] );

		return $valid;
	}

	/**
	 * Method to add a new settings page as subpage
	 *
	 * @since  1.0.0
	 */
	public function add_menu() {
		add_options_page(
			'Taxonomy Settings',
			'Taxonomy Settings',
			'manage_options', // capabilities
			'ts_settings', // slug
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Callback function to render the actual settings page.
	 *
	 * @since  1.0.0
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'You do not have sufficient permissions to access this page.' );
		} ?>

		<h2>Taxonomy Settings</h2>
		<div class="wrap">
		<div id="icon-tools" class="icon32"></div>
		<form method="POST" action="options.php">
			<?php settings_fields( 'ts_options' ); ?>
			<?php do_settings_sections( 'ts_set' ); ?>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php }
}
