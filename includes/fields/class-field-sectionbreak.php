<?php
/**
 * Field Shortcode
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration\Fields;

use RadiantRegistration\Fields\RadiantRegistration_Field;

/**
 * Field SectionBreak class
 *
 * @package RadiantRegistration
 * @author  Rokibul
 */
class Field_SectionBreak extends RadiantRegistration_Field {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Section Break', 'radiant-registration' );
		$this->input_type = 'section_break';
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
		$description = isset( $field_settings['description'] ) ? $field_settings['description'] : '';
		$name        = isset( $field_settings['name'] ) ? $field_settings['name'] : '';
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>
			<div class="<?php echo 'section_' . esc_attr( $form_id ); ?> <?php echo esc_html( $name ) . '_' . esc_attr( $form_id ); ?>">
				<h2 class="section-title"><?php echo esc_attr( $field_settings['label'] ); ?></h2>
				<div class="section-details"><?php echo esc_attr( $description ); ?></div>
			</div>
		</li>
		<?php
	}

	/**
	 * Get field option settings
	 *
	 * @return array
	 */
	public function get_options_settings() {
		$settings = array(
			array(
				'name'      => 'label',
				'title'     => __( 'Title', 'radiant-registration' ),
				'type'      => 'text',
				'section'   => 'basic',
				'priority'  => 10,
				'help_text' => __( 'Title of the section', 'radiant-registration' ),
			),
			array(
				'name'      => 'name',
				'title'     => __( 'Meta Key', 'radiant-registration' ),
				'type'      => 'text_meta',
				'section'   => 'basic',
				'priority'  => 11,
				'help_text' => __( 'Name of the meta key this field will save to', 'radiant-registration' ),
			),
			array(
				'name'      => 'description',
				'title'     => __( 'Description', 'radiant-registration' ),
				'type'      => 'textarea',
				'section'   => 'basic',
				'priority'  => 12,
				'help_text' => __( 'Some details text about the section', 'radiant-registration' ),
			),
		);

		return $settings;
	}

	/**
	 * Get the field props
	 *
	 * @return array
	 */
	public function get_field_props() {
		$props = array(
			'template'    => $this->get_type(),
			'label'       => $this->get_name(),
			'description' => __( 'Some description about this section', 'radiant-registration' ),
			'id'          => 0,
			'is_new'      => true,
		);

		return $props;
	}
}
