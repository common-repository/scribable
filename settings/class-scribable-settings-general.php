<?php

if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'Scribable_Settings_General' ) ) {

	class Scribable_Settings_General extends Scribable_Settings_Base {

		static $settings_key  = 'scribable-settings-general';


		static public function plugins_loaded() {
			add_action( 'admin_init', array( __CLASS__, 'register_general_settings' ) );
			add_filter( 'scribable-settings-tabs', array( __CLASS__, 'add_tab') );
		}


		static public function add_tab( $tabs ) {
			$tabs[ self::$settings_key ] = __( 'General', 'scribable' );
			return $tabs;
		}


		static public function get_default_settings() {
			return array(
				'encryption-key'   => '',
			);
		}


		static public function register_general_settings() {
			$key = self::$settings_key;

			register_setting( $key, $key, array( __CLASS__, 'sanitize_settings') );

			$section = 'general';

			add_settings_section( $section, '', null, $key );

			add_settings_field( 'encryption-key', __( 'Encryption Key', 'scribable' ), array( __CLASS__, 'settings_input' ), $key, $section,
				array( 'key' => $key, 'name' => 'encryption-key', 'after' => '' ) );

		}
	}

}

