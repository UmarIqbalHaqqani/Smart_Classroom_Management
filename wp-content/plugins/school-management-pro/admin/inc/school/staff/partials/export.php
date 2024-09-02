<?php
defined( 'ABSPATH' ) || die();
?>
<div class="row">
	<div class="col-md-12">
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-export-table-form">
			<div class="wlsm-export-fields">
				<input type="hidden" name="nonce" value="" id="wlsm-export-nonce">
				<input type="hidden" name="action" value="" id="wlsm-export-action">
				<input type="hidden" name="filter" value="" id="wlsm-export-filter">
			</div>
			<button type="submit" class="btn btn-sm btn-success" id="wlsm-export-table-btn">
				<i class="fas fa-file-export"></i>
				<?php esc_html_e( 'Export', 'school-management' ); ?>
			</button>
		</form>
	</div>
</div>
