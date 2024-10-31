<?php
/**
 * Field Text Doc Comment
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration\Fields;

use RadiantRegistration\Fields\RadiantRegistration_Field;
use RadiantRegistration\Fields\Traits\Textoption;

/**
 * Field Text class
 * 
 * @package RadiantRegistration
 * @author  Rokibul
 */
class Field_Text extends RadiantRegistration_Field {
	use Textoption;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Text', 'radiant-registration' );
		$this->input_type = 'text_field';
		$this->icon       = 'text-width';
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
				$this->print_label( $field_settings, $form_id );

				printf(
					'<div class="radiant_registration-fields"> <input type="text" name="%s" class="radiant_registration-el-form-control %s" id="%s" placeholder="%s" data-required="%s" data-type="text" value="%s" size="%s" data-errormessage="%s" /> </div>',
					esc_attr( $field_settings['name'] ),
					esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ),
					esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ),
					esc_attr( $field_settings['placeholder'] ),
					esc_attr( $field_settings['required'] ),
					esc_attr( $value ),
					esc_attr( $field_settings['size'] ),
					esc_attr( $field_settings['message'] ),
				);

				$this->help_text( $field_settings );
			?>
		</li>
		<?php
	}

	/**
	 * Get field option settings
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$default_options      = $this->get_default_option_settings();
		$default_text_options = $this->get_default_text_option_settings( false );
		$check_duplicate      = array(
			array(
				'name'          => 'duplicate',
				'title'         => 'No Duplicates',
				'type'          => 'checkbox',
				'is_single_opt' => true,
				'options'       => array(
					'no' => __( 'Unique Values Only', 'radiant-registration' ),
				),
				'default'       => '',
				'section'       => 'advanced',
				'priority'      => 23,
				'help_text'     => __( 'Select this option to limit user input to unique values only. This will require that a value entered in a field does not currently exist in the entry database for that field.', 'radiant-registration' ),
			),
		);

		return array_merge( $default_options, $default_text_options, $check_duplicate );
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$defaults = $this->default_attributes();
		$props = array(
			'duplicate' => '',
		);

		return array_merge( $defaults, $props );
	}
}
