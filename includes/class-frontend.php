<?php
/**
 * Frontend
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

namespace RadiantRegistration;

/**
 * Frontend class
 *
 * @package RadiantRegistration
 * @author  Rokibul
 */
class Frontend {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_shortcode( 'radiant-registration', array( $this, 'render' ) );
	}

	/**
	 *
	 * @param array $atts atts.
	 *
	 * @return string
	 */
	public function render( $atts ) {
		extract( shortcode_atts( array( 'id' => 0 ), $atts ) );
		ob_start();

		if ( is_user_logged_in() ) {
			echo wp_kses_post('You are already logged in!');
		} else {

			if ( get_option( 'users_can_register' ) != '1' ) {
				echo esc_html(__('User registration disabled, please contact the admin to enable.', 'radiant-registration'));
				return;
			}

			$form = radiant_registration()->forms->get( $id );

			$this->render_form( $form, $atts );
		}

		return ob_get_clean();
	}

	private function render_form( $form, $atts ) {
		$form_fields = $form->getFields();
		$form_settings = $form->getSettings();
		$status = ( isset( $form_settings['new_user_status'] ) && $form_settings['new_user_status'] == 1 ) ? 'approved' : 'pending';
		$user_notification = isset( $form_settings['user_notification'] ) ? $form_settings['user_notification'] : false;
		radiant_registration()->assets->register_frontend();
		radiant_registration()->assets->enqueue_frontend();
		?>
		<form class="radiant_registration-form-add <?php echo esc_attr( $form->id ); ?>" action="" method="post">
			<ul>
				<?php
				radiant_registration()->fields->render_fields( $form_fields, $form->id, $atts );
				if( radiant_registration()->fields->hassubmit_fields( $form_fields, $form->id, $atts ) ) {

				} else {
					$this->submit_button( $form->id, $form_settings );
				}
				?>
			</ul>
		</form>
		<?php
	}

	/**
	 *
	 * @param array $form_id form_id.
	 * @param array $form_settings form_settings.
	 *
	 * @return string
	 */
	private function submit_button( $form_id, $form_settings ) { ?>
		<li class="submit submit_wrapper radiant_registration_submit">
			<div class="radiant_registration-label"> &nbsp; </div>

			<?php esc_attr( wp_nonce_field( 'radiant_registration_form_frontend', 'radiant_registration_form_frontend_nonce' ) ); ?>
			<input type="hidden" name="form_id" value="<?php echo esc_attr( $form_id ); ?>">
			<input type="hidden" name="page_id" value="<?php echo get_the_ID(); ?>">
			<input type="hidden" name="action" value="radiant_registration_frontend_submit">
			<input type="submit" class="btn btn-submit radiant_registration_submit_btn" name="submit" value="<?php echo esc_attr( $form_settings['submit_text'] ); ?>" />
		</li>
		<?php
	}
}
