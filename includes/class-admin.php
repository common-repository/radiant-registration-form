<?php
/**
 * Admin
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration;

/**
 * Admin class
 *
 * @package RadiantRegistration
 */
class Admin{

	/**
	 * settings api
	 * 
	 * @var
	 */
	private $settings_api;

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'parent_file', array( $this, 'fix_parent_menu' ) );
	}

	public function screen_options_set( $keep, $option, $value ) {
		if ( in_array( $option, array( 'registration_forms_per_page'  ), true ) ) {
			return $value;
		}

		return $keep;
	}


	public function screen_options_forms() {

		$screen = get_current_screen();

		if ( null === $screen || 'toplevel_page_radiant-registration' !== $screen->id ) {
			return;
		}

		add_screen_option(
			'per_page',
			array(
				'label'   => esc_html__( 'Number of forms per page:', 'radiant-registration' ),
				'option'  => 'registration_forms_per_page',
				'default' =>  20,
			)
		);
	}

	public function fix_parent_menu( $parent_file ) {
		$current_screen = get_current_screen();
		$post_types     = [ 'registration_forms' ];

		if ( in_array( $current_screen->post_type, $post_types ) ) {
			$parent_file = 'registration';
		}

		return $parent_file;
	}

	/**
	 * Register form post types
	 *
	 * @return void
	 */
	public function register_post_type() {
		$capability = 'manage_options';

		register_post_type( 'registration_forms',
		array(
			'label'           => __( 'Forms', 'radiant-registration' ),
			'public'          => false,
			'show_ui'         => false,
			'show_in_menu'    => false, //false,
			'capability_type' => 'post',
			'hierarchical'    => false,
			'query_var'       => false,
			'supports'        => array( 'title' ),
			'capabilities'    => array(
				'publish_posts'       => $capability,
				'edit_posts'          => $capability,
				'edit_others_posts'   => $capability,
				'delete_posts'        => $capability,
				'delete_others_posts' => $capability,
				'read_private_posts'  => $capability,
				'edit_post'           => $capability,
				'delete_post'         => $capability,
				'read_post'           => $capability,
			),
			'labels' => array(
				'name'               => __( 'Forms', 'radiant-registration' ),
				'singular_name'      => __( 'Form', 'radiant-registration' ),
				'menu_name'          => __( 'Forms', 'radiant-registration' ),
				'add_new'            => __( 'Add Form', 'radiant-registration' ),
				'add_new_item'       => __( 'Add New Form', 'radiant-registration' ),
				'edit'               => __( 'Edit', 'radiant-registration' ),
				'edit_item'          => __( 'Edit Form', 'radiant-registration' ),
				'new_item'           => __( 'New Form', 'radiant-registration' ),
				'view'               => __( 'View Form', 'radiant-registration' ),
				'view_item'          => __( 'View Form', 'radiant-registration' ),
				'search_items'       => __( 'Search Form', 'radiant-registration' ),
				'not_found'          => __( 'No Form Found', 'radiant-registration' ),
				'not_found_in_trash' => __( 'No Form Found in Trash', 'radiant-registration' ),
				'parent'             => __( 'Parent Form', 'radiant-registration' ),
			),
			)
		);

		register_post_type( 'registration_input',
			array(
				'public'       => false,
				'show_ui'      => false,
				'show_in_menu' => false,
			)
		);
	}

	/**
	 *
	 * admin menu
	 *
	 * @return void|string
	 */
	public function admin_menu() {
		global $submenu;

		$capability = 'manage_options';
		$slug       = 'radiant-registration';

		$hook = add_menu_page( __( 'Radient Registration', 'radiant-registration' ), __( 'Radient Registration', 'radiant-registration' ), $capability, $slug, [ $this, 'forms_page' ], 'dashicons-text' );
		remove_submenu_page( 'radiant-registration', 'radiant-registration' );
		add_submenu_page( $slug, __( 'Radient Registration', 'radiant-registration' ), __( 'Radient Registration', 'radiant-registration' ), $capability, 'radiant-registration', array( $this, 'forms_page' ) );
	}

	/**
	 * load forms page
	 *
	 * @return void
	 */
	public function forms_page() {

		$add_new_page_url =	esc_url(
			wp_nonce_url(
				add_query_arg(
					array(
						'action' => 'add-new',
					),
					admin_url( 'admin.php?page=radiant-registration' )
				),
				'radiant-page',
				'radiant_page_nonce'
			)
		);

		if ( isset( $_GET['radiant_page_nonce'] ) &&  wp_verify_nonce( sanitize_text_field( $_GET['radiant_page_nonce'] ) , 'radiant-page' ) ) {
			$action = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : null;
			switch ( $action ) {
				case 'edit':
					require_once RADIANT_REGISTRATION_INCLUDES . '/html/form.php';
					break;
				case 'add-new':
					require_once RADIANT_REGISTRATION_INCLUDES . '/html/form.php';
					break;
				default:
					require_once RADIANT_REGISTRATION_INCLUDES . '/html/form-list-view.php';
					break;
			}

		} else {
			require_once RADIANT_REGISTRATION_INCLUDES . '/html/form-list-view.php';
		}
	}
}
