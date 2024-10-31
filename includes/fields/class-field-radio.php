<?php
/**
 * Field Radio
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration\Fields;

use RadiantRegistration\Fields\RadiantRegistration_Field;
use RadiantRegistration\Fields\Field_Checkbox;

/**
 * Field Radio class
 *
 * @package RadiantRegistration
 * @author  Rokibul
 */
class Field_Radio extends Field_Checkbox {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->name       = __( 'Radio', 'radiant-registration' );
		$this->input_type = 'radio_field';
		$this->icon       = 'dot-circle-o';
		$this->multiple   = false;
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
		$selected = isset( $field_settings['selected'] ) ? $field_settings['selected'] : '';
		?>
		<li <?php $this->print_list_attributes( $field_settings ); ?>>
			<?php $this->print_label( $field_settings, $form_id ); ?>
			<?php
			$class   =  $field_settings['layout'] == 'inline' ? 'show-inline ' : "show ";
			if ( $field_settings['photo_value'] ) {
				$class .= 'list_type_image';
			}
			?>
			<div class="<?php echo esc_attr($class); ?>" data-required="<?php echo esc_attr( $field_settings['required'] ) ?>" data-type="radio">
				<?php
				if ( $field_settings['options'] && count( $field_settings['options'] ) > 0 ) {
					foreach ( $field_settings['options'] as $value => $option ) { 
						?>
					<label>
						<?php
						printf( 
							'<input name="%s" class="%s" type="radio" value="%s" %s/>',
							esc_attr( $field_settings['name'] ),
							esc_attr( $field_settings['name'] ). '_'. esc_attr( $form_id ),
							esc_attr( $option['value'] ),
							( $option['value'] == $selected ) ? ' checked="checked"' : '',
						);
						if( $option['photo'] != "" && $field_settings['photo_value'] == 1 ) {
							printf( 
								'<img src="%s"/>',
								esc_attr( $option['photo'] )
							);
						} else {
							printf( '<span> %s </span>', esc_attr( $option['label'] ) );
						}
						?>
						</label>
						<?php
					}
				}
				$this->help_text( $field_settings );
				?>
			</div>
		</li>
		<?php
	}

	/**
	 * Get the field props
	 *
	 * @return array
	 */
	public function get_field_props() {
		$defaults = $this->default_attributes();
		$props    = array(
			'selected'    => '',
			'inline'      => 'no',
			'layout'      => 'default',
			'image'       => true,
			'sync_value'  =>  true,
			'photo_value' =>  false,
			'show_value'  => false,
			'options'     => array(
				array(
					'label' => 'option',
					'value' => 'option',
					'photo' => '',
				),
				array(
					'label' => 'option-2',
					'value' => 'option-2',
					'photo' => '',
				),
				array(
					'label' => 'option-3',
					'value' => 'option-3',
					'photo' => '',
				)
			),
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
		$val  = $post_data[$field['name']];

		return isset( $field['options'][ $val ] ) ? $field['options'][ $val ] : $val;
	}
}
