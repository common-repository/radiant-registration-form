<?php
/**
 * Ajax Template
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration;

use RadiantRegistration\Traits\UserField;
use WP_Error;

/**
 * Ajax class
 *
 * @package RadiantRegistration
 */
class Ajax {

	use UserField;

	public function __construct() {
		add_action( 'wp_ajax_save_registration_form', array( $this, 'save_registration_form' ) );
		add_action( 'wp_ajax_nopriv_radiant_registration_frontend_submit', array( $this, 'user_register' ) );
	}

	/**
	 * register user
	 *
	 * @return void
	 */
	public function user_register() {
		
		if ( ! isset( $_POST['radiant_registration_form_frontend_nonce'] ) || ! wp_verify_nonce( 
			sanitize_text_field( wp_unslash ( $_POST['radiant_registration_form_frontend_nonce'] ) ), 'radiant_registration_form_frontend' ) ) {
			wp_send_json_error( __( 'Unauthorized operation', 'radiant-registration' ) );
		}

		@header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
		
		$form_id       = isset( $_POST['form_id'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['form_id'] ) ) ) : 0;
		$page_id       = isset( $_POST['page_id'] ) ? intval( sanitize_text_field( wp_unslash( $_POST['page_id'] ) ) ) : 0;
		$form          = radiant_registration()->forms->get( $form_id );
		$form_settings = $form->getSettings();
		$form_fields   = $form->getFields();

		list( $user_vars, $taxonomy_vars, $meta_vars )  = $this->getInputFields( $form_fields );

		$has_username_field = false;
		$username           = '';
		$user_email         = '';
		$firstname          = '';
		$lastname           = '';
		$password_field     = true;

		// don't let to be registered if no email address given.
		if( ! isset( $_POST['user_email'] ) || empty( $_POST['user_email'] ) ) {
			$this->send_error( __( 'An Email address is required', 'radiant-registration' ) );
		}

		// if any username given, check if it exists.
		if ( $this->search( $user_vars, 'name', 'user_login' )) {
			$has_username_field = true;
			$username = sanitize_user( sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) );

