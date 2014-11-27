<?php

/**
 * The Public Class
 *
 * This Class is responsible to display the message in posts in the frontend.
 *
 * @since      1.0.0
 * @package    Tax-Settings
 * @subpackage Tax-Settings/public
 * @author     Hans-Hege Buerger
 */
class TS_Category_Message {
	/**
	 * Method which gets the content of a post and prepends the message
	 *
	 * @since  1.0.0
	 *
	 * @param  string $content original content
	 * @return string          if single then altered content
	 */
	public function show_message( $content ) {
		if ( is_single() ) {
			$text = $this->get_cat_text();
			$banner = sprintf( "<blockquote class='ts-banner'>%s</blockquote>", esc_attr( $text ) );
			$content = $banner . $content;
		}
		return $content;
	}

	/**
	 * Method to enqueue a custom style.
	 *
	 * @since  1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'tax-settings', plugin_dir_url( __FILE__ ) . 'css/ts-settings-public.css' );
	}

	/**
	 * Custom method to get the category message from the database
	 *
	 * @since  1.0.0
	 * @return string category message
	 */
	public function get_cat_text() {
		$options = get_option('ts_options');

		if ( !empty( $options ) and isset( $options['ts_category_message'] )) {
			return $options['ts_category_message'];
		}

		return '';
	}
}
