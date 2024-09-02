<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Library.php';

$page_url = WLSM_M_Staff_Library::get_books_page_url();

$school_id = $current_school['id'];

$book = NULL;

$nonce_action = 'add-book';

$title       = '';
$author      = '';
$subject     = '';
$description = '';
$rack_number = '';
$book_number = '';
$isbn_number = '';
$price       = '';
$quantity    = '';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id      = absint( $_GET['id'] );
	$book = WLSM_M_Staff_Library::fetch_book( $school_id, $id );

	if ( $book ) {
		$nonce_action = 'edit-book-' . $book->ID;

		$title       = $book->title;
		$author      = $book->author;
		$subject     = $book->subject;
		$description = $book->description;
		$rack_number = $book->rack_number;
		$book_number = $book->book_number;
		$isbn_number = $book->isbn_number;
		$price       = $book->price;
		$quantity    = $book->quantity;
	}
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					if ( $book ) {
						printf(
							wp_kses(
								/* translators: %s: book title */
								__( 'Edit Book: %s', 'school-management' ),
								array(
									'span' => array( 'class' => array() )
								)
							),
							esc_html( stripcslashes( $title ) )
						);
					} else {
						esc_html_e( 'Add New Book', 'school-management' );
					}
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-book"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-book-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-book">

			<?php if ( $book ) { ?>
			<input type="hidden" name="book_id" value="<?php echo esc_attr( $book->ID ); ?>">
			<?php } ?>

			<div class="wlsm-form-section">
				<div class="form-row">
					<div class="form-group col-md-6">
						<label for="wlsm_title" class="wlsm-font-bold">
							<span class="wlsm-important">*</span> <?php esc_html_e( 'Title', 'school-management' ); ?>:
						</label>
						<input type="text" name="title" class="form-control" id="wlsm_title" placeholder="<?php esc_attr_e( 'Enter book title', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $title ) ); ?>">
					</div>
					<div class="form-group col-md-6">
						<label for="wlsm_author" class="wlsm-font-bold">
							<?php esc_html_e( 'Author', 'school-management' ); ?>:
						</label>
						<input type="text" name="author" class="form-control" id="wlsm_author" placeholder="<?php esc_attr_e( 'Enter book author', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $author ) ); ?>">
					</div>
				</div>

				<div class="form-row">
					<div class="form-group col-md-4">
						<label for="wlsm_subject" class="wlsm-font-bold">
							<?php esc_html_e( 'Subject', 'school-management' ); ?>:
						</label>
						<input type="text" name="subject" class="form-control" id="wlsm_subject" placeholder="<?php esc_attr_e( 'Enter subject', 'school-management' ); ?>" value="<?php echo esc_attr( stripcslashes( $subject ) ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_price" class="wlsm-font-bold">
							<?php esc_html_e( 'Price', 'school-management' ); ?>:
						</label>
						<input type="number" step="any" min="0" name="price" class="form-control" id="wlsm_price" placeholder="<?php esc_attr_e( 'Enter price', 'school-management' ); ?>" value="<?php echo esc_attr( ! empty( $price ) ? WLSM_Config::sanitize_money( $price ) : '' ); ?>">
					</div>
					<div class="form-group col-md-4">
						<label for="wlsm_quantity" class="wlsm-font-bold">
							<?php esc_html_e( 'Quantity', 'school-management' ); ?>:
						</label>
						<input type="number" step="1" min="0" name="quantity" class="form-control" id="wlsm_quantity" placeholder="<?php esc_attr_e( 'Enter quantity', 'school-management' ); ?>" value="<?php echo esc_attr( $quantity ); ?>">
					</div>
				</div>

				<div class="form-row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="wlsm_description" class="wlsm-font-bold">
								<?php esc_html_e( 'Description', 'school-management' ); ?>:
							</label>
							<textarea name="description" class="form-control" id="wlsm_description" cols="30" rows="8" placeholder="<?php esc_attr_e( 'Enter description', 'school-management' ); ?>"><?php echo esc_html( $description ); ?></textarea>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="wlsm_rack_number" class="wlsm-font-bold">
								<?php esc_html_e( 'Rack Number', 'school-management' ); ?>:
							</label>
							<input type="text" name="rack_number" class="form-control" id="wlsm_rack_number" placeholder="<?php esc_attr_e( 'Enter rack number', 'school-management' ); ?>" value="<?php echo esc_attr( $rack_number ); ?>">
						</div>
						<div class="form-group">
							<label for="wlsm_book_number" class="wlsm-font-bold">
								<?php esc_html_e( 'Book Number', 'school-management' ); ?>:
							</label>
							<input type="text" name="book_number" class="form-control" id="wlsm_book_number" placeholder="<?php esc_attr_e( 'Enter book number', 'school-management' ); ?>" value="<?php echo esc_attr( $book_number ); ?>">
						</div>
						<div class="form-group">
							<label for="wlsm_isbn_number" class="wlsm-font-bold">
								<?php esc_html_e( 'ISBN Number', 'school-management' ); ?>:
							</label>
							<input type="text" name="isbn_number" class="form-control" id="wlsm_isbn_number" placeholder="<?php esc_attr_e( 'Enter ISBN number', 'school-management' ); ?>" value="<?php echo esc_attr( $isbn_number ); ?>">
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-primary" id="wlsm-save-book-btn">
						<?php
						if ( $book ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Book', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Book', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
