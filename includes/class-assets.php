<?php
/**
 * Assets
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration;

/**
 * Assets class
 *
 * @package RadiantRegistration
 */
class Assets {

	/**
	 * Settings
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * Constructor
	 */
	function __construct() {
		global $post;

		add_action( 'admin_enqueue_scripts', array( $this, 'register_builder_backend' ), 10 );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend' ) );

		add_action( 'in_admin_header', array( $this, 'remove_admin_notices' ) );

		if ( isset( $_GET['radiant_page_nonce'] ) && 
			wp_verify_nonce( sanitize_text_field( $_GET['radiant_page_nonce'] ) , 'radiant-page' ) 
		) {
			$id   = isset( $_GET['id'] ) ? intval( wp_unslash( $_GET['id'] ) ) : '';
			$post = get_post( $id );

			if ( !empty( $post->ID ) ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_builder_scripts' ) );
			}
		}
	}

	/**
	 * Remove admin notice
	 *
	 * @return void
	 */
	public function remove_admin_notices() {
		remove_all_actions( 'network_admin_notices' );
		remove_all_actions( 'user_admin_notices' );
		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );
	}

	/**
	 * Get frontend localize script
	 *
	 * @return array
	 */
	public function get_frontend_localized_scripts() {
		return apply_filters(
			'radiant_registration_frontend_localize_script',
			array(
				'confirmMsg' => __( 'Are you sure?', 'radiant-registration' ),
				'delete_it'  => __( 'Yes, delete it', 'radiant-registration' ),
				'cancel_it'  => __( 'No, cancel it', 'radiant-registration' ),
				'nonce'      => wp_create_nonce( 'radiant_registration_nonce' ),
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'plupload'   => array(
					'url'              => admin_url( 'admin-ajax.php' ) . '?nonce=' . wp_create_nonce( 'radiant_registration-upload-nonce' ),
					'flash_swf_url'    => includes_url( 'js/plupload/plupload.flash.swf' ),
					'filters'          => array(
					array(
						'title'      => __( 'Allowed Files', 'radiant-registration' ),
						'extensions' => '*',
					),
				),
				'multipart'        => true,
				'urlstream_upload' => true,
				'warning'          => __( 'Maximum number of files reached!', 'radiant-registration' ),
				'size_error'       => __( 'The file you have uploaded exceeds the file size limit. Please try again.', 'radiant-registration' ),
				'type_error'       => __( 'You have uploaded an incorrect file type. Please try again.', 'radiant-registration' ),
			)
			)
		);
	}

	/**
	 * Get admin localize script
	 *
	 * @return array
	 */
	public function get_admin_localized_scripts() {
		global $post;
		
		$form = radiant_registration()->forms->get( $post );
		$radiant_registration_settings = radiant_registration_get_settings();

		return apply_filters(
			'radiant_registration_admin_localize_script',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'radiant_registration-form-builder-nonce' ),
				'rest'    => array(
					'root'    => esc_url_raw( get_rest_url() ),
					'nonce'   => wp_create_nonce( 'wp_rest' ),
					'version' => 'radiant/v1',
				),
				'field_settings' => radiant_registration()->fields->get_js_settings(),
				'panel_sections' => radiant_registration()->fields->get_field_groups(),
				'form_fields'    => $form->getFields(),
				'settings'       => $form->getSettings(),
				'post'           => $post,
				'preview_url'    => esc_url( add_query_arg('new_window', 1, radiant_registration_get_form_preview_url( $form->id  ) ) ),
				'contact_cond_supported_fields'  => array( 'radio_field', 'checkbox_field', 'dropdown_field' ),
				'smart_tags'     => radiant_registration()->smarttags->getMergeTags(),
				'single_objects' => array( 'user_login', 'first_name', 'last_name','display_name','nickname','user_email','user_url','user_bio','password','avatar' ),
				'radiant_registration_settings' => $radiant_registration_settings,
			)
		);

	}

	/**
	 * Enqueue Assets frontend
	 *
	 * @return void
	 */
	public function enqueue_frontend_scripts() {
		wp_enqueue_style( 'radiant_registration-frontend-css' );
		wp_enqueue_script( 'radiant_registration-upload' );
		wp_enqueue_script( 'radiant_registration-frontend' );
		$localize_script = $this->get_frontend_localized_scripts();
		wp_localize_script( 'radiant_registration-frontend', 'frontend', $localize_script );
	}

	/**
	 * Register builder script and styles
	 *
	 * @return void
	 */
	public function register_builder_backend() {
		$screen = get_current_screen();

		if ( $screen->base !== 'toplevel_page_radiant-registration' ) {
			return;
		}

		$this->register_styles( $this->get_admin_styles() );
		$this->register_scripts( $this->get_admin_scripts() );

		wp_enqueue_script('radiant_registration-admin-modal');
		wp_enqueue_style('radiant_registration-admin-modal');
	}

	/**
	 * Enqueue Builder Scripts
	 *
	 * @return void
	 */
	public function enqueue_builder_scripts() {
		$screen = get_current_screen();


		if ( $screen->base !== 'toplevel_page_radiant-registration' ) {
			return;
		}

		$this->enqueue_styles( $this->get_admin_styles() );
		$this->enqueue_scripts( $this->get_admin_scripts() );

		$localize_script = $this->get_admin_localized_scripts();
		wp_localize_script( 'radiant_registration-admin', 'radiant_registration', $localize_script );
	}

	/**
	 * Register frontend script and styles
	 *
	 * @return void
	 */
	public function register_frontend() {
		$this->register_styles( $this->get_frontend_styles() );
		$this->register_scripts( $this->get_frontend_scripts() );
	}

	/**
	 * Enqueue Assets frontend
	 *
	 * @return void
	 */
	public function enqueue_frontend() {
		$this->enqueue_styles( $this->get_frontend_styles() );
		$this->enqueue_scripts( $this->get_frontend_scripts() );

		$localize_script = $this->get_frontend_localized_scripts();

		wp_localize_script( 'radiant_registration-frontend', 'radiant_registration', $localize_script );

		wp_localize_script(
			'radiant_registration-frontend',
			'error_str_obj', 
			array(
				'required'   => __( 'is required', 'radiant-registration' ),
				'mismatch'   => __( 'does not match', 'radiant-registration' ),
				'validation' => __( 'is not valid', 'radiant-registration' ),
				'duplicate'  => __( 'requires a unique entry and this value has already been used', 'radiant-registration' ),
			)
		);
	}

	/**
	 * Get admin styles
	 *
	 * @return array
	 */
	public function get_admin_styles() {
		$styles = array(
			'radiant_registration-admin'          => array(
				'src' => RADIANT_REGISTRATION_ASSETS . '/css/admin.css'
			),
			'radiant_registration-admin-modal'          => array(
				'src' => RADIANT_REGISTRATION_ASSETS . '/css/admin-modal.css'
			),
			'radiant_registration-font-awesome'   => array(
				'src' => RADIANT_REGISTRATION_ASSETS . '/css/font-awesome/css/font-awesome.min.css',
			)
		);

		return apply_filters( 'radiant_registration_admin_styles', $styles );
	}

	/**
	 * Get admin scripts
	 *
	 * @return array
	 */
	public function get_admin_scripts() {

		$form_builder_js_deps = apply_filters(
			'radiant_registration_builder_js_deps',
			array(
				'jquery',
				'jquery-ui-sortable',
				'jquery-ui-draggable',
				'jquery-ui-droppable',
				'jquery-ui-resizable',
				'underscore',
				'clipboard',
				'radiant_registration-jquery-scrollto'
			)
		);

		$scripts = array(
			'radiant_registration-admin'          => array(
				'src'       => RADIANT_REGISTRATION_ASSETS . '/js/admin.js',
				'deps'      => $form_builder_js_deps,
				'version'   => filemtime( RADIANT_REGISTRATION_PATH . '/assets/js/admin.js' ),
				'in_footer' => true
			),
			'radiant_registration-admin-modal'          => array(
				'src'       => RADIANT_REGISTRATION_ASSETS . '/js/admin-modal.js',
				'deps'      => ['jquery'],
				'version'   => filemtime( RADIANT_REGISTRATION_PATH . '/assets/js/admin-modal.js' ),
				'in_footer' => true
			),
			'radiant_registration-jquery-scrollto' => array(
				'src'       => RADIANT_REGISTRATION_ASSETS . '/js/jquery.scrollTo.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( RADIANT_REGISTRATION_PATH . '/assets/js/jquery.scrollTo.js' ),
				'in_footer' => true
			)
		);

		return apply_filters( 'radiant_registration_admin_scripts', $scripts );
	}

	/**
	 * Get frontend scripts
	 *
	 * @return array
	 */
	public function get_frontend_styles() {
		$styles = array(
			'radiant_registration-frontend' => array(
				'src' => RADIANT_REGISTRATION_ASSETS . '/css/frontend.css',
			),
			'radiant_registration-choices' => array(
				'src' => RADIANT_REGISTRATION_ASSETS . '/css/choices.css',
			),
			'radiant_registration-flatpickr' => array(
				'src' => RADIANT_REGISTRATION_ASSETS . '/css/flatpickr.css',
			),
		);

		return apply_filters( 'radiant_registration_frontend_styles', $styles );
	}

	/**
	 * Get frontend scripts
	 *
	 * @return array
	 */
	public function get_frontend_scripts() {

		$scripts = array(
			
			'radiant_registration-frontend' => array(
				'src'       => RADIANT_REGISTRATION_ASSETS . '/js/frontend.js',
				'deps'      => array( 
					'jquery',
					'radiant_registration-choices',
					'radiant_registration-flatpickr'
				),
				'version'   => filemtime( RADIANT_REGISTRATION_PATH . '/assets/js/frontend.js' ),
				'in_footer' => true,
			),

			'radiant_registration-choices'   => array(
				'src'       => RADIANT_REGISTRATION_ASSETS . '/js/choices.js',
				'deps'      => array( 'jquery' ),
				'version'   => filemtime( RADIANT_REGISTRATION_PATH . '/assets/js/choices.js' ),
				'in_footer' => true,
			),

			'radiant_registration-flatpickr' => array(
				'src'       => RADIANT_REGISTRATION_ASSETS . '/js/flatpickr.js',
				'deps'      => array( 'jquery' ),
				'in_footer' => true,
			),
		);

		return apply_filters( 'radiant_registration_frontend_scripts', $scripts );
	}

	/**
	 * Register scripts
	 *
	 * @param array $scripts scripts.
	 *
	 * @return void
	 */
	public function register_scripts( $scripts ) {
		foreach ( $scripts as $handle => $script ) {
			$deps      = isset( $script['deps'] ) ? $script['deps'] : false;
			$in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
			$version   = isset( $script['version'] ) ? $script['version'] : RADIANT_REGISTRATION_VERSION;

			wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
		}
	}

	/**
	 * Register styles
	 *
	 * @param array $styles styles.
	 *
	 * @return void
	 */
	public function register_styles( $styles ) {
		foreach ( $styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;
			wp_register_style( $handle, $style['src'], $deps, RADIANT_REGISTRATION_VERSION );
		}
	}

	/**
	 * Enqueue the scripts
	 *
	 * @param array $scripts scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts( $scripts ) {
		foreach ( $scripts as $handle => $script ) {
			wp_enqueue_script( $handle );
		}
	}

	/**
	 * Enqueue the styles
	 *
	 * @param array $styles styles.
	 *
	 * @return void
	 */
	public function enqueue_styles( $styles ) {
		foreach ( $styles as $handle => $script ) {
			wp_enqueue_style( $handle );
		}
	}
}
