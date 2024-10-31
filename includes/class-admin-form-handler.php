<?php
/**
 * Admin Form Handler
 *
 * @author Rokibul
 * @package Product_Addon_Custom_Field
 */

namespace RadiantRegistration;

/**
 * Admin class
 *
 * @package Product_Addon_Custom_Field
 */
class Admin_Form_Handler {

	/**
	 *  Constructor
	 */
	public function __construct() {
		add_action( 'load-toplevel_page_radiant-registration', array( $this, 'forms_actions' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 *  Verify current page
	 *
	 * @param string $page_id page_id.
	 * @param string $bulk_action bulk_action.
	 *
	 * @return boolean
	 */
	public function verify_current_page_screen( $page_id, $bulk_action ) {

		if ( ! isset( $_REQUEST['_wpnonce'] ) || ! isset( $_REQUEST['page'] ) ) {
			return false;
		}

		if ( sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) !== $page_id ) {
			return false;
		}

		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_key( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';

		if ( isset( $nonce ) && ! wp_verify_nonce( $nonce, $bulk_action ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Forms action
	 *
	 * @return void
	 */
	public function forms_actions() {
		// Nonce validation.
		if ( ! $this->verify_current_page_screen( 'radiant-registration', 'bulk-radiant-forms' ) ) {
			return;
		}

		if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['radiant_nonce'] ) ), 'radiant_form_list_nonce' ) ) { 
			die('error');
		}

		$ids = isset( $_REQUEST['id'] ) ? wp_parse_id_list( wp_unslash( $_REQUEST['id'] ) ) : array();

		$chi_forms = new Forms_List_Table();
		$action    = $chi_forms->current_action();

		if ( $action ) {
			$remove_query_args = array( '_wp_http_referer', '_wpnonce', 'prafe_nonce', 'action', 'id', 'post', 'action2', 'paged', 'doaction' );
			$add_query_args    = array();

			switch ( $action ) {
				case 'prafe':
					$redirect = remove_query_arg( array( 'prafe' ), $remove_query_args );
					break;

				case 'delete':
					foreach ( $ids as $id ) {
						wp_delete_post( $id );
					}
					$add_query_args['deleted'] = count( $ids );
					break;

				case 'duplicate':
					if ( ! empty( $_GET['id'] ) ) {
						$id                           = intval( sanitize_text_field( wp_unslash( $_GET['id'] ) ) );
						$add_query_args['duplicated'] = product_addon_extra_field()->forms->duplicate( $id );
					}
					break;
			}

			if ( ( isset( $_REQUEST['action'] ) && wp_unslash( $_REQUEST['action'] ) === 'bulk-delete' ) || ( isset( $_REQUEST['action2'] ) && wp_unslash( $_REQUEST['action2'] ) === 'bulk-delete' ) ) {
				$ids = esc_sql( sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) );

				foreach ( $ids as $id ) {
					wp_delete_post( $id );
				}
			}

			$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			$redirect    = remove_query_arg( $remove_query_args, $request_uri );
			$redirect    = add_query_arg( $add_query_args, $redirect );

			wp_redirect( esc_url( $redirect ) );
			exit;
		}
	}

	/**
	 *  Admin notice
	 *
	 * @return void
	 */
	public function admin_notices() {
		
		if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'radiant_form_list_action' ) ) { 
			die('error');
		}

		if ( ! empty( $_GET['duplicated'] ) ) {
			$duplicated = sanitize_text_field( wp_unslash( $_GET['duplicated'] ) );
			$notice     = sprintf( __( 'Form duplicated successfully', 'radiant-registration' ) );
			$this->display_notice( $notice );
		}
	}

	/**
	 *  Display notice
	 *
	 * @param string $text text.
	 * @param string $type type.
	 *
	 * @return void
	 */
	public function display_notice( $text, $type = 'updated' ) {
		printf( '<div class="%s"><p>%s</p></div>', esc_attr( $type ), wp_kses_post( $text ) );
	}
}
