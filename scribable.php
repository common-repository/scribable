<?php
/**
 * Plugin Name: Scribable
 * Description: WP REST api connection with Scribable platform
 * Author: Scribable
 * Author URI: https://scribable.io
 * Version: 1.0.3
 */

// adapted from https://github.com/WP-API/Basic-Auth with 
// addition of password encryption

if ( ! defined( 'SCRIBABLE_VERSION' ) ) {
	define( 'SCRIBABLE_VERSION', '1.0.3' );
}

if ( ! defined( 'SCRIBABLE_BASENAME' ) ) {
	define( 'SCRIBABLE_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'SCRIBABLE_PATH' ) ) {
	define( 'SCRIBABLE_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

$plugin_class_file = 'scribable';

$includes = array(
	'settings/class-' . $plugin_class_file . '-settings-base.php',
	'settings/class-' . $plugin_class_file . '-settings-general.php',
	'settings/class-' . $plugin_class_file . '-settings.php',
	'inc/class-' . $plugin_class_file . '-common.php',
	'inc/class-' . $plugin_class_file . '-auth-handler.php',
);

$class_base = 'Scribable';

$classes = array(
	$class_base . '_Common',
);


/* Include classes */
foreach ( $includes as $include ) {
	require_once SCRIBABLE_PATH . $include;
}

/* Instantiate classes and hook into WordPress */
foreach ( $classes as $class ) {
	$plugin = new $class();
	if ( method_exists( $class, 'plugins_loaded' ) ) {
		add_action( 'plugins_loaded', array( $plugin, 'plugins_loaded' ), 1 );
	}
}

Scribable_Settings::plugins_loaded();
Scribable_Settings_General::plugins_loaded();
Scribable::plugins_loaded();

/* Activation hook */
register_activation_hook(
	__FILE__,
	function() {
		require_once 'inc/class-scribable-activator.php';
		Scribable_Activator::activate();
	}
);