<?php
defined( 'ABSPATH' ) || die();

$page_url = WLSM_M_Staff_General::get_certificates_page_url();

$school_id  = $current_school['id'];
$session_id = $current_session['ID'];

$certificate = NULL;

if ( isset( $_GET['id'] ) && ! empty( $_GET['id'] ) ) {
	$id          = absint( $_GET['id'] );
	$certificate = WLSM_M_Staff_General::fetch_certificate( $school_id, $id );
}

if ( ! $certificate ) {
	die;
}

$label = $certificate->label;
?>
<div class="row">
	<div class="col-md-12">
		<div class="mt-3 text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading-box">
				<span class="wlsm-section-heading">
					<?php
					printf(
						wp_kses(
							/* translators: %s: certificate title */
							__( 'Certificates Distributed: %s', 'school-management' ),
							array(
								'span' => array( 'class' => array() )
							)
						),
						esc_html( $certificate->label )
					);
					?>
				</span>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $page_url . "&action=distribute&id=" . $certificate->ID ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-certificate"></i>&nbsp;
					<?php esc_html_e( 'Distribute Certificate', 'school-management' ); ?>
				</a>
				<a href="<?php echo esc_url( $page_url ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-certificate"></i>&nbsp;
					<?php esc_html_e( 'View All', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-certificates-distributed-table" data-certificate="<?php echo esc_attr( $certificate->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'certificate-' . $certificate->ID ) ); ?>">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Certificate No.', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Student Name', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Enrollment Number', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Section', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Roll Number', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Date Issued', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Phone', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
