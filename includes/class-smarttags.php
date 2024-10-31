<?php
/**
 * SmartTags
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration;

/**
 * SmartTags class
 *
 * @package RadiantRegistration
 */
class SmartTags {

	/**
	 * Get Merge Tags
	 *
	 * @return array
	 */
	public function getMergeTags() {
		$tags = array(
			'form' => array(
				'title' => __( 'Form', 'radiant-registration' ),
				'tags'  => array(
					'entry_id'  => __( 'Entry ID', 'radiant-registration' ),
					'form_id'   => __( 'Form ID', 'radiant-registration' ),
					'form_name' => __( 'Form Name', 'radiant-registration' ),
				),
			),
			'system' => array(
				'title' => __( 'System', 'radiant-registration' ),
				'tags'  => array(
					'admin_email' => __( 'Site Administrator Email', 'radiant-registration' ),
					'date'        => __( 'Date', 'radiant-registration' ),
					'site_name'   => __( 'Site Title', 'radiant-registration' ),
					'site_url'    => __( 'Site URL', 'radiant-registration' ),
					'page_title'  => __( 'Embedded Page Title', 'radiant-registration' ),
				),
			),
			'user' => array(
				'title' => __( 'User', 'radiant-registration' ),
				'tags'  => array(
					'ip_address'   => __( 'IP Address', 'radiant-registration' ),
					'user_id'      => __( 'User ID', 'radiant-registration' ),
					'first_name'   => __( 'First Name', 'radiant-registration' ),
					'last_name'    => __( 'Last Name', 'radiant-registration' ),
					'display_name' => __( 'Display Name', 'radiant-registration' ),
					'user_email'   => __( 'Email', 'radiant-registration' ),
				),
			),
			'urls' => array(
				'title' => __( 'URL\'s', 'radiant-registration' ),
				'tags'  => array(
					'url_page'                         => __( 'Embeded Page URL', 'radiant-registration' ),
					'url_referer'                      => __( 'Referer URL', 'radiant-registration' ),
					'url_login'                        => __( 'Login URL', 'radiant-registration' ),
					'url_logout'                       => __( 'Logout URL', 'radiant-registration' ),
					'url_register'                     => __( 'Register URL', 'radiant-registration' ),
					'url_lost_password'                => __( 'Lost Password URL', 'radiant-registration' ),
					'personal_data_erase_confirm_url'  => __( 'Personal Data Erase Confirmation URL', 'radiant-registration' ),
					'personal_data_export_confirm_url' => __( 'Personal Data Export Confirmation URL', 'radiant-registration' ),
				),
			),
		);

		return  $tags;
	}
}
