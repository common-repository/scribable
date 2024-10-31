<?php
if ( ! defined( 'ABSPATH' ) ) die( 'restricted access' );

if ( ! class_exists( 'Scribable' ) ) {

	class Scribable {

        public static function plugins_loaded() {
            add_filter( 'determine_current_user', array( __CLASS__, 'basic_auth_handler' ), 20 );
            add_filter( 'rest_authentication_errors', array( __CLASS__, 'basic_auth_error' ) );
        }

        public static function basic_auth_handler( $user ) {
            global $wp_json_basic_auth_error;
            $settings = get_option( 'scribable-settings-general' );
        
            // Used for decrypting the password
            $encryptionMethod = "AES-256-CBC";
            $encryption_key   = isset( $settings['encryption-key'] ) ? esc_html( $settings['encryption-key'] ) . 'AjNuyTsO' : '';
            $iv               = substr($encryption_key, 0, 16);
            
            $wp_json_basic_auth_error = null;
        
            // Don't authenticate twice
            if ( ! empty( $user ) ) {
                return $user;
            }
        
            // Check that we're trying to authenticate
            if ( !isset( $_SERVER['PHP_AUTH_USER'] ) ) {
                return $user;
            }
        
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
        
            $password = openssl_decrypt($password, $encryptionMethod, $encryption_key, 0, $iv);
        
            remove_filter( 'determine_current_user', 'json_basic_auth_handler', 20 );
        
            $user = wp_authenticate( $username, $password );
        
            add_filter( 'determine_current_user', 'json_basic_auth_handler', 20 );
        
            if ( is_wp_error( $user ) ) {
                $wp_json_basic_auth_error = $user;
                return null;
            }
        
            $wp_json_basic_auth_error = true;
        
            return $user->ID;
        }
        
        public static function basic_auth_error( $error ) {
            // Passthrough other errors
            if ( ! empty( $error ) ) {
                return $error;
            }
        
            global $wp_json_basic_auth_error;
        
            return $wp_json_basic_auth_error;
        }
    }
}