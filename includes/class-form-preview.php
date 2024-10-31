<?php
/**
 * Form Preview Template
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration;

use RadiantRegistration\Form;
use WP_Query;

/**
 * Form Preview class
 *
 * @package RadiantRegistration
 */
class Form_Preview {

	/**
	 * Form Id
	 * 
	 * @var int
	 */
	private $form_id;

	/**
	 * Is Preview
	 * 
	 * @var boolean
	 */
	private $is_preview = true;

	/**
	 * Constructor
	 */
	public function __construct() {

		if ( isset( $_GET['radiant_preview_nonce'] ) && 
			!wp_verify_nonce( sanitize_text_field( $_GET['radiant_preview_nonce'] ) , 'radiant-preview' ) 
		) {
			die('error preview');
		}

		if( ! isset( $_GET[ 'radiant_registration_form_preview' ] ) && empty( $_GET['radiant_registration_form_preview'] ) ) {
			return;
		}

		$this->form_id = isset( $_GET['radiant_registration_form_preview'] ) ? intval( sanitize_text_field( wp_unslash( $_GET['radiant_registration_form_preview'] ) ) ): 0;

		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		add_filter( 'template_include', array( $this, 'template_include' ) );

		add_filter( 'the_title', array( $this, 'the_title' ) );
		add_filter( 'the_content', array( $this, 'the_content' ) );
		add_filter( 'get_the_excerpt', array( $this, 'the_content' ) );
		add_filter( 'post_thumbnail_html', '__return_empty_string' );
	}

	/**
	 * set post per page
	 *
	 * @param object $query query.
	 *
	 * @return object
	 */
	public function pre_get_posts( $query ) {
		if ( $query->is_main_query() ) {
			$query->set( 'posts_per_page', 1 );
		}

		return $query;
	}

	/**
	 * set title
	 *
	 * @param string $title title.
	 *
	 * @return string
	 */
	public function the_title( $title ) {
		return $title;
	}

	/**
	 * set preview content
	 *
	 * @param string $content content.
	 *
	 * @return string
	 */
	public function the_content( $content ) {
		if ( $this->is_preview ) {
			if ( ! is_user_logged_in() ) {
				return __( 'You must be logged in to preview this form.', 'radiant-registration' );
			}
		}

		return do_shortcode( sprintf( '[radiant_registration id="%d"]', $this->form_id ) );

	}

	/**
	 * template added
	 *
	 * @return string
	 */
	public function template_include() {
		return locate_template( array( 'page.php', 'single.php', 'index.php' ) );
	}
}
