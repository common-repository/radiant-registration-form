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
 * Field Html class
 *
 * @package RadiantRegistration
 */
class Field_Html extends RadiantRegistration_Field {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Html', 'radiant-registration' );
		$this->input_type = 'html_field';
		$this->icon       = 'code';
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
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>
			<?php echo wp_kses_post( $field_settings['html'] ); ?>
		</li>
	<?php }

	/**
	 * Get field options setting
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$settings = [
			[
				'name'      => 'html',
				'title'     => __( 'Html Codes', 'radiant-registration' ),
				'type'      => 'textarea',
				'section'   => 'basic',
				'priority'  => 11,
				'help_text' => __( 'Paste your HTML codes, WordPress shortcodes will also work here', 'radiant-registration' ),
			],
			[
				'name'          => 'name',
				'title'         => __( 'Meta Key', 'radiant-registration' ),
				'type'          => 'text_meta',
				'section'       => 'basic',
				'priority'      => 12,
				'help_text'     => __( 'Name of the meta key this field will save to', 'radiant-registration' ),
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
			'template'  => $this->get_type(),
			'label'     => $this->get_name(),
			'html'      => sprintf( '%s', __( 'HTML Section', 'radiant-registration' ) ),
			'id'        => 0,
			'is_new'    => true
		];

		return $props;
	}
}
