<?php
/**
 * Field Textarea
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration\Fields;

use RadiantRegistration\Fields\RadiantRegistration_Field;
use RadiantRegistration\Fields\Traits\TextareaOption;

/**
 * Field Textarea class
 *
 * @package RadiantRegistration
 */
class Field_Textarea extends RadiantRegistration_Field {
	use TextareaOption;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Textarea', 'radiant-registration' );
		$this->input_type = 'textarea_field';
		$this->icon       = 'paragraph';
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
			<?php $this->print_label( $field_settings, $form_id );?>
			<div class="radiant_registration-fields">
				<textarea
					class="radiant_registration-el-form-control textareafield <?php echo esc_attr( $field_settings['name'] ).'_'. esc_attr( $form_id ); ?>"
					id="<?php echo esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ); ?>"
					name="<?php echo esc_attr( $field_settings['name'] ); ?>"
					placeholder="<?php echo esc_attr( $field_settings['placeholder'] ); ?>"
					rows="<?php echo esc_attr($field_settings['rows']); ?>"
					cols="<?php echo esc_attr($field_settings['cols']); ?>"
					data-required="<?php echo esc_attr( $field_settings['required'] ); ?>"
					data-type="textarea"
					data-errormessage="<?php echo esc_attr( $field_settings['message'] ); ?>"
				>
				<?php echo esc_textarea( $value ) ?>
				</textarea>
				<?php $this->help_text( $field_settings ); ?>
			</div>
		<li>
	<?php }

	/**
	 * Get field option settings
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$default_options      = $this->get_default_option_settings();
		$default_text_options = $this->get_default_textarea_option_settings();

		return array_merge( $default_options, $default_text_options );
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$defaults = $this->default_attributes();
		$props    = array(
			'rows' => 5,
			'cols' => 25,
		);

		return array_merge( $defaults, $props );
	}
}
