<?php
defined( 'ABSPATH' ) || die();

global $wpdb;

$page_url = WLSM_M_Staff_Accountant::get_income_page_url();

$school_id = $current_school['id'];

$income_category = NULL;

$nonce_action = 'add-income-category';

$label = '';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id = absint( $_GET['id'] );

	$income_category = WLSM_M_Staff_Accountant::fetch_income_category( $school_id, $id );

	if ( $income_category ) {
		$nonce_action = 'edit-income-category-' . $income_category->ID;

		$label = $income_category->label;
	}
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<i class="fas fa-tag"></i>
					<?php esc_html_e( 'Income Categories', 'school-management' ); ?>
				</span>
			</span>
			<span class="float-md-right">
				<?php if ( $income_category ) { ?>
				<a href="<?php echo esc_url( $page_url . '&action=category' ); ?>" class="float-md-right btn btn-sm btn-outline-light">
					<i class="fas fa-tag"></i>&nbsp;
					<?php esc_html_e( 'Income Categories', 'school-management' ); ?>
				</a>
				<?php } else { ?>
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-file-invoice"></i>&nbsp;
					<?php esc_html_e( 'View Income', 'school-management' ); ?>
				</a>
				<?php } ?>
			</span>
		</div>
	</div>
</div>

<div class="row<?php if ( $income_category ) { echo ' justify-content-md-center'; } ?> mt-4">
	<?php if ( ! $income_category ) { ?>
	<div class="col-md-6">
		<table class="table table-hover table-bordered" id="wlsm-income-categories-table">
			<thead>
				<tr class="bg-primary text-white">
					<th scope="col"><?php esc_html_e( 'Category', 'school-management' ); ?></th>
					<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
				</tr>
			</thead>
		</table>
	</div>
	<?php } ?>

	<div class="col-md-6">
		<div class="wlsm-page-heading-box">
			<h2 class="h4 border-bottom pb-2 wlsm-page-heading">
				<?php if ( $income_category ) { ?>
				<i class="fas fa-edit text-primary"></i>
				<?php
				printf(
					wp_kses(
						/* translators: %s: income category */
						__( 'Edit Income Category: <span class="text-secondary">%s</span>', 'school-management' ),
						array(
							'span' => array( 'class' => array() )
						)
					),
					esc_html( WLSM_M_Staff_Accountant::get_label_text( $label ) )
				);
				?>
				<?php } else { ?>
				<i class="fas fa-plus-square text-primary"></i>
				<?php esc_html_e( 'Add New Income Category', 'school-management' ); ?>
				<?php } ?>
			</h2>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-income-category-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-income-category">

			<?php if ( $income_category ) { ?>
			<input type="hidden" name="income_category_id" value="<?php echo esc_attr( $income_category->ID ); ?>">
			<?php } ?>

			<div class="form-group">
				<label for="wlsm_income_category_label" class="font-weight-bold"><?php esc_html_e( 'Category', 'school-management' ); ?>:</label>
				<input type="text" name="label" class="form-control" id="wlsm_income_category_label" placeholder="<?php esc_attr_e( 'Enter income category', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_M_Staff_Accountant::get_label_text( $label ) ); ?>">
			</div>

			<div>
				<span class="float-md-right">
					<button type="submit" class="btn btn-sm btn-primary" id="wlsm-save-income-category-btn">
						<?php
						if ( $income_category ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Income Category', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Income Category', 'school-management' );
						}
						?>
					</button>
				</span>
			</div>

		</form>
	</div>
</div>
