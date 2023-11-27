<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://magnigeeks.com
 * @since             1.0.0
 * @package           Vocabulary_Bank
 *
 * @wordpress-plugin
 * Plugin Name:       Vocabulary Bank
 * Plugin URI:        https://magnigeeks.com
 * Description:       Add the option under the quiz create/edit screen to select specific words that will come across the different quiz questions.
 * Version:           1.0.0
 * Author:            Bikash sahoo
 * Author URI:        https://magnigeeks.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       vocabulary-bank
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'VOCABULARY_BANK_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-vocabulary-bank-activator.php
 */
function activate_vocabulary_bank() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vocabulary-bank-activator.php';
	Vocabulary_Bank_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-vocabulary-bank-deactivator.php
 */
function deactivate_vocabulary_bank() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vocabulary-bank-deactivator.php';
	Vocabulary_Bank_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_vocabulary_bank' );
register_deactivation_hook( __FILE__, 'deactivate_vocabulary_bank' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-vocabulary-bank.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_vocabulary_bank() {

	$plugin = new Vocabulary_Bank();
	$plugin->run();

}
run_vocabulary_bank();
