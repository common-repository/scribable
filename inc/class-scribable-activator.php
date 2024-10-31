<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

class Scribable_Activator {


	public static function activate() {

		Scribable_Settings::create_default_settings();

		// add an option so we can show the activated admin notice
		add_option( Scribable_Common::PLUGIN_NAME . '-plugin-activated', '1' );

	}



}
