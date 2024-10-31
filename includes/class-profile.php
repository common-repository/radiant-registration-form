<?php
/**
 * Profile Handler
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration;

/**
 * Profile class
 *
 * @package RadiantRegistration
 */
class Profile {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'personal_options_update', array( $this, 'save_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_fields' ) );

		add_action( 'show_user_profile', array( $this, 'render_fields' ) );
		add_action( 'edit_user_profile', array( $this, 'render_fields' ) );
	}

	/**
	 * Save Fields
	 *
	 * @param int $user_id user_id.
	 *
	 * @return void
	 */
	public function save_fields( $user_id ) {

	}

	/**
	 * Render Fields
	 *
	 * @param array $userdata userdata.
	 * @param int $user_id user_id.
	 * @param boolean $preview preview.
	 *
	 * @return void
	 */
	public function render_fields( $userdata, $user_id, $preview = false ) {

	}
}
