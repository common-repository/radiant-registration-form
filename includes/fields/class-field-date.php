<?php
/**
 * Field Date
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration\Fields;

use RadiantRegistration\Fields\RadiantRegistration_Field;

/**
 * Field Date class
 *
 * @package RadiantRegistration
 */
class Field_Date extends RadiantRegistration_Field {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Date', 'radiant-registration' );
		$this->input_type = 'date_field';
		$this->icon       = 'calendar';
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
		$value = '';
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>
			<?php
				$this->print_label( $field_settings );
			printf('<div class="radiant_registration-fields"> <input
					id="radiant_registration-date-%s"
					type="text"
					class="datepicker radiant_registration-el-form-control %s"
					data-required="%s"
					data-type="text"
					name="%s"
					placeholder="%s"
					value="%s"
					size="30"
					data-errormessage="%s"
				/> </div>',
				esc_attr( $field_settings['name'] ),
				esc_attr( $field_settings['name'] ).'_'. esc_attr($form_id),
				esc_attr($field_settings['required']),
				esc_attr( $field_settings['name'] ),
				esc_attr( $field_settings['name'].'_'.$form_id ),
				esc_attr( $value ),
				esc_attr( $field_settings['message'] )
			);
				$this->help_text( $field_settings );
			?>
		</li>
		<?php
			$name   = $field_settings['name'];
			$format = $field_settings["format"];

			$script = "jQuery('#radiant_registration-date-{$name}').flatpickr({
				dateFormat: '{$format}'
			});";

			wp_add_inline_script( 'radiant_registration-flatpickr', $script );
	}

	/**
	 * Get field options setting
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$default_options      = $this->get_default_option_settings();

		$settings = [

			[
				'name'          => 'format',
				'title'     => __( 'Date Format', 'radiant-registration' ),
				'type'          => 'select',
				'is_single_opt' => true,
				'options'       => [
					'm/d/Y'   => __( 'm/d/Y - (Ex: 04/28/2018)', 'radiant-registration' ),
					'm/d/y'   => __( 'm/d/Y - (Ex: 04/28/18)', 'radiant-registration' ),
					'd/m/Y'   => __( 'm/d/Y - (Ex: 28/04/2018)', 'radiant-registration' ),
					'd.m.Y'   => __( 'd.m.Y - (Ex: 28.04.2018)', 'radiant-registration' ),
					'm/d/Y'   => __( 'm/d/Y - (Ex: 04/28/2018)', 'radiant-registration' ),
					'y/m/d'   => __( 'y/m/d - (Ex: 28/04/18)', 'radiant-registration' ),
					'd-m-y'   => __( 'd-m-y - (Ex: 28-04-18)', 'radiant-registration' ),
					'h:i K'   => __( 'h:i K - (Ex: 08:55 PM)', 'radiant-registration' ),
					'H:i'   => __( 'H:i - (Ex: 20:55 )', 'radiant-registration' ),
					'd.m.Y H:i K' => __( 'd.m.Y H:i K- (Ex: 28.04.2018 20:55 PM)', 'radiant-registration' ),
					'd/m/Y H:i K' => __( 'd/m/Y H:i K- (Ex: 28/04/2018 20:55 PM)', 'radiant-registration' ),
					'd.m.Y H:i' => __( 'd.m.Y H:i - (Ex: 28.04.2018 20:55)', 'radiant-registration' ),
					'd/m/Y H:i' => __( 'd/m/Y H:i - (Ex: 28/04/2018 20:55)', 'radiant-registration' ),
					'H:i'   => __( 'H:i - (Ex: 28-04-18 )', 'radiant-registration' ),
				],
				'section'       => 'advanced',
				'priority'      => 24,
				'help_text'     => __('The date format', 'radiant-registration')
			],
		];

		return array_merge( $default_options, $settings );
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$defaults = $this->default_attributes();

		$props = [
			'format' => 'd/m/Y',
		];

		return array_merge( $defaults, $props );
	}
}
