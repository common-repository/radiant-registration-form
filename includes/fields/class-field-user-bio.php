<?php
/**
 * Field User Bio
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration\Fields;

use RadiantRegistration\Fields\RadiantRegistration_Field;
use RadiantRegistration\Fields\Traits\TextareaOption;

/**
 * Field User Bio class
 *
 * @package RadiantRegistration
 * @author  Rokibul
 */
class Field_User_Bio extends RadiantRegistration_Field {
	use TextareaOption;

	/**
	 * Constructor
	 */
	function __construct() {
		$this->name       = __( 'Biographical Info', 'radiant-registration' );
		$this->input_type = 'user_bio';
		$this->icon       = 'user';
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
		$value       = $field_settings['default'];?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>
			<?php $this->print_label( $field_settings, $form_id );?>
			<textarea
				class="radiant_registration-el-form-control textareafield <?php echo esc_attr( $field_settings['name'] ).'_'. esc_attr( $form_id ); ?>"
				id="<?php echo esc_attr( $field_settings['name'] ) . '_' . esc_attr( $form_id ); ?>"
				name="<?php echo esc_attr( $field_settings['name'] ); ?>"
				placeholder="<?php echo esc_attr( $field_settings['placeholder'] ); ?>"
				rows="<?php echo esc_attr($field_settings['rows']); ?>"
				cols="<?php echo esc_attr($field_settings['cols']); ?>"
				data-errormessage="<?php echo esc_attr( $field_settings['message'] ); ?>"
				date-type="textarea"
				data-required="<?php echo esc_attr( $field_settings['required'] ); ?>"
			>
			<?php echo esc_textarea( $value ) ?>
			</textarea>
			<?php $this->help_text( $field_settings ); ?>
		<li>
		<?php
	}

	/**
	 * Get field option settings
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$default_options = $this->get_default_option_settings( false , array( 'dynamic', 'width' ) );
		$settings        = $this->get_default_textarea_option_settings( true );

		return array_merge( $default_options, $settings );
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$defaults = $this->default_attributes();
		$props    = array(
			'input_type'       => 'textarea',
			'required'         => 'yes',
			'name'             => 'description',
			'is_meta'          => 'no',
			'help'             => '',
			'css'              => '',
			'rows'             => 5,
			'cols'             => 25,
			'placeholder'      => '',
			'default'          => '',
			'word_restriction' => '',
			'id'               => 0,
			'is_new'           => true,
		);

		return array_merge( $defaults, $props );
	}

	/**
	 * Prepare entry
	 *
	 * @param array $field field.
	 * @param array $post_data post_data.
	 *
	 * @return string
	 */
	public function prepare_entry( $field, $post_data = array() ) {
		return sanitize_text_field( trim( $post_data[ $field['name'] ] ) );
	}
}
