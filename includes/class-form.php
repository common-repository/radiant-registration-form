<?php
/**
 * Form Template
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration;

use RadiantRegistration\EntryManager;
use WP_Error;

/**
 * Form class
 *
 * @package RadiantRegistration
 */
class Form {

	/**
	 * Form id
	 *
	 * @var int
	 */
	public $id = 0;

	/**
	 * Form name
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Form data
	 *
	 * @var array
	 */
	public $data;

	/**
	 * Form fields
	 *
	 * @var array
	 */
	public $form_fields = array();

	/**
	 * Constructor
	 *
	 * @param string $form form.
	 *
	 */
	public function __construct( $form = null ) {
		if ( is_numeric( $form ) ) {
			$the_post = get_post( $form );

			if ( $the_post ) {
				$this->id   = $the_post->ID;
				$this->name = $the_post->post_title;
				$this->data = $the_post;
			}
		} elseif ( is_a( $form, 'WP_Post' ) ) {
			$this->id   = $form->ID;
			$this->name = $form->post_title;
			$this->data = $form;
		}
	}

	/**
	 * Get id
	 *
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get fields
	 *
	 * @return array
	 */
	public function getFields() {
		$form_fields = array();

		$fields = get_children(
			array(
				'post_parent' => $this->id,
				'post_status' => 'publish',
				'post_type'   => 'registration_input',
				'numberposts' => '-1',
				'orderby'     => 'menu_order',
				'order'       => 'ASC',
			)
		);

		foreach ( $fields as $key => $content ) {
			$field = maybe_unserialize( $content->post_content );

			if ( empty( $field['template'] ) ) {
				continue;
			}

			$field['id']   = $content->ID;
			$form_fields[] = $field;
		}

		return $form_fields;
	}

	/**
	 * has field values
	 *
	 * @param array $field_template field_template.
	 *
	 * @return boolean
	 */
	public function hasField( $field_template ) {
		foreach ( $this->getFields() as $key => $field ) {
			if ( isset( $field['template'] ) && $field['template'] === $field_template ) {
				return true;
			}
		}
	}

	/**
	 * Get field values
	 *
	 * @return array
	 */
	public function getFieldValues() {
		$values = [];
		$fields = $this->getFields();

		if ( !$fields ) {
			return $values;
		}

		$ignore_fields  = apply_filters( 'ignore_fields_list', [ 'recaptcha', 'section_break' ] );
		$options_fields = apply_filters( 'option_fields_list', [ 'dropdown_field', 'radio_field', 'multiple_select', 'checkbox_field' ] );

		foreach ( $fields as $field ) {

			if ( in_array( $field['template'], $ignore_fields ) ) {
				continue;
			}

			if ( !isset( $field['name'] ) ) {
				continue;
			}

			$value = [
				'label' => isset( $field['label'] ) ? $field['label'] : '',
				'type'  => $field['template'],
			];

			// put options if this is an option field
			if ( in_array( $field['template'], $options_fields ) ) {
				$value['options'] = $field['options'];
			}

			$values[ $field['name'] ] = array_merge( $field, $value );
		}

		return apply_filters( 'radiant_registration_get_field_values', $values );
	}

	/**
	 * Get Settings
	 *
	 * @return array
	 */
	public function getSettings() {
		$settings = get_post_meta( $this->id, 'form_settings', true );
		$default  = radiant_registration_get_default_form_settings();

		return  array_merge( $default, $settings );
	}

