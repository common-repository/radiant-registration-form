<?php
/**
 * Admin Form Handler
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

 namespace RadiantRegistration;

/**
 * Admin Template class
 *
 * @package RadiantRegistration
 */
class Admin_Template {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_footer', array( $this, 'render_templates' ) );
		add_filter( 'admin_action_create_template', array( $this, 'create_template' ) );
	}

	/**
	 * Render footer template
	 *
	 * @return void|boolean
	 */
	public function render_templates() {
		$current_screen = get_current_screen();

		if ( ! in_array( $current_screen->id, array( 'toplevel_page_radiant-registration' ) ) ) {
			return true;
		}

		$templates      = radiant_registration()->templates->get_templates();
		$blank_form_url = admin_url( 'admin.php?page=radiant-registration&action=add-new' );
		$action_name    = 'create_template';

		include __DIR__ . '/html/modal.php';
	}

	/**
	 * Create Template
	 *
	 * @return void|string
	 */
	public function create_template() {

		if ( !wp_verify_nonce( wp_unslash( sanitize_text_field( wp_unslash( $_REQUEST['radiant_nonce'] ) ) ), 'radiant_create_nonce' ) ) { 
			die('error');
		}

		$template = ( isset( $_GET['template'] ) && wp_unslash( $_GET['template'] ) !== null ) ? sanitize_text_field( wp_unslash( $_GET['template'] ) ) : '';
		
		if ( empty( $template ) ) {
			return ;
		}

		$template_obj = radiant_registration()->templates->get_template( $template );

		if( $template_obj == false ) {
			return;
		}

		$form_id = radiant_registration()->templates->create( $template );
		
		wp_redirect( admin_url( 'admin.php?page=radiant-registration&action=edit&id='. $form_id . '&radiant_page_nonce=' . wp_create_nonce('radiant-page') ) );
		exit;
	}
}
