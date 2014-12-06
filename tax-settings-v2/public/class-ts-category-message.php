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
		$text = $this->get_cat_text();
		$banner = sprintf( "<blockquote class='ts-banner'>%s</blockquote>", esc_attr( $text ) );
		$content = $banner . $content;
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
		global $post;
		$settings_model = new TS_Settings();

		$cats = wp_get_post_categories( $post->ID, array( 'fields' => 'slugs' ) );

		/* Make use of get_option_by_key method to get the correct
		message for the category of this post.
		In this tutorial the first category of the post is used to make things
		simple. But if you want to show all messages just loop over the $cats
		array and display each message. */
		$value = $settings_model->get_option_by_key( $cats[0], 'ts_cat_message' );

		return $value['field_value'];
	}
}
