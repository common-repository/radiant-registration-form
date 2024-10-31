<?php
/**
 * UserField
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration\Traits;

/**
 * UserField Trait
 *
 * @package RadiantRegistration
 */
trait UserField {

	/**
	 * Get Input Fields
	 * 
	 * @param array $form_fields form_fields.
	 * 
	 * @return array
	 */
	public function getInputFields( $form_fields ) {
		$ignore_lists = array( 'section_break', 'html' );
		$user_vars = $meta_vars = $taxonomy_vars = array();

		foreach ( $form_fields as $key => $value ) {

			if ( in_array( $value['input_type'], $ignore_lists ) ) {
				continue;
			}

			if ( isset( $value['is_meta'] ) && $value['is_meta'] === 'yes' ) {
				$meta_vars[] = $value;
				continue;
			}

			if ( $value['input_type'] === 'taxonomy' ) {
				if ( $value['name'] === 'category' ) {
					continue;
				}
				$taxonomy_vars[] = $value;
			} else {
				$post_vars[] = $value;
			}
		}

		return array( $post_vars, $taxonomy_vars, $meta_vars );
	}
}
