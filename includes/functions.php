<?php
/**
 * Functions
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

/**
 * Get allowed extensions
 *
 * @return array
 */
function radiant_registration_allowed_extensions() {
	$extesions = array(
		'images' => array(
			'ext'   => 'jpg,jpeg,gif,png,bmp',
			'label' => __( 'Images', 'radiant-registration' ),
		),
		'audio'  => array(
			'ext'   => 'mp3,wav,ogg,wma,mka,m4a,ra,mid,midi',
			'label' => __( 'Audio', 'radiant-registration' ),
		),
		'video'  => array(
			'ext'   => 'avi,divx,flv,mov,ogv,mkv,mp4,m4v,divx,mpg,mpeg,mpe',
			'label' => __( 'Videos', 'radiant-registration' ),
		),
		'pdf'    => array(
			'ext'   => 'pdf',
			'label' => __( 'PDF', 'radiant-registration' ),
		),
		'office' => array(
			'ext'   => 'doc,ppt,pps,xls,mdb,docx,xlsx,pptx,odt,odp,ods,odg,odc,odb,odf,rtf,txt',
			'label' => __( 'Office Documents', 'radiant-registration' ),
		),
		'zip'    => array(
			'ext'   => 'zip,gz,gzip,rar,7z',
			'label' => __( 'Zip Archives', 'radiant-registration' ),
		),
		'exe'    => array(
			'ext'   => 'exe',
			'label' => __( 'Executable Files', 'radiant-registration' ),
		),
		'csv'    => array(
			'ext'   => 'csv',
			'label' => __( 'CSV', 'radiant-registration' ),
		),
	);

	return apply_filters( 'radiant_registration_allowed_extensions', $extesions );
}

/**
 * get default form settings
 *
 * @return array
 */
function radiant_registration_get_default_form_settings() {
	global $wp_roles;

	return apply_filters(
		'radiant_registration_get_default_form_settings',
		array(
			'message' => "successfully registerd",
			'new_user_status'	 => true,
			'user_notification'  => true,
            'admin_notification' => true,
            'notification_type'  => 'email_verification',
			'redirect_to' => 'same',
			'page_id'     => '',
			'pages'       => wp_list_pluck( get_pages(), 'post_title', 'ID' ),
			'role'        => 'subscriber',
			'roles'       => $wp_roles->role_names,
			'submit_text' => 'Register',

			'admin_email_subject'   => 'Admin Email Subject',
			'admin_email_body'      => 'Admin Email body',
			'verification_subject'  => 'Verification Email Subject',
			'verification_body'     => 'Verification Email body',
			'welcome_email_subject' => 'Welcome Email Subject',
			'welcome_email_body'    => 'Welcome Email Body',
		)
	);
}

/**
 * get format text
 *
 * @param string $content content.
 *
 * @return string
 */
function radiant_registration_format_text( $content ) {
	$content = wptexturize( $content );
	$content = convert_smilies( $content );
	$content = wpautop( $content );
	$content = make_clickable( $content );

	return $content;
}

/**
 * get insert form field
 *
 * @param int    $form_id  form_id.
 * @param array  $field    field.
 * @param string $field_id field_id.
 * @param int    $order    order.
 *
 * @return string
 */
function radiant_registration_insert_form_field( $form_id, $field = array(), $field_id = null, $order = 0 ) {
	$args = array(
		'post_type'    => 'registration_input',
		'post_parent'  => $form_id,
		'post_status'  => 'publish',
		'post_content' => maybe_serialize( wp_unslash( $field ) ),
		'menu_order'   => $order,
	);

	if ( $field_id ) {
		$args['ID'] = $field_id;
	}

	if ( $field_id ) {
		return wp_update_post( $args );
	} else {
		return wp_insert_post( $args );
	}
}

/**
 * get insert form field
 *
 * @param string $value value.
 *
 * @return string
 */
function radiant_registration_get_pain_text( $value ) {
	if ( is_serialized( $value ) ) {
		$value = unserialize( $value );
	}

	if ( is_array( $value ) ) {
		$string_value = array();

		if ( is_array( $value ) ) {
			foreach ( $value as $key => $single_value ) {
				if ( is_array( $single_value ) || is_serialized( $single_value ) ) {
					$single_value = radiant_registration_get_pain_text( $single_value );
				}

				$single_value = ucwords( str_replace( [ '_', '-' ], ' ', $key ) ) . ': ' . ucwords( $single_value );

				$string_value[] = $single_value;
			}

			$value = implode( ' | ', $string_value );
		}
	}

	$value = trim( wp_strip_all_tags( $value ) );

	return $value;
}

/**
 * get insert form field
 *
 * @param int      $form_id  form_id.
 * @param boolean  $new_window new_window.
 *
 * @return string
 */
