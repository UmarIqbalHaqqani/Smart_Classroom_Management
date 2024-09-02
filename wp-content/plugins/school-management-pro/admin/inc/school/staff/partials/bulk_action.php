<?php
defined( 'ABSPATH' ) || die();
?>
<caption>
	<div class="input-group">
		<select name="bulk_action" data-entity="<?php echo esc_attr( $entity ); ?>" class="bulk-action-select bulk-action-select-<?php echo esc_attr( $entity ); ?> form-control">
			<option value=""><?php esc_html_e( 'Select Option', 'school-management' ); ?></option>
			<option value="delete"><?php esc_html_e( 'Delete', 'school-management' ); ?></option>
		</select>
		<button data-nonce="<?php echo esc_attr( wp_create_nonce( 'bulk-action-' . $entity ) ); ?>" data-message-title="<?php esc_attr_e( 'Confirmation!', 'school-management' ); ?>" data-message-content="<?php esc_attr_e( 'Please confirm the action.', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>" data-submit="<?php esc_attr_e( 'Submit', 'school-management' ); ?>" class="btn btn-danger btn-sm bulk-action-btn bulk-action-btn-<?php echo esc_attr( $entity ); ?>" type="button">
			<?php esc_html_e( 'Apply', 'school-management' ); ?>
		</button>
	</div>
</caption>