	public function isSubmissionOpen() {
		$settings = $this->get_settings();

		$needs_login  = ( isset( $settings['require_login'] ) && $settings['require_login'] == 'true' ) ? true : false;
		$has_limit    = ( isset( $settings['limit_entries'] ) && $settings['limit_entries'] == 'true' ) ? true : false;
		$is_scheduled = ( isset( $settings['schedule_form'] ) && $settings['schedule_form'] == 'true' ) ? true : false;

		if ( $this->data->post_status != 'publish' ) {
			return new WP_Error( 'needs-publish', __( 'The form is not published yet.', 'radiant-registration' ) );
		}

		if ( $needs_login && !is_user_logged_in() ) {
			return new WP_Error( 'needs-login', $settings['req_login_message'] );
		}

		if ( $has_limit ) {
			$limit        = (int) $settings['limit_number'];
			$form_entries = $this->num_form_entries();

			if ( $limit <= $form_entries ) {
				return new WP_Error( 'entry-limit', $settings['limit_message'] );
			}
		}

		if ( $is_scheduled ) {
			$start_time   = strtotime( $settings['schedule_start'] );
			$end_time     = strtotime( $settings['schedule_end'] );
			$current_time = current_time( 'timestamp' );

			// too early?
			if ( $current_time < $start_time ) {
				return new WP_Error( 'form-pending', $settings['sc_pending_message'] );
			} elseif ( $current_time > $end_time ) {
				return new WP_Error( 'form-expired', $settings['sc_expired_message'] );
			}
		}

		return apply_filters( 'radiant_registration_is_submission_open', true, $settings, $this );
	}

	/**
	 * Prepare entries
	 *
	 * @param array $post_data post_data.
	 *
	 * @return array
	 */
	public function prepare_entries( $args = [] ) {
		$fields      = radiant_registration()->fields->getFields();
		$form_fields = $this->getFields();
		$entry_fields = [];

		$ignore_list  = [ 'recaptcha', 'section_break', 'step_start' ];

		foreach ( $form_fields as $field ) {

			if ( in_array( $field['template'], $ignore_list ) ) {
				continue;
			}

			if ( !array_key_exists( $field['template'], $fields ) ) {
				continue;
			}

			$field_class = $fields[ $field['template'] ];
			$entry_fields[ $field['name'] ] = $field_class->prepare_entry( $field );
		}

		return $entry_fields;
	}

	/**
	 * Is Pending Form
	 *
	 * @param string $scheduleStart scheduleStart.
	 *
	 * @return boolean
	 */
	public function isPendingForm( $scheduleStart ) {
		$currentTime = current_time( 'timestamp' );
		$startTime   = strtotime( $scheduleStart );

		if ( $currentTime < $startTime ) {
			return true;
		}

		return false;
	}

	/**
	 * Is Expired Form
	 *
	 * @param string $scheduleEnd scheduleEnd.
	 *
	 * @return boolean
	 */
	public function isExpiredForm( $scheduleEnd ) {
		$currentTime = current_time( 'timestamp' );
		$endTime     = strtotime( $scheduleEnd );

		if ( $currentTime > $endTime ) {
			return true;
		}

		return false;
	}

	/**
	 * Is Open Form
	 *
	 * @param string $scheduleStart scheduleStart.
	 * @param string $scheduleEnd   scheduleEnd.
	 *
	 * @return boolean
	 */
	public function isOpenForm( $scheduleStart, $scheduleEnd ) {
		$currentTime = current_time( 'timestamp' );
		$startTime   = strtotime( $scheduleStart );
		$endTime     = strtotime( $scheduleEnd );

		if ( $currentTime > $startTime && $currentTime < $endTime ) {
			return true;
		}

		return false;
	}

	/**
	 * Is Form Status Chanage
	 *
	 * @param string $formSettings formSettings.
	 * @param string $entries      entries.
	 *
	 * @return boolean
	 */
	public function isFormStatusClosed( $formSettings, $entries ) {

		if ( $formSettings['schedule_form'] === 'true' && $this->isPendingForm( $formSettings['schedule_start'] ) ) {
			return true;
		}

		if ( $formSettings['schedule_form'] === 'true' && $this->isExpiredForm( $formSettings['schedule_end'] ) ) {
			return true;
		}

		if ( $formSettings['limit_entries'] === 'true' && $entries >= $formSettings['limit_number'] ) {
			return true;
		}

		return false;
	}
}
