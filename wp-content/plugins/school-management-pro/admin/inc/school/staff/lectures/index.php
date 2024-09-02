<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Transport.php';

$lecture_page_url = WLSM_M_Staff_Transport::get_lecture_page_url();
$chapter_page_url = WLSM_M_Staff_Transport::get_chapter_page_url();
?>

<div class="row">
	<div class="col-md-12">
		<div class="text-center wlsm-section-heading-block">
			<span class="wlsm-section-heading">
			<i class="fas fa-home"></i>
				<?php esc_html_e( 'Lessons', 'school-management' ); ?>
			</span>
			<span class="float-md-right">
				<a href="<?php echo esc_url( $chapter_page_url); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-eye"></i>&nbsp;
					<?php echo esc_html( 'Chapter\'s', 'school-management' ); ?>
				</a>
				<a href="<?php echo esc_url( $lecture_page_url . '&action=save' ); ?>" class="btn btn-sm btn-outline-light">
					<i class="fas fa-plus-square"></i>&nbsp;
					<?php echo esc_html( 'Add New Lesson', 'school-management' ); ?>
				</a>
			</span>
		</div>
		<div class="wlsm-table-block">
			<table class="table table-hover table-bordered" id="wlsm-lecture-table">
				<thead>
					<tr class="text-white bg-primary">
						<!-- <th scope="col" class="text-nowrap"><?php esc_html_e( 'ID', 'school-management' ); ?></th> -->
						<th scope="col"><?php esc_html_e( 'Title', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Class', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Subject', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Chapter', 'school-management' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Created At', 'school-management' ); ?></th>
						<th scope="col" class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
