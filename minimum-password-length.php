<?php
/*
 Plugin Name: Minimum Password Length
 Version: 1.0.2
 Plugin URI: http://www.beapi.fr
 Description: Enforce a specific password length.
 Author: BE API Technical team
 Author URI: http://www.beapi.fr
 Domain Path: languages
 Text Domain: bea-plugin-boilerplate
 
 ----
 
 Copyright 2016 BE API Technical team (human@beapi.fr)
 
 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 
 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

 class Minimum_Password_Length {
	const LENGTH_KEY = 'minimum_password_length';

	const SHORT_PASS = 1;
	const STRONG_PASS = 4;
	const MISMATCH = 5;

	const DEFAULT_REQUIRED_LENGTH = 8;

 	public static function start() {
		add_action( 'user_profile_update_errors', array( __CLASS__, 'check_password_length' ) );
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
		add_action( 'validate_password_reset', array( __CLASS__, 'check_password_length' ) );
	}

	public static function check_password_length( $errors ) {
		$password1 = isset( $_POST['pass1'] ) ? $_POST['pass1'] : '';
		$password2 = isset( $_POST['pass2'] ) ? $_POST['pass2'] : '';
		
		if ( empty( $password1 ) && empty( $password2 ) ) {
			return;
		}

		$result = self::get_password_length( $password1, $password2 );

		if ( self::MISMATCH == $result ) {
			$errors->add( 'mismatched-password', 'The passwords you entered do not match', array( 'form-field' => 'pass1' ) );
		} elseif ( self::SHORT_PASS == $result ) {
			$errors->add( 'weak-password', sprintf( __( 'Your password must be at least %d characters', 'minimum-password-length' ), self::get_required_length() ), array( 'form-field' => 'pass1' ) );
		}
	}

	public static function add_menu() {
		add_options_page( __( 'Minimum Password Length', 'minimum-password-length' ), __( 'Password Length', 'minimum-password-length' ), 'manage_options', __FILE__, array( __CLASS__, 'show_settings_page' ) );
	}

	public static function show_settings_page() {
		if ( isset( $_POST['submit'] ) && isset( $_POST['_wpnonce'] ) &&
				wp_verify_nonce( $_POST['_wpnonce'], 'update_minimum_password_length' ) ) {
			$length = intval( $_POST['length'] );
			update_option( self::LENGTH_KEY, $length );
		}
		
		$current_length = self::get_required_length();

		include plugin_dir_path( __FILE__ ) . 'views/settings.php';
	}

	public static function get_required_length() {
		return get_option( self::LENGTH_KEY, self::DEFAULT_REQUIRED_LENGTH );
	}

	public static function get_password_length( $password1, $password2 ) {
		if ( strcmp( $password1, $password2 ) )
			return self::MISMATCH;

		if ( mb_strlen( $password1 ) < self::get_required_length() )
			return self::SHORT_PASS;
		
		return self::STRONG_PASS;
	}
 }

 Minimum_Password_Length::start();
