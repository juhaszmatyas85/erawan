<?php

/**
 * Blablabla
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define global variables
 */
define( 'ERAWAN_PATH', get_stylesheet_directory() . '/' );
define( 'ERAWAN_URL', get_stylesheet_directory_uri() . '/' );
define( 'ERAWAN_TEMPLATE_PATH', trailingslashit( ERAWAN_PATH ) . 'templates/' );

/**
 * Composer autoloader
 */
require_once ERAWAN_PATH . 'vendor/autoload.php';

use Erawan\Activator;
use Erawan\Deactivator;
use Erawan\Main;

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-norbth-gge-deactivator.php
 */
function activate() {
	$activator = new Activator();
	$activator->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-norbth-gge-deactivator.php
 */
function deactivate() {
	$deactivator = new Deactivator();
	$deactivator->deactivate();
}

register_activation_hook( __FILE__, 'activate' );
register_deactivation_hook( __FILE__, 'deactivate' );

/**
 * Begins execution of the plugin.
 */
function run() {
	$plugin = new Main();
	$plugin->run();
}
run();
