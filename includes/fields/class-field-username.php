<?php
/**
 * Field UserName
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration\Fields;

use RadiantRegistration\Fields\RadiantRegistration_Field;
use RadiantRegistration\Fields\Traits\Textoption;

/**
 * Field UserName class
 *
 * @package RadiantRegistration
 * @author  Rokibul
 */
class Field_Username extends RadiantRegistration_Field {
	use Textoption;

	/**
	 * Constructor
	 */
	function __construct() {
		$this->name       = __( 'Username', 'radiant-registration' );
		$this->input_type = 'user_login';
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
		$value = $field_settings['default'];
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>
			<?php
				$this->print_label( $field_settings, $form_id );
				printf('<input
					class="radiant_registration-el-form-control %s"
					id="%s"
					name="%s"
					type="text"
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
	 * Get field option settings
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$default_options = $this->get_default_option_settings( false , array('dynamic') );
		$settings        = $this->get_default_text_option_settings( true );

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
			'input_type'  => 'text',
			'required'    => 'no',
			'name'        => 'user_login',
			'is_meta'     => 'no',
			'help'        => '',
			'css'         => '',
			'placeholder' => '',
			'default'     => '',
			'size'        => 40,
			'id'          => 0,
			'is_new'      => true,
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
		return sanitize_text_field( trim( $post_data[$field['name']] ) );
	}
}