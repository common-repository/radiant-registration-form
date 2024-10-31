<?php
/*
Plugin Name: Radiant Registration
Plugin URI:
Description: Radiant Registration
Version: 1.0.0
Author: Md Rokibul islam
Author URI: https://profiles.wordpress.org/rokibul-islam/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: radiant-registration
Domain Path: languages
*/

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Radiant class
 *
 * @class Radiant The class that holds the entire Radiant plugin
 */
final class RadiantRegistration {

    /**
     * Version 
     * 
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Holds various class instances
     * 
     * @var array
     */
    private $container = [];

    /**
	 * Constructor
	 */
    public function __construct() {
        $this->define_constants();
        add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
    }

    /**
	 * Initializes the RadiantRegistration class
	 *
	 * @return object
	 */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new Self();
        }

        return $instance;
    }

	/**
	 * Magic Method getter to bypass referencing objects
	 *
	 * @param string $prop prop.
	 *
	 * @return void
	 */
	public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
			return $this->container[ $prop ];
		}

		return $this->{$prop};
	}

	/**
	 * Check isset properties
	 *
	 * @param string $prop prop.
	 *
	 * @return boolean
	 **/
	public function __isset( $prop ) {
		return isset( $this->{$prop} ) || isset( $this->container[ $prop ] );
	}

    /**
	 *  Define Constants
	 *
	 * @return void
	 */
    public function define_constants() {
        define( 'RADIANT_REGISTRATION_VERSION', $this->version );
        define( 'RADIANT_REGISTRATION_SEPARATOR', ' | ');
        define( 'RADIANT_REGISTRATION_FILE', __FILE__ );
        define( 'RADIANT_REGISTRATION_ROOT', __DIR__ );
        define( 'RADIANT_REGISTRATION_PATH', dirname( RADIANT_REGISTRATION_FILE ) );
        define( 'RADIANT_REGISTRATION_INCLUDES', RADIANT_REGISTRATION_PATH . '/includes' );
        define( 'RADIANT_REGISTRATION_URL', plugins_url( '', RADIANT_REGISTRATION_FILE ) );
        define( 'RADIANT_REGISTRATION_ASSETS', RADIANT_REGISTRATION_URL . '/assets' );
    }

    /**
     * Load the plugin after all plugis are loaded
     *
     * @return void
     */
    public function init_plugin() {
        $this->includes();
        $this->init_classes();
        do_action( 'radiant_registration_loaded' );
    }

    /**
	 * Include all the required files
	 *
	 * @return void
	 */
    public function includes() {
        require_once RADIANT_REGISTRATION_INCLUDES . '/field-trait.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-admin-template.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-admin.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-admin-form-handler.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-ajax.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-assets.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-installer.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-field-manager.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-form-manager.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-form-preview.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-template-manager.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-forms-list.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-form.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-frontend.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-profile.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/class-smarttags.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/functions.php';
        
        // fields.
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/field-trait.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-abstract-fields.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-checkbox.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-text.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-dropdown.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-multidropdown.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-hidden.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-sectionbreak.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-number.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-username.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-first-name.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-last-name.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-display-name.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-nickname.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-user-email.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-user-url.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-user-bio.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-password.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-radio.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-url.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-email.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-name.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-textarea.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-textarea.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-date.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/fields/class-field-html.php';
    
        // template.
        require_once RADIANT_REGISTRATION_INCLUDES . '/templates/class-abstract-template.php';
        require_once RADIANT_REGISTRATION_INCLUDES . '/templates/class-template-blank.php';
    }

    /**
	 * Initilize classes
	 *
	 * @return void
	 */
    public function init_classes() {

        if ( is_admin() ) {
            $this->container['admin']              = new RadiantRegistration\Admin();
            $this->container['admin_template']     = new RadiantRegistration\Admin_Template();
            $this->container['admin_form_handler'] = new RadiantRegistration\Admin_Form_Handler();
        }

        $this->container['assets']    = new RadiantRegistration\Assets();
        $this->container['fields']    = new RadiantRegistration\FieldManager();
        $this->container['forms']     = new RadiantRegistration\FormManager();
        $this->container['templates'] = new RadiantRegistration\TemplateManager();
        $this->container['installer'] = new RadiantRegistration\Installer();
        $this->container['ajax']      = new RadiantRegistration\Ajax();
        $this->container['frontend']  = new RadiantRegistration\Frontend();
        $this->container['preview']   = new RadiantRegistration\Form_Preview();
        $this->container['smarttags'] = new RadiantRegistration\SmartTags();
    }
}

/**
 * Call main class
 *
 * @return void
 */
function radiant_registration() {
    return RadiantRegistration::init();
}

radiant_registration();
