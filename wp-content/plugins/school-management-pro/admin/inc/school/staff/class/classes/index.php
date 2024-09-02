<?php
defined( 'ABSPATH' ) || die();
?>
<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
				<i class="fas fa-layer-group"></i>
				<?php esc_html_e( 'Classes and Sections', 'school-management' ); ?>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-staff-classes-table">
				<thead>
					<tr class="text-white bg-primary">
						<th scope="col"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Number of Sections', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Total Students', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Class Teacher', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
