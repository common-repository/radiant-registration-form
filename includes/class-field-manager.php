<?php
/**
 * Field Manager Template
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration;

use RadiantRegistration\Fields\Field_Checkbox;
use RadiantRegistration\Fields\Field_Email;
use RadiantRegistration\Fields\Field_Name;
use RadiantRegistration\Fields\Field_Radio;
use RadiantRegistration\Fields\Field_Text;
use RadiantRegistration\Fields\Field_Textarea;
use RadiantRegistration\Fields\Field_Url;
use RadiantRegistration\Fields\Field_Date;
use RadiantRegistration\Fields\Field_Dropdown;
use RadiantRegistration\Fields\Field_MultiDropdown;
use RadiantRegistration\Fields\Field_Html;
use RadiantRegistration\Fields\Field_Hidden;
use RadiantRegistration\Fields\Field_SectionBreak;
use RadiantRegistration\Fields\Field_Number;

// user field
use RadiantRegistration\Fields\Field_Display_Name;
use RadiantRegistration\Fields\Field_First_Name;
use RadiantRegistration\Fields\Field_Last_Name;
use RadiantRegistration\Fields\Field_Password;
use RadiantRegistration\Fields\Field_User_Bio;
use RadiantRegistration\Fields\Field_User_Email;
use RadiantRegistration\Fields\Field_User_Url;
use RadiantRegistration\Fields\Field_Username;
use RadiantRegistration\Fields\Field_Nickname;

/**
 * FieldManager class
 *
 * @package RadiantRegistration
 */
class FieldManager {

	/**
	 *  Store fields
	 *
	 * @var array
	 */
	private $fields = array();

	/**
	 * Get Fields
	 *
	 * @return array
	 */
	public function getFields() {
		if ( ! empty( $this->fields ) ) {
			return $this->fields;
		}

		$this->register_field_types();

		return $this->fields;
	}

	/**
	 * Get field
	 *
	 * @param string $field_type field_type.
	 *
	 * @return void
	 */
	public function getField( $field_type ) {
		$fields = $this->getFields();

		if ( array_key_exists( $field_type, $fields ) ) {
			return $fields[ $field_type ];
		}

		return false;
	}

	/**
	 * Register field types
	 *
	 * @return void
	 */
	private function register_field_types() {
		$fields = [
			'checkbox_field'  => new Field_Checkbox(),
			'email_field'     => new Field_Email(),
			'name_field'      => new Field_Name(),
			'radio_field'     => new Field_Radio(),
			'text_field'      => new Field_Text(),
			'textarea_field'  => new Field_Textarea(),
			'url_field'       => new Field_Url(),
			'date_field'      => new Field_Date(),
			'dropdown_field'  => new Field_Dropdown(),
			'multiple_select' => new Field_MultiDropdown(),
			'html_field'      => new Field_Html(),
			'hidden_field'    => new Field_Hidden(),
			'section_break'   => new Field_SectionBreak(),
			'number_field'    => new Field_Number(),
			'user_login'      => new Field_Username(),
			'first_name'      => new Field_First_Name(),
			'last_name'       => new Field_Last_Name(),
			'display_name'    => new Field_Display_Name(),
			'nickname'        => new Field_Nickname(),
			'user_email'      => new Field_User_Email(),
			'user_url'        => new Field_User_Url(),
			'user_bio'        => new Field_User_Bio(),
			'password'        => new Field_Password()
		];

		$this->fields = apply_filters( 'radiant_registration_form_fields', $fields );
	}

	/**
	 * Get field groups
	 *
	 * @return array
	 */
	public function get_field_groups() {
		$before_custom_fields = apply_filters( 'radiant_registration_form_fields_section_before', array() );
		$groups               = array_merge( $before_custom_fields, $this->get_profile_fields() );
		$groups               = array_merge( $groups, $this->get_custom_fields() );
		$groups               = array_merge( $groups, $this->get_others_fields() );
		$after_custom_fields  = apply_filters( 'radiant_registration_form_fields_section_after', array() );
		$groups               = array_merge( $groups, $after_custom_fields );

		return $groups;
	}

	/**
	 * Get Profile fields
	 *
	 * @return array
	 */
	public function get_profile_fields() {
		$profile_fields = apply_filters(
			'radiant_registration_form_fields_profile_fields',
			array(
				'user_login',
				'first_name',
				'last_name',
				'display_name',
				'nickname',
				'user_email',
				'user_url',
				'user_bio',
				'password'
			)
		);

		return array(
			array(
				'title'  => __( 'Profile Fields', 'radiant-registration' ),
				'id'     => 'profile-fields',
				'fields' => $profile_fields,
				'show'   => true
			)
		);
	}

	/**
	 * Get custom fields
	 *
	 * @return array
	 */
	private function get_custom_fields() {
		$fields = apply_filters(
			'radiant_registration_form_fields_custom_fields',
			array(
				'text_field',
				'textarea_field',
				'url_field',
				'name_field',
				'email_field',
				'checkbox_field',
				'radio_field',
				'date_field',
				'dropdown_field',
				'multiple_select',
				'hidden_field',
				'number_field'
			)
		);

		return array(
			array(
				'title'  => __( 'Custom Fields', 'radiant-registration' ),
				'id'     => 'custom-fields',
				'fields' => $fields,
				'show'   => true
			),
		);
	}
	/**
	 * Get other fields
	 *
	 * @return array
	 */
	private function get_others_fields() {
		$fields = apply_filters(
			'radiant_registration_form_fields_others_fields',
			array(
				'section_break',
				'html_field'
			)
		);

		return array(
			array(
				'title'  => __( 'Others', 'radiant-registration' ),
				'id'     => 'others',
				'fields' => $fields,
				'show'   => true
			),
		);
	}

	/**
	 * Get settings
	 *
	 * @return array
	 */
	public function get_js_settings() {
		$fields = $this->getFields();

		$js_array = array();

		if ( $fields ) {
			foreach ( $fields as $type => $object ) {
				if ( is_object( $object ) ) {
					$js_array[ $type ] = $object->get_js_settings();
				}
			}
		}

		return $js_array;
	}

	/**
	 * Render fields
	 *
	 * @param array $fields  fields.
	 * @param int   $form_id form_id.
	 * @param array $atts    atts.
	 *
	 * @return void
	 */
	public function render_fields( $fields, $form_id, $atts = array() ) {
		if ( empty( $fields ) ) {
			return;
		}

		foreach ( $fields as $field ) {
			if ( ! $field_object = $this->getField( $field['template'] ) ) {
				continue;
			}

			$field_object->render( $field, $form_id );
		}
	}

	/**
	 * has submit fields check
	 *
	 * @param array $fields  fields.
	 * @param int   $form_id form_id.
	 * @param array $atts    atts.
	 *
	 * @return void
	 */
	public function hassubmit_fields( $fields, $form_id, $atts = array() ) {
		if ( empty( $fields ) ) {
			return false;
		}

		foreach ( $fields as $field ) {
			if ( $field['template'] === 'submit_field' ) {
				return true;
			}
		}

		return false;
	}
}
