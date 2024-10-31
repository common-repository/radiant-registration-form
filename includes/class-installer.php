<?php
/**
 * Installer Handler
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration;

/**
 * Installer class
 *
 * @package RadiantRegistration
 */
class Installer {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->create_default_form();

		$installed = get_option( 'RADIANT_REGISTRATION_INSTALLED' );

		if( !$installed ) {
			update_option( 'RADIANT_REGISTRATION_INSTALLED', time() );
		}

		update_option( 'RADIANT_REGISTRATION_VERSION', RADIANT_REGISTRATION_VERSION );
	}
	/**
	 * create default form
	 *
	 * @return void
	 */
	public function create_default_form() {
		$version = get_option( 'RADIANT_REGISTRATION_VERSION' );

		if( $version ) {
			return;
		}

		radiant_registration()->templates->create('blank');
	}
}
