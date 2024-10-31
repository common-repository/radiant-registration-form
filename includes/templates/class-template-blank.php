<?php
/**
 * Template Blank
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration\Templates;

use RadiantRegistration\Templates\RadiantRegistration_Form_Template;

/**
 * Template Blank class
 *
 * @package RadiantRegistration
 */
class Template_Blank extends RadiantRegistration_Form_Template {

	/**
	 * Constructor
	 */
	public function __construct() {
		// parent::__construct();
		$this->enabled     = true;
		$this->title       = __( 'Blank Form', 'radiant-registration' );
		$this->description = __( 'Create a simple Blank form.', 'radiant-registration' );
		$this->image       = '';
		$this->category    = 'default';
	}

	/**
	 *  Get form fields
	 *
	 * @return empty | array
	 */
	public function get_form_fields() {
		return __return_empty_array();
	}
}
