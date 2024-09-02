<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_School.php';

$page_url = WLSM_M_School::get_page_url();

$school = NULL;

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id     = absint( $_GET['id'] );
	$school = WLSM_M_School::fetch_school_label( $id );
}

if ( ! $school ) {
	die();
}

$nonce_action = 'assign-classes-' . $school->ID;

$label = $school->label;

$school_classes_ids = WLSM_M_Staff_Class::fetch_classes_ids( $school->ID );

$classes = WLSM_M_School::get_keyword_classes();

$classes = array_filter(
	$classes,
	function( $class ) use ( $school_classes_ids ) {
		if ( in_array( $class->ID, $school_classes_ids ) ) {
			return false;
		}
		return true;
	}
);
?>
<div class="wlsm">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="wlsm-main-header card col">
					<h1 class="h3 text-center">
						<i class="fas fa-school text-primary"></i>
						<?php
						printf(
							wp_kses(
								/* translators: %s: school name */
								__( 'School: <span class="text-secondary">%s</span>', 'school-management' ),
								array(
									'span' => array( 'class' => array() )
								)
							),
							esc_html( WLSM_M_School::get_label_text( $label ) )
						);
						?>
					</h1>
				</div>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class="col-md-12">
				<div class="card col">
					<div class="card-header">
						<span class="float-md-right">
							<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-info">
								<i class="fas fa-school"></i>&nbsp;
								<?php esc_html_e( 'View Schools', 'school-management' ); ?>
							</a>
						</span>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-7">

								<h2 class="h4 border-bottom pb-2">
									<i class="fas fa-layer-group text-primary"></i>
									<?php esc_html_e( 'Classes Assigned', 'school-management' ); ?>
								</h2>
								<table class="table table-hover table-bordered" id="wlsm-school-classes-table" data-school="<?php echo esc_attr( $school->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'school-classes-' . $school->ID ) ); ?>">
									<thead>
										<tr class="text-white bg-primary">
											<th scope="col"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
											<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
										</tr>
									</thead>
								</table>

							</div>
							<div class="col-md-5">

								<h2 class="h4 border-bottom pb-2">
									<i class="fas fa-plus-square text-primary"></i>
									<?php esc_html_e( 'Assign Classes', 'school-management' ); ?>
								</h2>
								<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-assign-classes-form">

									<?php $nonce = wp_create_nonce( $nonce_action ); ?>
									<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

									<input type="hidden" name="action" value="wlsm-assign-classes">

									<input type="hidden" name="school_id" value="<?php echo esc_attr( $school->ID ); ?>">

									<!-- Autocomplete classes.
									<div class="form-group">
										<label for="wlsm_class_search" class="font-weight-bold"><?php esc_html_e( 'Class', 'school-management' ); ?>:</label>
										<input type="text" name="keyword" class="form-control" id="wlsm_class_search" placeholder="<?php esc_attr_e( 'Type 1 or more characters... then click to select', 'school-management' ); ?>" autocomplete="off">
									</div>
									-->

									<div class="form-group">
										<label for="wlsm_classes" class="wlsm-font-bold">
											<?php esc_html_e( 'Classes', 'school-management' ); ?>:
										</label>
										<select multiple name="classes[]" class="form-control selectpicker" id="wlsm_classes" data-actions-box="true" data-none-selected-text="<?php esc_attr_e( 'Assign Classes to the School', 'school-management' ); ?>">
											<?php foreach ( $classes as $class ) { ?>
											<option value="<?php echo esc_attr( $class->ID ); ?>">
												<?php echo esc_html( WLSM_M_Class::get_label_text( $class->label ) ); ?>
											</option>
											<?php } ?>
										</select>
									</div>

									<div class="wlsm_school_classes"></div>

									<div>
										<span class="float-md-right">
											<button type="submit" class="btn btn-sm btn-primary" id="wlsm-assign-classes-btn">
												<i class="fas fa-save"></i>&nbsp;
												<?php esc_html_e( 'Assign Classes', 'school-management' ); ?>
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
	</div>
</div>
