<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Library.php';

$books_page_url        = WLSM_M_Staff_Library::get_books_page_url();
$books_issued_page_url = WLSM_M_Staff_Library::get_books_issued_page_url();
?>

<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-book"></i>
				<?php esc_html_e( 'Books', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $books_issued_page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-id-card"></i>&nbsp;
					<?php echo esc_html( 'View Books Issued', 'school-management' ); ?>
				</a>&nbsp;
				<a href="<?php echo esc_url( $books_page_url . '&action=save_bulk' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-plus-square"></i>&nbsp;
					<?php echo esc_html( 'Add New Books In Bulk', 'school-management' ); ?>
				</a>
				<a href="<?php echo esc_url( $books_page_url . '&action=save' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-plus-square"></i>&nbsp;
					<?php echo esc_html( 'Add New Book', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-books-table">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Title', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Author', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Subject', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Rack Number', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Book Number', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'ISBN Number', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Price', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Quantity', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Issue Book', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
