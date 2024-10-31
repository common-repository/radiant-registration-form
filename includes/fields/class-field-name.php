<?php
/**
 * Field Display Name
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration\Fields;

use RadiantRegistration\Fields\RadiantRegistration_Field;

/**
 * Field Display Name class
 *
 * @package RadiantRegistration
 * @author  Rokibul
 */
class Field_Name extends RadiantRegistration_Field {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Name', 'radiant-registration' );
		$this->input_type = 'name_field';
		$this->icon       = 'user-o';
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
		if ( isset( $field_settings['auto_populate'] ) && $field_settings['auto_populate'] == 'yes' && is_user_logged_in() ) {
			return;
		}
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>

		<div class="radiant_registration-fields">
				<div class="name-container">
					<div class="first-name">
						<?php
						if( $field_settings['first_name']['show_field'] == 1) {
								printf('<label class="radiant_registration-form-sub-label"> %s </label>', 'First');
								echo wp_kses_post( $this->required( $field_settings['first_name'] ) );
								printf('<input
									name="%s[first]"
									type="text"
									placeholder="%s"
									value="%s"
									size="40"
									autocomplete="family-name",
									data-required="%s"
									data-type="text"
									data-errormessage="%s"
									class="radiant_registration-el-form-control"
								>',
									esc_attr( $field_settings['name'] ),
									esc_attr( $field_settings['first_name']['placeholder'] ),
									esc_attr( $field_settings['first_name']['default'] ),
									esc_attr( $field_settings['required'] ),
									esc_attr( $field_settings['message'] )
								);
						} ?>
					</div>

					<div class="middle-name">
						<?php
						if( $field_settings['middle_name']['show_field'] == 1) {
							printf('<label class="radiant_registration-form-sub-label"> %s </label>', 'Middle');
							echo wp_kses_post( $this->required( $field_settings['last_name'] ) );
							printf(
								'<input name="%s[middle]"
								type="text"
								placeholder="%s"
								value="%s"
								size="40"
								autocomplete="additional-name"
								class="radiant_registration-el-form-control"
								/>',
								esc_attr( $field_settings['name'] ),
								esc_attr( $field_settings['middle_name']['placeholder'] ),
								esc_attr( $field_settings['middle_name']['default'] )
							);
						}
						?>
					</div>

					<div class="last-name">
						<?php
						if( $field_settings['last_name']['show_field'] == 1) {
							printf( '<label class="radiant_registration-form-sub-label"> %s </label>', 'Last' );
							echo wp_kses_post( $this->required( $field_settings['last_name'] ) );
							printf('<input
								name="%s[last]"
								type="text"
								placeholder="%s"
								value="%s"
								size="40"
								autocomplete="family-name"
								class="radiant_registration-el-form-control"
							>',
								esc_attr( $field_settings['name'] ),
								esc_attr( $field_settings['last_name']['placeholder'] ),
								esc_attr( $field_settings['last_name']['default'] )
							);
						}
						?>
					</div>

				</div>

			</div>

				<?php
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
		$name_settings = array(
			array(
				'name'          => 'auto_populate',
				'title'         => 'Auto-populate name for logged users',
				'type'          => 'checkbox',
				'is_single_opt' => true,
				'options'       => array(
					'yes'   => __( 'Auto-populate Name', 'radiant-registration' ),
				),
				'default'       => '',
				'section'       => 'advanced',
				'priority'      => 23,
				'help_text'     => __( 'If a user is logged into the site, this name field will be auto-populated with his first-last/display name. And form\'s name field will be hidden.', 'radiant-registration' ),
			),
			array(
				'name'      => 'sub-labels',
				'title'     => __( 'Label', 'radiant-registration' ),
				'type'      => 'name',
				'section'   => 'advanced',
				'priority'  => 21,
				'help_text' => __( 'Select format to use for the name field', 'radiant-registration' ),
			),
			array(
				'name'          => 'hide_subs',
				'title'         => '',
				'type'          => 'checkbox',
				'is_single_opt' => true,
				'options'       => [
					'true'   => __( 'Hide Sub Labels', 'radiant-registration' ),
				],
				'section'       => 'advanced',
				'priority'      => 23,
				'help_text'     => '',
			),
			array(
				'name'          => 'inline',
				'title'         => __( 'Show in inline list', 'radiant-registration' ),
				'type'          => 'radio',
				'options'       =>  array(
					'yes'   => __( 'Yes', 'radiant-registration' ),
					'no'    => __( 'No', 'radiant-registration' ),
				),
				'default'       => 'no',
				'inline'        => true,
				'section'       => 'advanced',
				'priority'      => 23,
				'help_text'     => __( 'Show this option in an inline list', 'radiant-registration' ),
			),
		);

		return array_merge( $default_options, $name_settings );
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$defaults = $this->default_attributes();
		$props    = array(
			'first_name' => array(
				'message'     => '',
				'show_field'  => true,
				'required'    => false,
				'label'       => 'Firstname',
				'placeholder' => '',
				'default'     => '',
				'sub'         => __( 'FirstName', 'radiant-registration' ),
			),
			'middle_name' => array(
				'message'     => '',
				'show_field'  => true,
				'required'    => false,
				'label'       => 'Middlename',
				'placeholder' => '',
				'default'     => '',
				'sub'         => __( 'MiddleName', 'radiant-registration' ),
			),
			'last_name' => array(
				'message'     => '',
				'show_field'  => true,
				'required'    => false,
				'label'       => 'Lastname',
				'placeholder' => '',
				'default'     => '',
				'sub'         => __( 'LastName', 'radiant-registration' ),
			),
		);

		return array_merge( $defaults, $props );
	}
}
