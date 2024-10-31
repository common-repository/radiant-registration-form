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
 * Field Hidden class
 *
 * @package RadiantRegistration
 */
class Field_Hidden extends RadiantRegistration_Field {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Hidden', 'radiant-registration' );
		$this->input_type = 'hidden_field';
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
		$value = $field_settings['meta_value'];
		?>
	<li <?php $this->print_list_attributes( $field_settings ); ?> >
		<input type="hidden" name="<?php echo esc_attr( $field_settings['name'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
	</li>
   <?php }

	/**
	 * Get field option settings
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$settings = [
			[
				'name'      => 'name',
				'title'     => __( 'Meta Key', 'radiant-registration' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 10,
				'help_text' => __( 'Name of the meta key this field will save to', 'radiant-registration' ),
			],
			[
				'name'      => 'meta_value',
				'title'     => __( 'Meta Value', 'radiant-registration' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 11,
				'help_text' => __( 'Enter the meta value', 'radiant-registration' ),
			],
			[
				'name'          => 'dynamic',
				'title'         => '',
				'type'          => 'dynamic',
				'section'       => 'advanced',
				'priority'      => 23,
				'help_text'     => __( 'Check this option to allow field to be populated dynamically using hooks/query string/shortcode', 'radiant-registration' ),
			],
		];

		return $settings;
	}

	/**
	 * Get field properties
	 *
	 * @return array
	 */
	public function get_field_props() {
		$props = [
			'template'      => $this->get_type(),
			'name'          => '',
			'meta_value'    => '',
			'is_meta'       => 'yes',
			'id'            => 0,
			'is_new'        => true,
			'contact_cond'     => null,
		];

		return $props;
	}
}
