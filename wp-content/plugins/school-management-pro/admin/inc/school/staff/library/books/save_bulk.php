<?php
defined( 'ABSPATH' ) || die();

$books_page_url        = WLSM_M_Staff_Library::get_books_page_url();
$books_issued_page_url = WLSM_M_Staff_Library::get_books_issued_page_url();

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$nonce_action = 'bulk-import-books';

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-2 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-file-import"></i>
				<?php esc_html_e( 'Bulk Import Books', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $books_issued_page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-id-card"></i>&nbsp;
					<?php echo esc_html( 'View Books Issued', 'school-management' ); ?>
				</a>&nbsp;
				<a href="<?php echo esc_url( $books_page_url . '&action=save' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-plus-square"></i>&nbsp;
					<?php echo esc_html( 'Add New Book', 'school-management' ); ?>
				</a>
			</span>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-bulk-import-books-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="<?php echo esc_attr( 'wlsm-bulk-import-books' ); ?>">

			<div class="wlsm-form-section">
				<div class="row">
					<div class="col-md-12">
						<div class="wlsm-form-sub-heading wlsm-font-bold">
							<?php esc_html_e( 'Import Books From CSV File', 'school-management' ); ?>
							<br>
							<small class="text-dark">
								<em><?php esc_html_e( 'click "Export Sample CSV", fill books details in the file, choose the CSV file with books details and click "Bulk Import".', 'school-management' ); ?></em>
							</small>
						</div>
					</div>
				</div>

				<div class="form-row mt-2">
					<div class="form-group col-md-4 col-lg-4 ">
						<label for="wlsm_students_csv" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'CSV File', 'school-management' ); ?>:
						</label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="wlsm_students_csv" name="csv">
							<label class="custom-file-label" for="wlsm_students_csv">
								<?php esc_html_e( 'Choose CSV File', 'school-management' ); ?>
							</label>
						</div>
					</div>

					<div class="form-group col-md-4">
						<label class="wlsm-font-bold">
							<?php esc_html_e( 'Export Sample CSV File', 'school-management' ); ?>
						</label>
						<br>
						<button type="button" class="btn btn-sm btn-outline-primary p-2" id="wlsm-books-sample-csv-export-btn" data-nonce="<?php echo esc_attr( wp_create_nonce( 'books-sample-csv-export' ) ); ?>">
                            <i class="fas fa-cloud-download-alt pr-1"></i>
							<?php esc_html_e( 'Export Sample CSV', 'school-management' ); ?>
						</button>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-sm btn-primary" id="wlsm-bulk-import-books-btn" data-message-title="<?php esc_attr_e( 'Confirm Import!', 'school-management' ); ?>" data-message-content="<?php esc_attr_e( 'Are you sure to import these students to selected class?', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>" data-submit="<?php esc_attr_e( 'Import', 'school-management' ); ?>">
						<i class="fas fa-file-import"></i>
						<?php esc_html_e( 'Bulk Import', 'school-management' ); ?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
