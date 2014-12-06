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
	 * Method which calls responsible methods to setup settings. 1 general setting and one for each category.
	 *
	 * @since 1.0.0
	 */
	public function admin_init() {

		$categories = $this->get_categories();

		$this->register_settings( 'general', 'General' );
		foreach ( $categories as $key => $category ) {
			$this->register_settings( $category->slug, $category->name );
		}
	}

	/**
	 * This method returns the proper option name
	 *
	 * This is done to guarantee that each option name follows the same
	 * convention and other classes / functions can use this method to
	 * get the name without knowing what the acutal convention is.
	 *
	 * @param  string $slug category slug
	 * @return string       correct option name
	 */
	public function get_option_name( $slug ) {
		return 'ts_' . $slug . '_options';
	}

	/**
	 * Method to get the proper section name
	 *
	 * @param  string $slug category slug
	 * @return string       correct section name
	 */
	public function get_sec_name( $slug ) {
		return 'ts_' . $slug . '_sec';
	}

	/**
	 * Method to get the proper page name
	 *
	 * @param  string $slug category slug
	 * @return string       correct page name
	 */
	public function get_page_name( $slug ) {
		return 'ts_' . $slug . '_page';
	}

	/**
	 * This method returns an array with the corresponding setting name,
	 * field name, and of course the value.
	 *
	 * If the value is not set in this option array an empty string is returned.
	 * This method is primarily used in setting forms to display the value
	 * (and to set a placeholder value).
	 *
	 * @param  string $setting_name option namen where to look at
	 * @param  string $value_name   actual value name in the option
	 * @return array                [setting_name, field_name, field_value]
	 */
	public function get_field_value( $setting_name, $value_name ) {

		$opt_name = $this->get_option_name( $setting_name );

		$options = get_option( $opt_name );

		$value = ( isset( $options[$value_name] ) ) ? $options[$value_name] : '' ;

		return array(
			'setting_name' => $opt_name,
			'field_name'   => $value_name,
			'field_value'  => $value
		);
	}

	/**
	 * This method returns the value for a field
	 *
	 * In comparision to the get_field_value method will return either the
	 * value in the named option array or if it is not set it try to get the
	 * value in the general option array as fallback.
	 * This method is used in the Frontend if a value is needed.
	 *
	 * @see    TS_Settings::get_field_value
	 *
	 * @param  string $setting_name option namen where to look at
	 * @param  string $value_name   actual value name in the option
	 * @return string               value or fallback
	 */
	public function get_option_by_key( $setting_name, $value_name ) {
		if ( !isset( $setting_name ) || !isset( $value_name ) ) {
			return '';
		}

		$value = $this->get_field_value( $setting_name, $value_name );

		if ( !isset( $value ) || empty( $value['field_value'] ) ) {
			$value = $this->get_field_value( 'general', $value_name );
		}

		return $value;
	}

	/**
	 * This method registers a new option for the given $slug
	 *
	 * @since  1.0.0
	 *
	 * @param  string $slug category slug
	 * @param  string $name category name
	 */
	public function register_settings( $slug, $name ) {
		$option = $this->get_option_name( $slug );

		// register settings for new show
		register_setting(
			$option,
			$option,
			array( $this, 'validate_options' )
		);

		$this->add_sections( $slug, $name );
	}

	/**
	 * Adds a new section for a given category
	 *
	 * @since  1.0.0
	 *
	 * @param string $slug category slug
	 * @param string $name category name
	 */
	public function add_sections( $slug, $name ) {
		$sec = $this->get_sec_name( $slug );
		$page = $this->get_page_name( $slug );

		add_settings_section(
			$sec, // ID
			"Category '{$name}' Settings", // Name
			array( $this, 'category_settings_description' ), // Callback for description
			$page // Page
		);

		$this->add_fields( $slug ,$sec, $page );
	}

	/**
	 * Adds new settings field to a specific section and page
	 *
	 * The slug is passed as callback arg to the callback function.
	 *
	 * @since  1.0.0
	 *
	 * @param string $slug category slug
	 * @param string $sec  section name
	 * @param string $page page name
	 */
	public function add_fields( $slug, $sec, $page ) {
		add_settings_field(
			'ts_category_message', // ID
			'Message', // Title
			array( $this, 'category_message' ), // Callback
			$page, // Page
			$sec, // Settings ID
			array( 'slug' => $slug ) // Callback args
		);
	}

	/**
	 * Callback function which displays a description for a new setting
	 *
	 * This sample description is used by all settings. Using callback arguments
	 * the description could be alterd to math a specific category
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
	 * Using callback arguments the same function can be used for all options.
	 * Using the $slug in the $args array the input field can be linked to the
	 * correct options.
	 *
	 * @since  1.0.0
	 *
	 * @param  array $args Callback arguments
	 */
	public function category_message( $args ) {
		$value = $this->get_field_value( $args['slug'], "ts_cat_message" );
		$placeholder = $this->get_field_value( 'general', "ts_cat_message" );
		$options_name = $this->get_option_name( $args['slug'] );

		?>
		<input id="ts_message"
			name="<?php echo $options_name; ?>[ts_cat_message]"
			size="40"
			type="text"
			value="<?php echo esc_attr( $value['field_value'] ); ?>"
			placeholder="<?php echo esc_attr( $placeholder['field_value'] ); ?>" />
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

		$valid["ts_cat_message"] = sanitize_text_field( $input["ts_cat_message"] );

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
		} else if( ! isset( $_GET['page'] ) || esc_attr( $_GET['page'] ) != 'ts_settings' ) {
			return;
		}

		// Get all categories which registred are at the time
		$categories = $this->get_categories();

		// Add links to category specific setting pages ?>
		<div id="ts_subheader" class="wrap">
		<?php $all_cat = 'General';
		echo "<a href='?page=ts_settings&cat=general'>{$all_cat}</a>";
		foreach ($categories as $key => $cat) : ?>

		 | <a href='?page=ts_settings&cat=<?php echo $cat->slug; ?>'><?php echo esc_attr( $cat->name ); ?></a>

		<?php endforeach; ?>

		<?php // Render actual settings page depending ?>
		<h2>Taxonomy Settings</h2>
		<div class="wrap">
		<div id="icon-tools" class="icon32"></div>
		<form method="POST" action="options.php">
			<?php
			if ( ! isset( $_GET['cat'] ) ) {
				$current_cat = 'general';
			} else {
				$current_cat = esc_attr( $_GET['cat'] );
			}

			$option_name = $this->get_option_name( $current_cat );
			$page_name   = $this->get_page_name( $current_cat );

			settings_fields( $option_name );
			do_settings_sections( $page_name );
			submit_button(); ?>
		</form>
	</div>
	<?php }

	/**
	 * Simple method to retrieve all categories
	 *
	 * @return array all categories
	 */
	public function get_categories() {
		$args = array(
			'hide_empty' => 0,
			'orderby'    => 'name'
		);
		return get_categories( $args );
	}
}
