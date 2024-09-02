<?php
defined( 'ABSPATH' ) || die();

global $wpdb;

$page_url = WLSM_M_Staff_Accountant::get_expenses_page_url();

$school_id = $current_school['id'];

$expense_category = NULL;

$nonce_action = 'add-expense-category';

$label = '';

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id = absint( $_GET['id'] );

	$expense_category = WLSM_M_Staff_Accountant::fetch_expense_category( $school_id, $id );

	if ( $expense_category ) {
		$nonce_action = 'edit-expense-category-' . $expense_category->ID;

		$label = $expense_category->label;
	}
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<i class="fas fa-tag"></i>
					<?php esc_html_e( 'Expense Categories', 'school-management' ); ?>
				</span>
			</span>
			<span class="float-md-right">
				<?php if ( $expense_category ) { ?>
				<a href="<?php echo esc_url( $page_url . '&action=category' ); ?>" class="float-md-right btn btn-sm btn-outline-light">
					<i class="fas fa-tag"></i>&nbsp;
					<?php esc_html_e( 'Expense Categories', 'school-management' ); ?>
				</a>
				<?php } else { ?>
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-file-invoice"></i>&nbsp;
					<?php esc_html_e( 'View Expenses', 'school-management' ); ?>
				</a>
				<?php } ?>
			</span>
		</div>
	</div>
</div>

<div class="row<?php if ( $expense_category ) { echo ' justify-content-md-center'; } ?> mt-4">
	<?php if ( ! $expense_category ) { ?>
	<div class="col-md-6">
		<table class="table table-hover table-bordered" id="wlsm-expense-categories-table">
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
				<?php if ( $expense_category ) { ?>
				<i class="fas fa-edit text-primary"></i>
				<?php
				printf(
					wp_kses(
						/* translators: %s: expense category */
						__( 'Edit Expense Category: <span class="text-secondary">%s</span>', 'school-management' ),
						array(
							'span' => array( 'class' => array() )
						)
					),
					esc_html( WLSM_M_Staff_Accountant::get_label_text( $label ) )
				);
				?>
				<?php } else { ?>
				<i class="fas fa-plus-square text-primary"></i>
				<?php esc_html_e( 'Add New Expense Category', 'school-management' ); ?>
				<?php } ?>
			</h2>
		</div>

		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-expense-category-form">

			<?php $nonce = wp_create_nonce( $nonce_action ); ?>
			<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="wlsm-save-expense-category">

			<?php if ( $expense_category ) { ?>
			<input type="hidden" name="expense_category_id" value="<?php echo esc_attr( $expense_category->ID ); ?>">
			<?php } ?>

			<div class="form-group">
				<label for="wlsm_expense_category_label" class="font-weight-bold"><?php esc_html_e( 'Category', 'school-management' ); ?>:</label>
				<input type="text" name="label" class="form-control" id="wlsm_expense_category_label" placeholder="<?php esc_attr_e( 'Enter expense category', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_M_Staff_Accountant::get_label_text( $label ) ); ?>">
			</div>

			<div>
				<span class="float-md-right">
					<button type="submit" class="btn btn-sm btn-primary" id="wlsm-save-expense-category-btn">
						<?php
						if ( $expense_category ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update Expense Category', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New Expense Category', 'school-management' );
						}
						?>
					</button>
				</span>
			</div>

		</form>
	</div>
</div>