function radiant_registration_get_form_preview_url( $form_id, $new_window = false ) {

	$url = add_query_arg(
		array(
			'radiant_registration_form_preview' => absint( $form_id ),
			'radiant_preview_nonce' => wp_create_nonce('radiant-preview'),
		),
		home_url()
	);

	if ( $new_window ) {
		$url = add_query_arg(
			array(
				'new_window' => 1,
			),
			$url
		);
	}

	return $url;
}

/**
 * get insert form field
 *
 * @param int    $user_id  user_id.
 * @param int    $attachment_id attachment_id.
 *
 * @return string
 */
function radiant_registration_update_avatar( $user_id, $attachment_id ) {
	$upload_dir   = wp_upload_dir();
	$relative_url = wp_get_attachment_url( $attachment_id );

	if ( function_exists( 'wp_get_image_editor' ) ) {
		// try to crop the photo if it's big
		$file_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $relative_url );

		// as the image upload process generated a bunch of images
		// try delete the intermediate sizes.
		$ext             = strrchr( $file_path, '.' );
		$file_path_w_ext = str_replace( $ext, '', $file_path );
		$small_url       = $file_path_w_ext . '-avatar' . $ext;
		$relative_url    = str_replace( $upload_dir['basedir'], $upload_dir['baseurl'], $small_url );

		$editor = wp_get_image_editor( $file_path );

		if ( ! is_wp_error( $editor ) ) {
			$avatar_size    = '100x100';
			$avatar_size    = explode( 'x', $avatar_size );
			$avatar_width   = $avatar_size[0];
			$avatar_height  = $avatar_size[1];

			$editor->resize( $avatar_width, $avatar_height, true );
			$editor->save( $small_url );

			// if the file creation successfull, delete the original attachment
			if ( file_exists( $small_url ) ) {
				wp_delete_attachment( $attachment_id, true );
			}
		}
	}

	// delete any previous avatar
	$prev_avatar = get_user_meta( $user_id, 'user_avatar', true );

	if ( ! empty( $prev_avatar ) ) {
		$prev_avatar_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $prev_avatar );

		if ( file_exists( $prev_avatar_path ) ) {
			unlink( $prev_avatar_path );
		}
	}

	// now update new user avatar
	update_user_meta( $user_id, 'user_avatar', $relative_url );
}

/**
 * get option
 *
 * @param int    $option  option.
 * @param array  $section section.
 * @param string $default default.
 *
 * @return string
 */
function radiant_registration_get_option( $option, $section, $default = '' ) {
	$options = get_option( $section );

	if ( isset( $options[ $option ] ) ) {
		return $options[ $option ];
	}

	return $default;
}

/**
 * get form settings
 *
 * @param int     $form_id form_id.
 * @param boolean $status status.
 *
 * @return string
 */
function radiant_registration_get_form_settings( $form_id, $status = true ) {
	return get_post_meta( $form_id, 'radiant_registration_form_settings', $status );
}

/**
 * get form fields
 *
 * @param int $form_id form_id.
 *
 * @return string
 */
function radiant_registration_get_form_fields( $form_id ) {
	$fields = get_children(
		array(
			'post_parent' => $form_id,
			'post_status' => 'publish',
			'post_type'   => 'radiant_registration_input',
			'numberposts' => '-1',
			'orderby'     => 'menu_order',
			'order'       => 'ASC',
		)
	);

	$form_fields = [];

	foreach ( $fields as $key => $content ) {
		$field = maybe_unserialize( $content->post_content );

		$field['id'] = $content->ID;

		// Add inline property for radio and checkbox fields
		$inline_supported_fields = [ 'radio', 'checkbox' ];

		if ( in_array( $field['input_type'], $inline_supported_fields, true ) ) {
			if ( ! isset( $field['inline'] ) ) {
				$field['inline'] = 'no';
			}
		}

		// Add 'selected' property
		$option_based_fields = [ 'select', 'multiselect', 'radio', 'checkbox' ];

		if ( in_array( $field['input_type'], $option_based_fields, true ) ) {
			if ( ! isset( $field['selected'] ) ) {
				if ( 'select' === $field['input_type'] || 'radio' === $field['input_type'] ) {
					$field['selected'] = '';
				} else {
					$field['selected'] = [];
				}
			}
		}

		// Add 'multiple' key for input_type:repeat
		if ( 'repeat' === $field['input_type'] && ! isset( $field['multiple'] ) ) {
			$field['multiple'] = '';
		}

		if ( 'recaptcha' === $field['input_type'] ) {
			$field['name']              = 'recaptcha';
			$field['enable_no_captcha'] = isset( $field['enable_no_captcha'] ) ? $field['enable_no_captcha'] : '';
		}

		$form_fields[] = apply_filters( 'radiant_registration-get-form-fields', $field );
	}

	return $form_fields;
}


/**
 * get settings
 *
 * @param int $key key.
 *
 * @return string
 */
function radiant_registration_get_settings( $key = '' ) {
    $settings = get_option( 'radiant_registration_settings', [] );

    if ( empty( $key ) ) {
        return $settings;
    }

    if ( isset( $settings[ $key ] ) ) {
        return $settings[ $key ];
    }
}
