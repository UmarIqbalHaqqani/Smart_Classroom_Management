<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_School.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Category.php';

global $wpdb;

$page_url = WLSM_M_School::get_page_url();

$school = NULL;

$nonce_action = 'add-school';

$label       = '';
$phone       = '';
$email       = '';
$address     = '';
$description = '';
$is_active   = 1;
$category_id = '';

$enrollment_settings = WLSM_Config::default_enrollment_settings();
$enrollment_prefix   = $enrollment_settings['prefix'];
$enrollment_base     = $enrollment_settings['base'];
$enrollment_padding  = $enrollment_settings['padding'];

$admission_settings = WLSM_Config::default_admission_settings();
$admission_prefix   = $admission_settings['prefix'];
$admission_base     = $admission_settings['base'];
$admission_padding  = $admission_settings['padding'];

$categories = WLSM_M_Category::fetch_Categories( );

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id     = absint( $_GET['id'] );
	$school = WLSM_M_School::fetch_school( $id );

	if ( $school ) {
		$nonce_action = 'edit-school-' . $school->ID;

		$label       = $school->label;
		$phone       = $school->phone;
		$email       = $school->email;
		$address     = $school->address;
		$description = $school->description;
		$category_id = $school->category_id;
		$is_active   = $school->is_active;

		$enrollment_prefix  = $school->enrollment_prefix;
		$enrollment_base    = absint( $school->enrollment_base );
		$enrollment_padding = absint( $school->enrollment_padding );

		$admission_prefix  = $school->admission_prefix;
		$admission_base    = absint( $school->admission_base );
		$admission_padding = absint( $school->admission_padding );
	}
}
?>
<div class="wlsm">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="wlsm-main-header card col wlsm-page-heading-box">
					<h1 class="h3 text-center wlsm-page-heading">
					<?php if ( $school ) { ?>
						<i class="fas fa-edit text-primary"></i>
						<?php
						printf(
							wp_kses(
								/* translators: %s: school name */
								__( 'Edit School: <span class="text-secondary">%s</span>', 'school-management' ),
								array(
									'span' => array( 'class' => array() )
								)
							),
							esc_html( WLSM_M_School::get_label_text( $label ) )
						);
						?>
					<?php } else { ?>
						<i class="fas fa-plus-square text-primary"></i>
						<?php esc_html_e( 'Add New School', 'school-management' ); ?>
					<?php } ?>
					</h1>
				</div>
			</div>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="wlsm-save-school-form" data-default-enrollment-base="<?php echo esc_attr( $enrollment_settings['base'] ); ?>" data-default-enrollment-padding="<?php echo esc_attr( $enrollment_settings['padding'] ); ?>">
			<div class="card col">
				<div class="card-header">
					<span class="h6 float-left">
						<?php echo wp_kses( __( 'Fill all the required fields (<span class="wlsm-important">*</span>).', 'school-management' ), array( 'span' => array( 'class' => array() ) ) ); ?>
					</span>
					<span class="float-md-right">
						<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-info">
							<i class="fas fa-school"></i>&nbsp;
							<?php esc_html_e( 'View All', 'school-management' ); ?>
						</a>
					</span>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<?php $nonce = wp_create_nonce( $nonce_action ); ?>
							<input type="hidden" name="<?php echo esc_attr( $nonce_action ); ?>" value="<?php echo esc_attr( $nonce ); ?>">

							<input type="hidden" name="action" value="wlsm-save-school">

							<?php if ( $school ) { ?>
							<input type="hidden" name="school_id" value="<?php echo esc_attr( $school->ID ); ?>">
							<?php } ?>

							<div class="form-group">
								<label for="wlsm_label" class="font-weight-bold"><span class="wlsm-important">*</span> <?php esc_html_e( 'School Name', 'school-management' ); ?>:</label>
								<input type="text" name="label" class="form-control" id="wlsm_label" placeholder="<?php esc_attr_e( 'Enter school name', 'school-management' ); ?>" value="<?php echo esc_attr( WLSM_M_School::get_label_text( $label ) ); ?>">
							</div>

							<div class="form-group">
								<label for="wlsm_phone" class="font-weight-bold"><?php esc_html_e( 'Phone', 'school-management' ); ?>:</label>
								<input type="text" name="phone" class="form-control" id="wlsm_phone" placeholder="<?php esc_attr_e( 'Enter school phone number', 'school-management' ); ?>" value="<?php echo esc_attr( $phone ); ?>">
							</div>

							<div class="form-group">
								<label for="wlsm_email" class="font-weight-bold"><?php esc_html_e( 'Email', 'school-management' ); ?>:</label>
								<input type="email" name="email" class="form-control" id="wlsm_email" placeholder="<?php esc_attr_e( 'Enter school email', 'school-management' ); ?>" value="<?php echo esc_attr( $email ); ?>">
							</div>

							<div class="form-group">
								<label for="wlsm_address" class="font-weight-bold"><?php esc_html_e( 'Address', 'school-management' ); ?>:</label>
								<textarea name="address" class="form-control" id="wlsm_address" rows="3" placeholder="<?php esc_attr_e( 'Enter school address', 'school-management' ); ?>"><?php echo esc_html( $address ); ?></textarea>
							</div>

							<div class="form-group">
								<label for="wlsm_description" class="font-weight-bold"><?php esc_html_e( 'Description', 'school-management' ); ?>:</label>
								<textarea name="description" class="form-control" id="wlsm_description" rows="3" placeholder="<?php esc_attr_e( 'Enter school description', 'school-management' ); ?>"><?php echo esc_html( $description ); ?></textarea>
							</div>

							<div class="form-group">
								<label for="wlsm_school_category" class="font-weight-bold"><?php esc_html_e( 'School Category', 'school-management' ); ?>:</label>
								<select name="school_category" class="form-control" id="wlsm_school_category">
									<?php foreach($categories as $category): ?>
										<option value="<?php echo esc_attr($category->ID); ?>" <?php selected($category->ID, $category_id); ?>>
											<?php echo esc_html(ucwords($category->label)); ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="form-group">
								<label class="font-weight-bold"><span class="wlsm-important">*</span> <?php esc_html_e( 'Status', 'school-management' ); ?>:</label>
								<br>
								<div class="form-check form-check-inline">
									<input <?php checked( $is_active, 1, true ); ?> class="form-check-input" type="radio" name="is_active" id="wlsm_status_active" value="1">
									<label class="ml-1 form-check-label text-primary font-weight-bold" for="wlsm_status_active">
										<?php echo esc_html( WLSM_M_School::get_active_text() ); ?>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input <?php checked( $is_active, 0, true ); ?> class="form-check-input" type="radio" name="is_active" id="wlsm_status_inactive" value="0">
									<label class="ml-1 form-check-label text-danger font-weight-bold" for="wlsm_status_inactive">
										<?php echo esc_html( WLSM_M_School::get_inactive_text() ); ?>
									</label>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="form-group">
								<label for="wlsm_enrollment_prefix" class="font-weight-bold"><?php esc_html_e( 'Enrollment Prefix', 'school-management' ); ?>:</label>
								<input type="text" name="enrollment_prefix" class="form-control" id="wlsm_enrollment_prefix" placeholder="<?php esc_attr_e( 'Enter enrollment prefix', 'school-management' ); ?>" value="<?php echo esc_attr( $enrollment_prefix ); ?>">
							</div>

							<div class="form-group">
								<label for="wlsm_enrollment_base" class="font-weight-bold"><?php esc_html_e( 'Enrollment Base Number', 'school-management' ); ?>:</label>
								<input type="number" step="1" min="0" name="enrollment_base" class="form-control" id="wlsm_enrollment_base" placeholder="<?php esc_attr_e( 'Enter enrollment base number', 'school-management' ); ?>" value="<?php echo esc_attr( $enrollment_base ); ?>">
							</div>

							<div class="form-group">
								<label for="wlsm_enrollment_padding" class="font-weight-bold"><?php esc_html_e( 'Enrollment Base Padding', 'school-management' ); ?>:</label>
								<input type="number" step="1" min="1" name="enrollment_padding" class="form-control" id="wlsm_enrollment_padding" placeholder="<?php esc_attr_e( 'Enter enrollment base padding', 'school-management' ); ?>" value="<?php echo esc_attr( $enrollment_padding ); ?>">
							</div>

							<div class="alert alert-info">
								<span class="wlsm-font-bold"><?php esc_html_e( 'First Enrollment Preview:', 'school-management' ); ?></span>
								<span class="wlsm-enrollment-text">
									<?php echo esc_html( $enrollment_prefix . str_pad( 1 + $enrollment_base, $enrollment_padding, '0', STR_PAD_LEFT ) ); ?>
								</span>
							</div>

							<hr>

							<div class="alert alert-secondary wlsm-font-small-medium">
								<span class="wlsm-font-bold"><?php esc_html_e( 'Note:', 'school-management' ); ?></span>
								<span>
									<?php esc_html_e( 'Admission number is auto-generated when student registers from front page registration form.', 'school-management' ); ?>
								</span>
							</div>

							<div class="form-group">
								<label for="wlsm_admission_prefix" class="font-weight-bold"><?php esc_html_e( 'Admission Prefix', 'school-management' ); ?>:</label>
								<input type="text" name="admission_prefix" class="form-control" id="wlsm_admission_prefix" placeholder="<?php esc_attr_e( 'Enter admission prefix', 'school-management' ); ?>" value="<?php echo esc_attr( $admission_prefix ); ?>">
							</div>

							<div class="form-group">
								<label for="wlsm_admission_base" class="font-weight-bold"><?php esc_html_e( 'Admission Base Number', 'school-management' ); ?>:</label>
								<input type="number" step="1" min="0" name="admission_base" class="form-control" id="wlsm_admission_base" placeholder="<?php esc_attr_e( 'Enter admission base number', 'school-management' ); ?>" value="<?php echo esc_attr( $admission_base ); ?>">
							</div>

							<div class="form-group">
								<label for="wlsm_admission_padding" class="font-weight-bold"><?php esc_html_e( 'Admission Base Padding', 'school-management' ); ?>:</label>
								<input type="number" step="1" min="1" name="admission_padding" class="form-control" id="wlsm_admission_padding" placeholder="<?php esc_attr_e( 'Enter admission base padding', 'school-management' ); ?>" value="<?php echo esc_attr( $admission_padding ); ?>">
							</div>

							<div class="alert alert-info">
								<span class="wlsm-font-bold"><?php esc_html_e( 'First Admission Preview:', 'school-management' ); ?></span>
								<span class="wlsm-admission-text">
									<?php echo esc_html( $admission_prefix . str_pad( 1 + $admission_base, $admission_padding, '0', STR_PAD_LEFT ) ); ?>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-md-12 text-center">
					<button type="submit" class="btn btn-sm btn-primary" id="wlsm-save-school-btn">
						<?php
						if ( $school ) {
							?>
							<i class="fas fa-save"></i>&nbsp;
							<?php
							esc_html_e( 'Update School', 'school-management' );
						} else {
							?>
							<i class="fas fa-plus-square"></i>&nbsp;
							<?php
							esc_html_e( 'Add New School', 'school-management' );
						}
						?>
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
