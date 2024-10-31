<?php
/**
 * Contactum Field Checkbox
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration\Fields;

/**
 * Contactum Field class
 *
 * @package RadiantRegistration
 * @author  Rokibul
 */
abstract class RadiantRegistration_Field {

	/**
	 * Name of field
	 *
	 * @var string $name name.
	 */
	protected $name = '';

	/**
	 * Input type
	 *
	 * @var string $input_type  input_type.
	 */
	protected $input_type = '';

	/**
	 * Icon
	 *
	 * @var string $icon  icon.
	 */
	protected $icon = 'header';

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get type
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->input_type;
	}

	/**
	 * Get icon
	 *
	 * @return string
	 */
	public function get_icon() {
		return $this->icon;
	}

	/**
	 * Render field
	 *
	 * @param array $field_settings field_settings.
	 * @param int   $form_id form_id.
	 *
	 * @return void
	 */
	abstract public function render( $field_settings, $form_id );

	/**
	 * Get field option settings
	 *
	 * @return array
	 */
	abstract public function get_options_settings();

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	abstract public function get_field_props();

	/**
	 * Set full width
	 *
	 * @return boolean
	 */
	public function is_full_width() {
		return false;
	}

	/**
	 * Get validator
	 *
	 * @return boolean
	 */
	public function get_validator() {
		return false;
	}

	/**
	 * Get settings
	 *
	 * @return array
	 */
	public function get_js_settings() {

		$settings = [
			'template'      => $this->get_type(),
			'title'         => $this->get_name(),
			'icon'          => $this->get_icon(),
			'settings'      => $this->get_options_settings(),
			'field_props'   => $this->get_field_props(),
			'is_full_width' => $this->is_full_width(),
		];

	   	if ( $validator = $this->get_validator() ) {
			$settings['validator'] = $validator;
		}

		$settings['settings'][] = $this->get_conditional_field();

		return apply_filters( 'formsuite_field_get_js_settings', $settings );
	}

	/**
	 * Get default conditional properties
	 *
	 * @return array
	 */
	public function default_conditional_prop() {
		return array(
			'condition_status' => 'no',
			'cond_field'       => array(),
			'cond_operator'    => array( '=' ),
			'cond_option'      => array( __( '- select -', 'radiant-registration' ) ),
			'cond_logic'       => 'all',
		);
	}

	/**
	 * Get default attribute properties
	 *
	 * @return array
	 */
	public function default_attributes() {
		return [
			'template'     => $this->get_type(),
			'name'         => '',
			'label'        => $this->get_name(),
			'required'     => 'no',
			'message'      => 'this field is required',
			'id'           => 0,
			'width'        => 'large',
			'css'          => '',
			'placeholder'  => '',
			'default'      => '',
			'size'         => 40,
			'help'         => '',
			'is_meta'      => 'yes',
			'is_new'       => true,
			'contact_cond' => $this->default_conditional_prop(),
		];
	}

	/**
	 * Get default option settings
	 *
	 * @param boolean $is_meta is_meta.
	 * @param boolean $exclude exclude.
	 *
	 * @return array
	 */
	public static function get_default_option_settings( $is_meta = true, $exclude = [] ) {
		$common_properties = [
			[
				'name'      => 'label',
				'title'     => __( 'Field Label', 'radiant-registration' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 10,
				'help_text' => __( 'Enter a title of this field', 'radiant-registration' ),
			],

			[
				'name'      => 'help',
				'title'     => __( 'Help text', 'radiant-registration' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 20,
				'help_text' => __( 'Give the user some information about this field', 'radiant-registration' ),
			],

			[
				'name'    => 'required',
				'title'   => __( 'Required', 'radiant-registration' ),
				'type'    => 'required',
				'options' => [
					'yes' => __( 'Yes', 'radiant-registration' ),
					'no'  => __( 'No', 'radiant-registration' ),
				],
				'section'   => 'basic',
				'priority'  => 21,
				'default'   => 'no',
				'inline'    => true,
				'help_text' => __( 'Check this option to mark the field required. A form will not submit unless all required fields are provided.', 'radiant-registration' ),
			],

			[
				'name'    => 'width',
				'title'   => __( 'Field Size', 'radiant-registration' ),
				'type'    => 'radio',
				'options' => [
					'small'  => __( 'Small', 'radiant-registration' ),
					'medium' => __( 'Medium', 'radiant-registration' ),
					'large'  => __( 'Large', 'radiant-registration' ),
				],
				'section'  => 'advanced',
				'priority' => 21,
				'default'  => 'large',
				'inline'   => true,
			],

			[
				'name'      => 'css',
				'title'     => __( 'CSS Class Name', 'radiant-registration' ),
				'type'      => 'text',
				'section'   => 'advanced',
				'priority'  => 22,
				'help_text' => __( 'Provide a container class name for this field. Available classes: radiant_registration-col-half, radiant_registration-col-half-last, radiant_registration-col-one-third, radiant_registration-col-one-third-last', 'radiant-registration' ),
			],

		];


		if ( $is_meta ) {
			$common_properties[] = [
				'name'      => 'name',
				'title'     => __( 'Meta Key', 'radiant-registration' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 11,
				'help_text' => __( 'Name of the meta key this field will save to', 'radiant-registration' ),
			];
		}

		if ( count( $exclude ) ) {
			foreach ( $common_properties as $key => &$option ) {
				if ( in_array( $option['name'], $exclude ) ) {
					unset( $common_properties[$key] );
				}
			}
		}

		return $common_properties;
	}

	/**
	 * Get conditional field
	 *
	 * @return array
	 */
	public function get_conditional_field() {
		return array(
			'name'      => 'contact_cond',
			'title'     => __( 'Conditional Logic', 'radiant-registration' ),
			'type'      => 'conditional_logic',
			'section'   => 'advanced',
			'priority'  => 30,
			'help_text' => '',
		);
	}

	/**
	 * Print label
	 *
	 * @param object $field field.
	 * @param int    $form_id form_id.
	 *
	 * @return void
	 */
	public function print_label( $field, $form_id = 0 ) {
		?>
		<div class="radiant_registration-label"> <label for="<?php echo isset( $field['name'] ) ? esc_attr( $field['name'] ) . '_' . esc_attr( $form_id ) : 'cls'; ?>">
			<?php echo esc_html(  $field['label'] )  . wp_kses_post( $this->required( $field ) ) ; ?></label> </div>
		<?php
	}

	/**
	 * Print list attribute
	 *
	 * @param object $field field.
	 *
	 * @return void
	 */
	public function print_list_attributes( $field ) {
		$label      = isset( $field['label'] ) ? $field['label'] : '';
		$el_name    = !empty( $field['name'] ) ? $field['name'] : '';
		$class_name = !empty( $field['css'] ) ? ' ' . $field['css'] : '';
		$field_size = !empty( $field['width'] ) ? ' field-size-' . $field['width'] : '';
        $message = !empty( $field['message'] ) ? $field['message'] : '';

		printf( 'class="radiant_registration-el radiant_registration-%s%s%s" data-label="%s" data-errormessage="%s" ', esc_attr( $el_name ), esc_attr( $class_name ), esc_attr( $field_size ),
		esc_attr( $label ), esc_attr( $message )  );
	}

	/**
	 * Required
	 *
	 * @param object $field field.
	 *
	 * @return string
	 */
	public function required( $field ) {
		if ( isset( $field['required'] ) && $field['required'] === 'yes' ) {
			return '<span class="required">*</span>';
		}
	}

	/**
	 * Get help string
	 *
	 * @param object $field field.
	 *
	 * @return string
	 */
	public function help_text( $field ) {
		if ( empty( $field['help'] ) ) {
			return;
		}
		?>
		<span class="radiant_registration-help"><?php echo esc_attr( $field['help'] ); ?></span>
		<?php
	}

	/**
	 * Prepare entry
	 *
	 * @param array $field field.
	 * @param array $post_data post_data.
	 *
	 * @return string
	 */
	public function prepare_entry( $field, $post_data = [] ) {
		$value = !empty( $post_data [$field['name'] ] ) ? $post_data[$field['name']] : '';

		if ( is_array( $value ) ) {
			$entry_value = implode( ' | ', $post_data[ $field['name'] ] );
		} else {
			$entry_value = trim( $value );
		}

		return $entry_value;
	}
}
