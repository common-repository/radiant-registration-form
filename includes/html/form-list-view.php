<?php
/**
 * Form List View
 *
 * @author Rokibul
 * @package RadiantRegistration
 */

defined( 'ABSPATH' ) || exit;

$url = esc_url( add_query_arg( array( 'action'   => 'create_template' ), admin_url( 'admin.php' ) ) );
?>
<div class="wrap">
	<h2>
		<?php esc_html_e( 'Forms', 'radiant-registration' );  ?>
		<a href="<?php echo esc_url( $add_new_page_url ); ?>" id="new-radiant-registration-form" class="page-title-action add-form"><?php esc_html_e( 'Add Form', 'radiant-registration' ); ?></a>
	</h2>
	<?php
		$form_list_table = new \RadiantRegistration\Forms_List_Table();
	?>
	<form method="post">
		<?php wp_nonce_field('radiant_form_list_action', 'radiant_form_list_nonce'); ?>
		<?php
			$form_list_table->prepare_items();
			$form_list_table->search_box( __( 'Search Forms', 'radiant-registration' ), 'radiant_registration-form-search' );
			$form_list_table->views();
			$form_list_table->display();
		?>
	</form>
</div>
