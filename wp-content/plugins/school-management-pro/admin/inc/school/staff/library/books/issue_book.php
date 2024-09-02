<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Library.php';

$books_page_url        = WLSM_M_Staff_Library::get_books_page_url();
$books_issued_page_url = WLSM_M_Staff_Library::get_books_issued_page_url();

$school_id = $current_school['id'];

$book = NULL;

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id   = absint( $_GET['id'] );
	$book = WLSM_M_Staff_Library::fetch_book( $school_id, $id );
	$book_issued_quantity = WLSM_M_Staff_Library::query_issued($id);
}

if ( ! $book ) {
	die;
}

$nonce_action = 'issue-book-' . $book->ID;

$classes = WLSM_M_Staff_Class::fetch_classes( $school_id );
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<i class="fas fa-plus"></i>
					<?php esc_html_e( 'Issue Book', 'school-management' ); ?>
				</span>
				<span class="float-md-right">
					<a href="<?php echo esc_url( $books_issued_page_url ); ?>" class="btn btn-sm btn-outline-light">
						<i class="fas fa-id-card"></i>&nbsp;
						<?php echo esc_html( 'View Books Issued', 'school-management' ); ?>
					</a>&nbsp;
					<a href="<?php echo esc_url( $books_page_url ); ?>" class="btn btn-sm btn-outline-light">
						<i class="fas fa-book"></i>&nbsp;
						<?php echo esc_html( 'Issue Book', 'school-management' ); ?>
					</a>
				</span>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-issue-book-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-issue-book">

			<input type="hidden" name="book_id" value="<?php echo esc_attr( $book->ID ); ?>">

			<div class="row">
				<div class="col-md-6">
					<ul class="list-group list-group-flush wlsm-book-details">
						<li class="list-group-item">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Book Title', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( stripcslashes( $book->title ) ); ?></span>
						</li>
						<li class="list-group-item">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Author', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( stripcslashes( $book->author ) ); ?></span>
						</li>
						<li class="list-group-item">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Subject', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( stripcslashes( $book->subject ) ); ?></span>
						</li>
						<li class="list-group-item">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Rack Number', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( $book->rack_number ); ?></span>
						</li>
						<li class="list-group-item">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Book Number', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( $book->book_number ); ?></span>
						</li>
						<li class="list-group-item">
							<span class="wlsm-font-bold"><?php esc_html_e( 'ISBN Number', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( $book->isbn_number ); ?></span>
						</li>
					</ul>
				</div>
				<div class="col-md-6">
					<ul class="list-group list-group-flush wlsm-book-details">
						<li class="list-group-item">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Total Quantity', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( ( $book->quantity ) ); ?></span>
						</li>
						<li class="list-group-item">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Number Of issued', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( ( $book_issued_quantity ) ); ?></span>
						</li>
						<li class="list-group-item">
							<span class="wlsm-font-bold"><?php esc_html_e( 'Price', 'school-management' ); ?>:</span>
							<span><?php echo esc_html( ( $book->price ) ); ?></span>
						</li>
					</ul>
				</div>
			</div>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_class" class="wlsm-font-bold">
							<?php esc_html_e( 'Class', 'school-management' ); ?>:
						</label>
						<select name="class_id" class="form-control selectpicker" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-class-sections' ) ); ?>" id="wlsm_class" data-live-search="true">
							<option value=""><?php esc_html_e( 'Select Class', 'school-management' ); ?></option>
							<?php foreach ( $classes as $class ) { ?>
							<option value="<?php echo esc_attr( $class->ID ); ?>">
								<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
							</option>
							<?php } ?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_section" class="wlsm-font-bold">
							<?php esc_html_e( 'Section', 'school-management' ); ?>:
						</label>
						<select name="section_id" class="form-control selectpicker wlsm_section" id="wlsm_section" data-live-search="true" title="<?php esc_attr_e( 'All Sections', 'school-management' ); ?>" data-all-sections="1" data-fetch-students="1" data-skip-transferred="0" data-only-active="0" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-section-students' ) ); ?>">
						</select>
					</div>
					<div class="form-group col-md-4 wlsm-student-select-block">
						<label for="wlsm_student" class="wlsm-font-bold">
							<?php esc_html_e( 'Student', 'school-management' ); ?>:
						</label>
						<select name="student" class="form-control selectpicker" id="wlsm_student" data-live-search="true" data-none-selected-text="<?php esc_attr_e( 'Select Student', 'school-management' ); ?>">
						</select>
						<div class="mt-2 wlsm-view-library-card-box">
							<button type="button" class="btn btn-sm btn-outline-primary wlsm-view-library-card" data-nonce="<?php echo esc_attr( wp_create_nonce( 'view-library-card' ) ); ?>" data-only-one-student="<?php esc_attr_e( 'Please select student.', 'school-management' ); ?>" data-message-title="<?php echo esc_attr__( 'View Library Card', 'school-management' ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>"><i class="fas fa-search"></i> <?php esc_html_e( 'Check Library Card', 'school-management' ); ?></button>
						</div>
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_quantity" class="wlsm-font-bold">
							<?php esc_html_e( 'Quantity', 'school-management' ); ?>:
						</label>
						<input type="number" step="1" min="0" name="quantity" class="form-control" id="wlsm_quantity" placeholder="<?php esc_attr_e( 'Enter quantity', 'school-management' ); ?>" value="1" disabled>
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_date_issued" class="font-weight-bold">
							<?php esc_html_e( 'Date Issued', 'school-management' ); ?>:
						</label>
						<input type="text" name="date_issued" class="form-control" id="wlsm_date_issued" placeholder="<?php esc_attr_e( 'Enter date issued', 'school-management' ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_return_date" class="font-weight-bold">
							<?php esc_html_e( 'Date to Return', 'school-management' ); ?>:
						</label>
						<input type="text" name="return_date" class="form-control" id="wlsm_return_date" placeholder="<?php esc_attr_e( 'Enter date to return', 'school-management' ); ?>">
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-issue-book-btn" data-message-title="<?php esc_attr_e( 'Confirm!', 'school-management' ); ?>" data-message-content="<?php esc_attr_e( 'Are you sure to issue this book to the student?', 'school-management' ); ?>" data-submit="<?php esc_attr_e( 'Issue Book', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>">
						<i class="fas fa-book"></i>&nbsp;
						<?php esc_html_e( 'Issue Book', 'school-management' ); ?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
