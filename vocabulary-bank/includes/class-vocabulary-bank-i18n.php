<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://magnigeeks.com
 * @since      1.0.0
 *
 * @package    Vocabulary_Bank
 * @subpackage Vocabulary_Bank/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Vocabulary_Bank
 * @subpackage Vocabulary_Bank/includes
 * @author     Bikash sahoo <bikashsahoobiki1999@gmail.com>
 */
class Vocabulary_Bank_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'vocabulary-bank',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
