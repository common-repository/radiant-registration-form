<?php
/**
 * Field Password
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration\Fields;

use RadiantRegistration\Fields\RadiantRegistration_Field;
use RadiantRegistration\Fields\Traits\Textoption;

/**
 * Field Password class
 *
 * @package RadiantRegistration
 * @author  Rokibul
 */
class Field_Password extends RadiantRegistration_Field {
	use Textoption;

	/**
	 * Constructor
	 */
	function __construct() {
		$this->name       = __( 'Password', 'radiant-registration' );
		$this->input_type = 'password';
		$this->icon       = 'lock';
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
				printf('<input
					class="radiant_registration-el-form-control %s"
					id="%s"
					name="%s"
					type="password"
					data-required="%s"
					data-type="text"
					placeholder="%s"
					value="%s"
					size="%s"
					data-errormessage="%s"
				>',
				esc_attr( $field_settings['name'] . '_' . $form_id ),
				esc_attr( $field_settings['name'] . '_' . $form_id ),
				esc_attr( $field_settings['name'] ),
				esc_attr( $field_settings['required'] ),
				esc_attr( $field_settings['placeholder'] ),
				esc_attr( $field_settings['default'] ),
				esc_attr( $field_settings['size'] ),
				esc_attr( $field_settings['message'] )
			);
			$this->help_text( $field_settings ); ?>
		</li>
		<?php
	}

	/**
	 * Get field options setting
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$default_options = $this->get_default_option_settings( false , array('dynamic') );
		$settings        = $this->get_default_text_option_settings( false );

		$pass_settings = [
			array(
				'name'          => 'min_length',
				'title'         => __( 'Minimum password length', 'radiant-registration' ),
				'type'          => 'text',
				'section'       => 'advanced',
				'priority'      => 23,
			),

			array(
				'name'          => 'repeat_pass',
				'title'         => __( 'Password Re-type', 'radiant-registration' ),
				'type'          => 'checkbox',
				'options'       => array( 'yes' => __( 'Require Password repeat', 'radiant-registration' ) ),
				'is_single_opt' => true,
				'section'       => 'advanced',
				'priority'      => 24,
			),

			array(
				'name'          => 're_pass_label',
				'title'         => __( 'Re-type password label', 'radiant-registration' ),
				'type'          => 'text',
				'section'       => 'advanced',
				'priority'      => 25,
			),

			array(
				'name'          => 'pass_strength',
				'title'         => __( 'Password Strength Meter', 'radiant-registration' ),
				'type'          => 'checkbox',
				'options'       => array( 'yes' => __( 'Show password strength meter', 'radiant-registration' ) ),
				'is_single_opt' => true,
				'section'       => 'advanced',
				'priority'      => 26,
			),
		];

		return array_merge( $default_options, $settings, $pass_settings );
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$defaults = $this->default_attributes();
		$props    = array(
			'input_type'    => 'password',
			'required'      => 'no',
			'name'          => 'password',
			'is_meta'       => 'no',
			'help'          => '',
			'css'           => '',
			'placeholder'   => '',
			'default'       => '',
			'size'          => 40,
			'id'            => 0,
			'is_new'        => true,
			'min_length'    => 5,
			'repeat_pass'   => 'yes',
			're_pass_label' => 'Confirm Password',
			'pass_strength' => 'yes',
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
