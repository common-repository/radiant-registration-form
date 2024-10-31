<?php
/**
 * Import View
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

defined( 'ABSPATH' ) || exit;
?>

<div id="registration-form-template-modal">
	<div class="registration-form-template-modal">
		<span id="modal-label" class="screen-reader-text"><?php esc_html_e( 'Modal window. Press escape to close.', 'radiant-registration'  ); ?></span>
		<a href="#" class="close">× <span class="screen-reader-text"><?php esc_html_e( 'Close modal window', 'radiant-registration'  ); ?></span></a>
		
		<header class="modal-header">
			<h2> <?php esc_html_e( 'Select a Template', 'radiant-registration' ); ?> </h2>
		</header>
		
		<div class="content-container modal-footer">
			<div class="content">
				<ul>
					<?php
					foreach ( $templates as $key => $template ) {
						$class    = 'template-active';
						$title    = $template->title;
						$image    = $template->image ? $template->image : '';
						$disabled = '';

						$url   = esc_url( add_query_arg(
							array(
								'action'   => $action_name,
								'template' => $key,
								// '_wpnonce' => wp_create_nonce( 'registration_create_from_template' ),
								'radiant_nonce'    => wp_create_nonce('radiant_create_nonce'),
							), 
							admin_url( 'admin.php' ) 
							) 
						);

						if ( ! $template->is_enabled() ) {
								$url      = '#';
								$class    = 'template-inactive';
								$title    = __( 'This integration is not installed.', 'radiant-registration' );
								$disabled = 'disabled';
						}
						?>

						<li class="<?php echo esc_attr( $class ); ?>">
								<h3><?php echo esc_html( $template->get_title() ); ?></h3>
								<?php 
								if ( $image ) {
									printf( '<img src="%s" alt="%s">', esc_attr( $image ), esc_attr( $title ) );
								}
								?>

								<div class="form-create-overlay">
										<div class="title"><?php echo esc_html( $title ); ?></div>
										<div class="description"><?php echo esc_html( $template->get_description() ); ?></div>
										<br>
										<a href="<?php echo esc_url( $url ); ?>" class="button button-primary  btn-submit" title="<?php echo esc_attr( $template->get_title() ); ?>" <?php echo esc_attr($disabled ); ?>>
												<?php esc_html_e( 'Create Form', 'radiant-registration' ); ?>
										</a>
								</div>
						</li>
						<?php
						}
						?>
						</ul>
					</div>
				</div>
			</div>
			<div class="registration-form-template-modal-backdrop"></div>
		</div>