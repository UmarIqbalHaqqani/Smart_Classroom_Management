<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_School.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Admin.php';

global $wpdb;

$page_url = WLSM_M_School::get_page_url();

$staff = NULL;

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id     = absint( $_GET['id'] );
	$staff  = WLSM_M_Admin::fetch_admin( $id );

	if ( $staff ) {
		$nonce_action = 'edit-school-admin-' . $staff->ID;

		$user_id     = $staff->user_id;
		$school_id   = $staff->school_id;
		$school_name = $staff->school_name;
		$name        = $staff->name;
		$username    = $staff->username;
		$email       = $staff->email;
	}
}

if ( ! $staff ) {
	die();
}
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
							esc_html( WLSM_M_School::get_label_text( $school_name ) )
						);
						?>
					</h1>
				</div>
			</div>
		</div>
		<div class="row justify-content-md-center">
			<div class="col-md-8">
				<div class="card col">
					<div class="card-header">
						<span class="float-left">
							<a href="<?php echo esc_url( $page_url . '&action=admins&id=' . $school_id ); ?>" class="btn btn-sm btn-primary">
								<i class="fas fa-user-shield"></i>&nbsp;
								<?php esc_html_e( 'Admins Assigned', 'school-management' ); ?>
							</a>
						</span>
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
									<i class="fas fa-edit text-primary"></i>
									<?php esc_html_e( 'Edit Admin', 'school-management' ); ?>
								</h2>
								<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-school-admin-form">

									<?php $nonce = wp_create_nonce( $nonce_action ); ?>
									<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

									<input type="hidden" name="action" value="wlsm-edit-school-admin">

									<input type="hidden" name="staff_id" value="<?php echo esc_attr( $staff->ID ); ?>">

									<div class="form-group">
										<label for="wlsm_name" class="font-weight-bold"><?php esc_html_e( 'Name', 'school-management' ); ?>:</label>
										<input type="text" name="name" class="form-control" id="wlsm_name" placeholder="<?php esc_attr_e( 'Enter name', 'school-management' ); ?>" value="<?php echo esc_attr( $name ); ?>">
									</div>

									<?php if ( ! $user_id ) { ?>
									<div class="form-group">
										<label class="font-weight-bold"><span class="wlsm-important">*</span> <?php esc_html_e( 'Create User Account', 'school-management' ); ?>:</label>
										<br>
										<div class="mb-2">
											<em class="text-secondary">
											<?php esc_html_e( 'There is no user account for this admin. Enter details to create a new account.', 'school-management' ); ?>
											</em>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="save_new_or_existing" id="wlsm_existing_user" value="existing_user">
											<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_existing_user">
												<?php esc_html_e( 'Existing User?', 'school-management' ); ?>
											</label>
										</div>
										<div class="form-check form-check-inline">
											<input checked class="form-check-input" type="radio" name="save_new_or_existing" id="wlsm_new_user" value="new_user">
											<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_new_user">
												<?php esc_html_e( 'New User?', 'school-management' ); ?>
											</label>
										</div>
									</div>
									<?php } ?>

									<div class="wlsm-save-school-admin wlsm-school-admin-existing-user">
										<div class="form-group">
											<label for="wlsm_existing_username" class="font-weight-bold"><span class="wlsm-important">*</span> <?php esc_html_e( 'Username', 'school-management' ); ?>:</label>
											<input type="text" name="existing_username" class="form-control" id="wlsm_existing_username" placeholder="<?php esc_attr_e( 'Enter existing username', 'school-management' ); ?>" value="">
										</div>
									</div>

									<div class="wlsm-save-school-admin wlsm-school-admin-new-user">
										<div class="form-group">
											<label for="wlsm_new_username" class="font-weight-bold">
												<span class="wlsm-important">*</span> <?php esc_html_e( 'Username', 'school-management' ); ?>:
												<?php if ( $user_id ) { ?>
													<small>
														<em class="text-secondary">
														<?php esc_html_e( 'Usernames cannot be changed.', 'school-management' ); ?>
														</em>
													</small>
												<?php } ?>
											</label>
											<input type="text" name="new_username" class="form-control" id="wlsm_new_username" placeholder="<?php esc_attr_e( 'Enter username', 'school-management' ); ?>" value="<?php echo esc_attr( $username ); ?>" <?php if ( $user_id ) { echo 'readonly'; } ?>>
										</div>

										<div class="form-group">
											<label for="wlsm_new_email" class="font-weight-bold"><span class="wlsm-important">*</span> <?php esc_html_e( 'Email', 'school-management' ); ?>:</label>
											<input type="text" name="new_email" class="form-control" id="wlsm_new_email" placeholder="<?php esc_attr_e( 'Enter email address', 'school-management' ); ?>" value="<?php echo esc_attr( $email ); ?>">
										</div>

										<div class="form-group">
											<label for="wlsm_new_password" class="font-weight-bold"><span class="wlsm-important">*</span> <?php esc_html_e( 'Password', 'school-management' ); ?>:</label>
											<input type="password" name="new_password" class="form-control" id="wlsm_new_password" placeholder="<?php esc_attr_e( 'Enter password', 'school-management' ); ?>">
										</div>
									</div>

									<div>
										<span class="float-md-right">
											<button type="submit" class="btn btn-sm btn-primary" id="wlsm-save-school-admin-btn">
												<i class="fas fa-save"></i>&nbsp;
												<?php esc_html_e( 'Update Admin', 'school-management' ); ?>
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
