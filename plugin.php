<?php
/**
 * Plugin Name:       10up Plugin Scaffold
 * Plugin URI:        https://github.com/10up/plugin-scaffold
 * Description:       A brief description of the plugin.
 * Version:           0.1.0
 * Requires at least: 4.9
 * Requires PHP:      7.2
 * Author:            10up
 * Author URI:        https://10up.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hero
 * Domain Path:       /languages
 *
 * @package           Hero
 */

// Useful global constants.
define( 'HERO_VERSION', '0.1.0' );
define( 'HERO_URL', plugin_dir_url( __FILE__ ) );
define( 'HERO_PATH', plugin_dir_path( __FILE__ ) );
define( 'HERO_INC', HERO_PATH . 'includes/' );

// Include files.
require_once HERO_INC . 'functions/core.php';
require_once HERO_INC . 'blocks/integrated-hero/integrated-hero.php';

// Activation/Deactivation.
register_activation_hook( __FILE__, '\Hero\Core\activate' );
register_deactivation_hook( __FILE__, '\Hero\Core\deactivate' );

// Bootstrap.
Hero\Core\setup();

// Require Composer autoloader if it exists.
if ( file_exists( HERO_PATH . '/vendor/autoload.php' ) ) {
	require_once HERO_PATH . 'vendor/autoload.php';
}
