<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_School.php';

global $wpdb;

$page_url = WLSM_M_School::get_page_url();

$school = NULL;

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id     = absint( $_GET['id'] );
	$school = WLSM_M_School::fetch_school_label( $id );
}

if ( ! $school ) {
	die();
}

$nonce_action = 'assign-admin-' . $school->ID;

$label = $school->label;
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
						<div class="row mb-3">
							<div class="col-md-12">

								<h2 class="h4 border-bottom pb-2">
									<i class="fas fa-plus-square text-primary"></i>
									<?php esc_html_e( 'Assign Admin', 'school-management' ); ?>
								</h2>
								<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-assign-admin-form">

									<?php $nonce = wp_create_nonce( $nonce_action ); ?>
									<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

									<input type="hidden" name="action" value="wlsm-assign-admin">

									<input type="hidden" name="school_id" value="<?php echo esc_attr( $school->ID ); ?>">

									<div class="form-group">
										<label class="font-weight-bold"><span class="wlsm-important">*</span> <?php esc_html_e( 'Assign New Admin', 'school-management' ); ?>:</label>
										<br>
										<div class="form-check form-check-inline">
											<input checked class="form-check-input" type="radio" name="new_or_existing" id="wlsm_existing_user" value="existing_user">
											<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_existing_user">
												<?php esc_html_e( 'Existing User?', 'school-management' ); ?>
											</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="new_or_existing" id="wlsm_new_user" value="new_user">
											<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_new_user">
												<?php esc_html_e( 'New User?', 'school-management' ); ?>
											</label>
										</div>
									</div>

									<div class="form-group">
										<label for="wlsm_name" class="font-weight-bold"><span class="wlsm-important">*</span> <?php esc_html_e( 'Name', 'school-management' ); ?>:</label>
										<input type="text" name="name" class="form-control" id="wlsm_name" placeholder="<?php esc_attr_e( 'Enter name', 'school-management' ); ?>">
									</div>

									<div class="wlsm-assign-user wlsm-assign-exisitng-user">
										<div class="form-group">
											<label for="wlsm_existing_username" class="font-weight-bold"><span class="wlsm-important">*</span> <?php esc_html_e( 'Username', 'school-management' ); ?>:</label>
											<input type="text" name="existing_username" class="form-control" id="wlsm_existing_username" placeholder="<?php esc_attr_e( 'Enter existing username', 'school-management' ); ?>" value="">
										</div>
									</div>

									<div class="wlsm-assign-user wlsm-assign-new-user">
										<div class="form-group">
											<label for="wlsm_new_username" class="font-weight-bold"><span class="wlsm-important">*</span> <?php esc_html_e( 'Username', 'school-management' ); ?>:</label>
											<input type="text" name="new_username" class="form-control" id="wlsm_new_username" placeholder="<?php esc_attr_e( 'Enter username', 'school-management' ); ?>">
										</div>

										<div class="form-group">
											<label for="wlsm_new_email" class="font-weight-bold"><span class="wlsm-important">*</span> <?php esc_html_e( 'Email', 'school-management' ); ?>:</label>
											<input type="text" name="new_email" class="form-control" id="wlsm_new_email" placeholder="<?php esc_attr_e( 'Enter email address', 'school-management' ); ?>">
										</div>

										<div class="form-group">
											<label for="wlsm_new_password" class="font-weight-bold"><span class="wlsm-important">*</span> <?php esc_html_e( 'Password', 'school-management' ); ?>:</label>
											<input type="password" name="new_password" class="form-control" id="wlsm_new_password" placeholder="<?php esc_attr_e( 'Enter password', 'school-management' ); ?>">
										</div>
									</div>

									<div>
										<span class="float-md-right">
											<button type="submit" class="btn btn-sm btn-primary" id="wlsm-assign-admin-btn">
												<i class="fas fa-key"></i>&nbsp;
												<?php esc_html_e( 'Assign Admin', 'school-management' ); ?>
											</button>
										</span>
									</div>

								</form>

							</div>
						</div>
						<div class="row">
							<div class="col-md-12">

								<h2 class="h4 border-bottom pb-2">
									<i class="fas fa-user-shield text-primary"></i>
									<?php esc_html_e( 'Admins Assigned', 'school-management' ); ?>
								</h2>
								<table class="table table-hover table-bordered" id="wlsm-school-admins-table" data-school="<?php echo esc_attr( $school->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'school-admins-' . $school->ID ) ); ?>">
									<thead>
										<tr class="text-white bg-primary">
											<th scope="col"><?php esc_html_e( 'Name', 'school-management' ); ?></th>
											<th scope="col"><?php esc_html_e( 'Username', 'school-management' ); ?></th>
											<th scope="col"><?php esc_html_e( 'Email', 'school-management' ); ?></th>
											<th scope="col"><?php esc_html_e( 'Assigned By', 'school-management' ); ?></th>
											<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
										</tr>
									</thead>
								</table>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
