<?php
/**
 * Field Number
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration\Fields;

use RadiantRegistration\Fields\RadiantRegistration_Field;

/**
 * Field Number class
 *
 * @package RadiantRegistration
 * @author  Rokibul
 */
class Field_Number extends  RadiantRegistration_Field {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Numeric', 'radiant-registration' );
		$this->input_type = 'number_field';
		$this->icon       = 'hashtag';
	}

	/**
	 * Render field
	 *
	 * @param array $field_settings field_settings.
	 * @param int   $form_id form_id.
	 *
	 * @return void
	 */
	public function render( $field_settings, $form_id ) {
		$value = $field_settings['default'];
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>

		<?php
				$this->print_label( $field_settings );
				printf('<input
						id="%s"
						type="number"
						class="radiant_registration-el-form-control %s"
						min="%s"
						max="%s"
						step="%s"
						name="%s"
						placeholder="%s"
						value="%s"
						size="%s"
						data-errormessage="%s"
						data-required="%s"
                        data-type="text"
					/>',
					esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ),
					esc_attr( $field_settings['name'] ).'_'. esc_attr( $form_id ),
					esc_attr($field_settings['min_value_field']),
					$field_settings['max_value_field'] == 0 ? '' : esc_attr($field_settings['max_value_field']),
					esc_attr( $field_settings['step_text_field'] ),
					esc_attr( $field_settings['name'] ),
					esc_attr( $field_settings['placeholder'] ),
					esc_attr( $value ),
					esc_attr( $field_settings['size'] ),
					esc_attr( $field_settings['message'] ),
					esc_attr( $field_settings['required'] ),
				);
				$this->help_text( $field_settings );
				?>
		</li>
		<?php
	}

	/**
	 * Get field options setting
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$default_options = $this->get_default_option_settings();

		$settings = array(
			array(
				'name'      => 'step_text_field',
				'title'     => __( 'Step', 'radiant-registration' ),
				'type'      => 'text',
				'variation' => 'number',
				'section'   => 'advanced',
				'priority'  => 9,
				'help_text' => '',
			),

			array(
				'name'      => 'min_value_field',
				'title'     => __( 'Min Value', 'radiant-registration' ),
				'type'      => 'text',
				'variation' => 'number',
				'section'   => 'advanced',
				'priority'  => 11,
				'help_text' => '',
			),

			array(
				'name'      => 'max_value_field',
				'title'     => __( 'Max Value', 'radiant-registration' ),
				'type'      => 'text',
				'variation' => 'number',
				'section'   => 'advanced',
				'priority'  => 13,
				'help_text' => '',
			),
		);

		return array_merge( $default_options, $settings );
	}

	/**
	 * Get the field props
	 *
	 * @return array
	 */
	public function get_field_props() {
		$defaults = $this->default_attributes();
		$props    = array(
			'step_text_field' => '0',
			'min_value_field' => '0',
			'max_value_field' => '0',
		);

		return array_merge( $defaults, $props );
	}
}
