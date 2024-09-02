<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Examination.php';

$page_url = WLSM_M_Staff_Examination::get_academic_report_page_url();

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$report_id    = absint( $_GET['id'] );
	
	$current_user = WLSM_M_Role::can( 'manage_exams' );
	$school_id    = $current_user['school']['id'];
	$session_id   = $current_user['session']['ID'];
	
	$report       = WLSM_M_Staff_Examination::get_academic_report( $school_id, $report_id );
	$class_id     = $report->class_id;
	$students     = WLSM_M_Staff_Class::get_class_students( $school_id, $session_id, $class_id, false );
}

?>

<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<?php 
					if ( isset( $report ) ) {
						echo $report->label ;
					} else {
						esc_html_e( 'Academic Reports', 'school-management' );
					}
					?>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-student-academic-report-table">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Enrollment No.', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Name', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Section', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Roll Number', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ( $students as $row ) {
						?>
						<tr>
							<td>
								<?php echo esc_html( $row->enrollment_number ); ?>
							</td>
							<td>
								<input type="hidden" name="student[<?php echo esc_attr( $row->ID ); ?>]" value="<?php echo esc_attr( $row->ID ); ?>">
								<?php echo esc_html( WLSM_M_Staff_Class::get_name_text( $row->name ) ); ?>
							</td>
							<td>
								<?php echo esc_html( WLSM_M_Staff_Class::get_section_label_text( $row->section_label ) ); ?>
							</td>
							<td>
								<?php echo esc_html( WLSM_M_Staff_Class::get_roll_no_text( $row->roll_number ) ); ?>
							</td>
							<td>
								<a class="text-primary wlsm-get-academic-report" data-nonce="<?php echo esc_attr( wp_create_nonce( 'get-academic-report-' . $row->ID ) ); ?>" data-student="<?php echo esc_attr( $row->ID ); ?>" data-report="<?php echo esc_attr( $report_id ); ?>" href="#" data-message-title="<?php echo esc_attr__( 'Subject-wise Results', 'school-management' ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>"><span class="dashicons dashicons-search"></span></a>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