			if ( username_exists( $username ) ) {
				$username_error = __( 'Username already exists.', 'radiant-registration' );
				$this->send_error( apply_filters( 'radiant_registration_duplicate_username_error', $username_error, $form_settings ) );
			}
		}

		// if any email address given, check if it exists.
		if ( $this->search( $user_vars, 'name', 'user_email' )) {
			$user_email = sanitize_text_field( wp_unslash( $_POST['user_email'] ) );
			if ( email_exists( $user_email ) ) {
				$this->send_error( __( 'E-mail address already exists.', 'radiant-registration' ) );
			}
		}

		// if there isn't any username field in the form, lets guess a username.
		if ( ! $has_username_field ) {
			$username = $this->guess_username( $user_email );
		}

		if ( ! validate_username( $username ) ) {
			$this->send_error( __( 'Username is not valid', 'radiant-registration' ) );
		}

		// verify password
		if ( $pass_element = $this->search($user_vars, 'name', 'password' ) ) {
			$pass_element    = current( $pass_element );
			$password        = sanitize_text_field( wp_unslash( $_POST['password'] ) );
			$password_repeat = isset( $_POST['pass2'] ) ? sanitize_text_field( wp_unslash( $_POST['pass2'] ) ) : false;

			// min length check
			if ( strlen( $password ) < intval( $pass_element['min_length'] ) ) {
				$this->send_error( sprintf( __( 'Password must be %s character long', 'radiant-registration' ), $pass_element['min_length'] ) );
			}

			// repeat password check
			if ( ( $password !== $password_repeat ) && $password_repeat !== false ) {
				$this->send_error( __( 'Password didn\'t match', 'radiant-registration' ) );
			}
		} else {
			$password = wp_generate_password();
		}

		if( isset( $_POST['password'] ) ) {
			$password = sanitize_text_field( wp_unslash( $_POST['password'] ) );
		} else {
			$password = wp_generate_password();
			$password_field = false;
		}

		$user_email = sanitize_text_field( wp_unslash( $_POST['user_email'] ) );


		// default WP registration hook
		$errors = new WP_Error();
		do_action( 'register_post', $username, $user_email, $errors );

		$errors = apply_filters( 'registration_errors', $errors, $username, $user_email );

		if ( $errors->get_error_code() ) {
			$this->send_error( $errors->get_error_message() );
		}

		// $user_id = wp_create_user( $username, $password, $user_email );

		$user_data = array(
		    'user_login' => $username,
		    'user_email' => $user_email,
		    'user_pass'  => $password,
		);

		$user_id = wp_insert_user( $user_data );

		if( is_wp_error( $user_id ) ) {
			$this->send_error( __( 'User not created', 'radiant-registration' ) );
		} else {

			$userdata = array(
				'ID'           => $user_id,
				'first_name'   => $this->search( $user_vars, 'name', 'first_name' ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '',
				'last_name'    => $this->search( $user_vars, 'name', 'last_name' ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ): '',
				'display_name' => isset( $_POST['display_name'] ) ? sanitize_text_field( wp_unslash( $_POST['display_name'] ) ): '',
				'nickname'     => $this->search( $user_vars, 'name', 'nickname' ) ? sanitize_text_field( wp_unslash( $_POST['nickname'] ) ): '',
				'user_url'     => $this->search( $user_vars, 'name', 'user_url' ) ? sanitize_text_field( wp_unslash( $_POST['user_url'] ) ): '',
				'description'  => $this->search( $user_vars, 'name', 'description' ) ? sanitize_text_field( wp_unslash( $_POST['description'] ) ): '',
				'role'         => $form_settings['role'],
			);

			$user_id = wp_update_user( apply_filters( 'radiant_registration_register_user_args', $userdata ) );

			if ( $user_id ) {

				// update meta fields.
				$files          = array();
				$meta_key_value = array();
				$multi_repeated = array();

				foreach ( $meta_vars as $key => $value ) {

					switch ( $value['input_type'] ) {
						case 'text':
						case 'email':
						case 'number':
						case 'date':
							$meta_key_value[$value['name']] = sanitize_text_field( wp_unslash( $_POST[ $value['name'] ] ) );
							break;

						case 'textarea':
							$meta_key_value[$value['name']] = wp_kses_post( wp_unslash( $_POST[$value['name'] ] ) );
							break;

						default:
							if ( !empty( $_POST[ $value['name'] ] ) && is_array( $_POST[ $value['name'] ] ) ) {
								if ( $value['input_type'] == 'address' ) {
									$meta_key_value[ $value['name'] ] = sanitize_text_field( wp_unslash( $_POST[ $value['name'] ] ) );
								} else {
									$meta_key_value[ $value['name'] ] = implode( RADIANT_REGISTRATION_SEPARATOR, sanitize_text_field( wp_unslash( $_POST[ $value['name'] ] ) ) );
								}
							} else if ( !empty( $_POST[ $value['name'] ] ) ) {
								$meta_key_value[ $value['name'] ] = sanitize_text_field( wp_unslash( $_POST[ $value['name'] ] ) );
							} else {
								$meta_key_value[ $value['name'] ] = sanitize_text_field( wp_unslash( $_POST[ $value['name'] ] ) );
							}

							break;
					}

				}

				$meta_checkbox = self::meta_filter_checkbox( $meta_vars );
				$meta_key_value = array_merge( $meta_checkbox,$meta_key_value );

				foreach ( $meta_key_value as $meta_key => $meta_value ) {
					update_user_meta( $user_id, $meta_key, $meta_value );
				}

				$user       = get_user_by('id', $user_id);
				$user_email = $user->user_email;
				$to         = $user_email;
				$subject    = '';
				$message    = '';
				
				$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
				$message  = 'Hi'. $user->user_login .',\r\n\r\n';
				$message .= "Congrats! You are Successfully registered to ". $blogname ."\r\n\r\n";
				$message .= "Thanks \r\n\r\n";
				
				if ( ! $password_field ) {
					$message .= "password: ". $password;
				}

				$subject = 'Thank you for registering';

				wp_mail( $to, $subject, $message );


				do_action( 'radiant_registration_after_register', $user_id, $form_id, $form_settings );

				$show_message = false;
				$redirect_to  = '';

				if ( isset( $form_settings['redirect_to'] ) && $form_settings['redirect_to'] === 'page' ) {
					$redirect_to = get_permalink( $form_settings['page_id'] );
				} elseif ( isset( $form_settings['redirect_to'] ) && $form_settings['redirect_to'] === 'url' ) {
					$redirect_to = $form_settings['url'];
				} elseif ( isset( $form_settings['redirect_to'] ) && $form_settings['redirect_to'] === 'same' ) {
					$show_message = true;
				} else {
					$redirect_to = get_permalink( $post_id );
				}

				if ( isset( $form_settings['redirect_to'] ) && $form_settings['redirect_to'] === 'page' ) {
					$redirect_to = get_permalink( $form_settings['page_id'] );
				} elseif ( isset( $form_settings['redirect_to'] ) && $form_settings['redirect_to'] === 'url' ) {
					$redirect_to = $form_settings['url'];
				} elseif ( isset( $form_settings['redirect_to'] ) && $form_settings['redirect_to'] === 'same' ) {
					$redirect_to = get_permalink( sanitize_text_field( wp_unslash(  $_POST['page_id'] ) ) );
					$redirect_to = add_query_arg( array( 'msg' => 'profile_update' ), $redirect_to );
				}

				$response = array(
					'success'      => true,
					'post_id'      => $user_id,
					'redirect_to'  => $redirect_to,
					'show_message' => $show_message,
					'message'      => ( isset( $form_settings['notification_type'] ) && $form_settings['notification_type'] === 1 )? __( 'Please check your email for activation link', 'radiant-registration' ) : $form_settings['message'],
				);

				$response = apply_filters( 'radiant_registration_user_register_redirect', $response, $user_id, $userdata, $form_id, $form_settings );

				echo wp_json_encode( $response );

				exit;
			}
		}

		$response = array( 'message' => $user_id );

		wp_send_json( $response );
	}

	/**
	 * save registration form
	 * 
	 * @return void
	 */
	public function save_registration_form() {

		if ( ! isset( $_POST['radiant_registration_form_builder_nonce'] ) || ! wp_verify_nonce( 
			sanitize_text_field( wp_unslash ( $_POST['radiant_registration_form_builder_nonce'] ) ), 'radiant_registration-form-builder-nonce' ) ) {
			wp_send_json_error( __( 'Unauthorized operation', 'radiant-registration' ) );
		}

		if ( isset( $_POST['form_data'] ) ) {
			parse_str( sanitize_text_field( wp_unslash( $_POST['form_data'] ) ),  $form_data );
		}

		if ( empty( $form_data['radiant_registration_form_id'] ) ) {
			wp_send_json_error( __( 'Invalid form id', 'radiant-registration' ) );
		}

		$form_fields = isset( $_POST['form_fields'] ) ? sanitize_text_field( wp_unslash( $_POST['form_fields'] ) ) : '';
		$form_fields = json_decode( $form_fields, true );

		if ( isset( $_POST['settings'] ) ) {
			$form_settings = (array) json_decode( sanitize_text_field( wp_unslash( $_POST['settings'] ) ) );
		}

		$data = array(
			'form_id'       => absint( $form_data['radiant_registration_form_id'] ),
			'post_title'    => $form_data['post_title'],
			'form_fields'   => $form_fields,
			'form_settings' => $form_settings,
		);

		$form_fields = radiant_registration()->forms->save( $data );

		wp_send_json_success(
			array(
				'form_fields'   => $form_fields,
				'form_settings' => $form_settings
			)
		);
	}

	/**
	 * send error json
	 *
	 * @param string $error error.
	 *
	 * @return void
	 */
	public function send_error( $error ) {

		echo wp_json_encode(
			array(
				'success' => false,
				'error'   => $error,
			)
		);

		die();
	}

	/**
	 * send error json
	 *
	 * @param array  $items items.
	 * @param string $key   key.
	 * @param string $value value.
	 *
	 * @return void
	 */
	public function search( $items, $key, $value ) {
		$results = array();

		if ( is_array( $items ) ) {
			if ( isset( $items[$key] ) && $items[$key] === $value ) {
				$results[] = $items;
			}

			foreach ( $items as $subarray ) {
				$results = array_merge( $results, $this->search( $subarray, $key, $value ) );
			}
		}

		return $results;
	}

	/**
	 * Guess Username
	 *
	 * @param string $email  email.
	 *
	 * @return void
	 */
	public function guess_username( $email ) {
		$username = sanitize_user( substr( $email, 0, strpos( $email, '@' ) ) );

		if ( ! username_exists( $username ) ) {
			return $username;
		}

		$username .= wp_rand( 1, 199 );

		if ( ! username_exists( $username ) ) {
			return $username;
		}
	}

	/**
	 * filter checkbox
	 *
	 * @param string $meta_vars  meta_vars.
	 *
	 * @return void
	 */
	public static function meta_filter_checkbox( $meta_vars ) {

		$filteredmeta = array_reduce( $meta_vars, function ( $result, $item ) {

			if( $item['template'] === 'checkbox_field' ) {
				$result[ $item['name'] ] =  '';
			}

			return $result;

		}, array() );

		return $filteredmeta;
	}
}
