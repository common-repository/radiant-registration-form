<?php
/**
 * Template Manager
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration;

use RadiantRegistration\Templates\Template_Blank;

/**
 *TemplateManager class
 *
 * @package RadiantRegistration
 */
class TemplateManager {
	/**
	 * Templates list
	 *
	 * @var array
	 */
	private $templates = array();

	/**
	 * Get templates
	 *
	 * @return array
	 */
	public function get_templates() {
		if ( ! empty( $this->templates ) ) {
			return $this->templates;
		}

		$this->register_templates();

		return $this->templates;
	}

	/**
	 * Get teamplate by type
	 *
	 * @param string $template_type template_type.
	 *
	 * @return array|boolean
	 */
	public function get_template( $template_type ) {
		$templates = $this->get_templates();

		if ( isset( $template_type, $templates ) ) {
			return $templates[ $template_type ];
		}

		return false;
	}

	/**
	 * Register template
	 *
	 * @return void
	 */
	private function register_templates() {
		$templates = array(
			'blank' => new Template_Blank(),
		);

		$this->templates = apply_filters( 'radiant_registration-form-templates', $templates );
	}

	/**
	 * Get groups
	 *
	 * @return array
	 */
	public function get_field_groups() {
		$before_custom_templates = apply_filters( 'radiant_registration-form-templates-section-before', array() );
		$groups                  = array_merge( $before_custom_templates, $this->get_custom_templates() );
		$groups                  = array_merge( $groups, $this->get_others_templates() );
		$after_custom_templates  = apply_filters( 'radiant_registration-form-templates-section-after', array() );
		$groups                  = array_merge( $groups, $after_custom_templates );

		return $groups;
	}

	/**
	 * Create template
	 *
	 * @param string $name name.
	 *
	 * @return int
	 */
	public function create( $name ) {
		if ( ! $template = $this->exists( $name ) ) {
			return;
		}

		$form_id = radiant_registration()->forms->create( $template->get_title(), $template->get_form_fields() );

		if ( is_wp_error( $form_id ) ) {
			return $form_id;
		}

		$meta_updates = array(
			'form_settings' => $template->get_form_settings(),
		);

		foreach ( $meta_updates as $meta_key => $meta_value ) {
			update_post_meta( $form_id, $meta_key, $meta_value );
		}

		return $form_id;
	}

	/**
	 * Display meta value
	 *
	 * @param string $name name.
	 *
	 * @return array|boolean
	 */
	public function exists( $name ) {
		if ( array_key_exists( $name, $this->get_templates() ) ) {
			return $this->templates[ $name ];
		}

		return false;
	}
}
