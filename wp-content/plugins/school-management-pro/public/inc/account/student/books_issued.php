<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Library.php';

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';

$books_issued_per_page = WLSM_M::books_issued_per_page();

$books_issued_query = WLSM_M::books_issued_query();

$books_issued_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$books_issued_query}) AS combined_table", $school_id, $session_id, $student->ID ) );

$books_issued_page = isset( $_GET['books_issued_page'] ) ? absint( $_GET['books_issued_page'] ) : 1;

$books_issued_page_offset = ( $books_issued_page * $books_issued_per_page ) - $books_issued_per_page;

$books_issued = $wpdb->get_results( $wpdb->prepare( $books_issued_query . ' ORDER BY bki.date_issued DESC LIMIT %d, %d', $school_id, $session_id, $student->ID, $books_issued_page_offset, $books_issued_per_page ) );
?>
<div class="wlsm-content-area wlsm-section-books_issued wlsm-student-books-issued">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'Books Issued', 'school-management' ); ?>
		</span>
	</div>

	<div class="wlsm-st-books-issued-section">
		<?php
		if ( count( $books_issued ) ) {
		?>
		<div class="wlsm-table-section">
			<div class="table-responsive w-100 wlsm-w-100">
				<table class="table table-bordered wlsm-student-books-issued-table wlsm-w-100">
					<thead>
						<tr class="bg-primary text-white">
							<th><?php esc_html_e( 'Book Title', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Issued Quantity', 'school-management' ); ?></th>
							<th class="text-nowrap"><?php esc_html_e( 'Date Issued', 'school-management' ); ?></th>
							<th class="text-nowrap"><?php esc_html_e( 'Return Date', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Return Status', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Author', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Subject', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Rack Number', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Book Number', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'ISBN Number', 'school-management' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $books_issued as $row ) {
							if ( ! $row->returned_at ) {
								$returned_text = '';
							} else {
								$returned_text = '<br><span class="text-secondary wlsm-font-small">' . esc_html( WLSM_Config::get_date_text( $row->returned_at ) ) . '</span>';
							}
						?>
						<tr>
							<td>
								<?php echo esc_html( WLSM_M_Staff_Library::get_book_title( $row->title ) ); ?>
							</td>
							<td>
								<?php echo esc_html( WLSM_M_Staff_Library::get_book_quantity( $row->issued_quantity ) ); ?>
							</td>
							<td class="text-nowrap">
								<?php echo esc_html( WLSM_Config::get_date_text( $row->date_issued ) ); ?>
							</td>
							<td class="text-nowrap">
								<?php echo esc_html( WLSM_Config::get_date_text( $row->return_date ) ); ?>
							</td>
							<td>
								<?php echo WLSM_M_Staff_Library::get_book_issued_status_text( $row->returned_at ) . $returned_text; ?>
							</td>
							<td>
								<?php echo esc_html( WLSM_M_Staff_Library::get_book_author( $row->author ) ); ?>
							</td>
							<td>
								<?php echo esc_html( WLSM_M_Staff_Library::get_book_subject( $row->subject ) ); ?>
							</td>
							<td>
								<?php echo esc_html( WLSM_M_Staff_Library::get_book_rack_number( $row->rack_number ) ); ?>
							</td>
							<td>
								<?php echo esc_html( WLSM_M_Staff_Library::get_book_number( $row->book_number ) ); ?>
							</td>
							<td>
								<?php echo esc_html( WLSM_M_Staff_Library::get_book_isbn_number( $row->isbn_number ) ); ?>
							</td>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="wlsm-text-right wlsm-font-medium wlsm-font-bold wlsm-mt-2">
		<?php
		echo paginate_links(
			array(
				'base'      => add_query_arg( 'books_issued_page', '%#%' ),
				'format'    => '',
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
				'total'     => ceil( $books_issued_total / $books_issued_per_page ),
				'current'   => $books_issued_page,
			)
		);
		?>
		</div>
		<?php
		} else {
		?>
		<div>
			<span class="wlsm-font-medium wlsm-font-bold">
				<?php esc_html_e( 'There is no books issued.', 'school-management' ); ?>
			</span>
		</div>
		<?php
		}
		?>
	</div>
</div>
