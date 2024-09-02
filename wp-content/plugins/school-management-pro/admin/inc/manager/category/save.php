<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Category.php';

global $wpdb;

$page_url = WLSM_M_Category::get_page_url();

$category = NULL;

$nonce_action = 'add-category';

$label = ''; 

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id     = absint( $_GET['id'] );
	$category = WLSM_M_Category::fetch_Category( $id );

	if ( $category ) {
		$nonce_action = 'edit-category-' . $category->ID;

		$label = $category->label;
	}
}
?>
<div class="wlsm">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="wlsm-main-header card col wlsm-page-heading-box">
					<h1 class="h3 text-center wlsm-page-heading">
					<?php if ( $category ) { ?>
						<i class="fas fa-edit text-primary"></i>
						<?php
						printf(
							wp_kses(
								/* translators: %s: class name */
								__( 'Edit Class: <span class="text-secondary">%s</span>', 'school-management' ),
								array(
									'span' => array( 'category' => array() )
								)
							),
							esc_html( WLSM_M_Category::get_label_text( $label ) )
						);
						?>
					<?php } else { ?>
						<i class="fas fa-plus-square text-primary"></i>
						<?php esc_html_e( 'Add New Category', 'school-management' ); ?>
					<?php } ?>
					</h1>
				</div>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class="col-md-8">
				<div class="card col">
					<div class="card-header">
						<span class="h6 float-left">
							<?php echo wp_kses( __( 'Fill all the required fields (<span class="wlsm-important">*</span>).', 'school-management' ), array( 'span' => array( 'category' => array() ) ) ); ?>
						</span>
						<span class="float-md-right">
							<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-info">
								<i class="fas fa-layer-group"></i>&nbsp;
								<?php esc_html_e( 'View All', 'school-management' ); ?>
							</a>
						</span>
					</div>
					<div class="card-body">
						<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-category-form">

							<?php $nonce = wp_create_nonce( $nonce_action ); ?>
							<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

							<input type="hidden" name="action" value="wlsm-save-category">

							<?php if ( $category ) { ?>
							<input type="hidden" name="category_id" value="<?php echo esc_attr( $category->ID ); ?>">
							<?php } ?>

							<div class="form-group">
								<label for="wlsm_label" class="font-weight-bold"><span class="wlsm-important">*</span> <?php esc_html_e( 'Category', 'school-management' ); ?>:</label>
								<input type="text" name="label" class="form-control" id="wlsm_label" placeholder="<?php esc_attr_e( 'Enter class label', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_M_Category::get_label_text( $label ) ); ?>">
							</div>

							<div>
								<span class="float-md-right">
									<button type="submit" class="btn btn-sm btn-primary" id="wlsm-save-category-btn">
										<?php
										if ( $category ) {
											?>
											<i class="fas fa-save"></i>&nbsp;
											<?php
											esc_html_e( 'Update Category', 'school-management' );
										} else {
											?>
											<i class="fas fa-plus-square"></i>&nbsp;
											<?php
											esc_html_e( 'Add New Category', 'school-management' );
										}
										?>
									</button>
								</span>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
