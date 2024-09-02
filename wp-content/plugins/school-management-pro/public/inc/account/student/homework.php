<?php
defined('ABSPATH') || die();

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/staff/WLSM_M_Staff_Class.php';

$section_id = $student->section_id;
$student_id = $student->ID;
$session_id = $student->session_id;
$homeworks_per_page = WLSM_M::homeworks_per_page();

$page_url = WLSM_M_Staff_Class::get_homeworks_submisson_page();

$homeworks_query = WLSM_M::homeworks_query();

$homeworks_total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(1) FROM ({$homeworks_query}) AS combined_table", $school_id, $session_id, $section_id));

$homeworks_page = isset($_GET['homeworks_page']) ? absint($_GET['homeworks_page']) : 1;

$homeworks_page_offset = ($homeworks_page * $homeworks_per_page) - $homeworks_per_page;

$homeworks = $wpdb->get_results($wpdb->prepare($homeworks_query . ' ORDER BY hw.homework_date DESC LIMIT %d, %d', $school_id, $session_id, $section_id, $homeworks_page_offset, $homeworks_per_page));

$homeworks_student = $wpdb->get_results(('SELECT ID, student_id, created_at, description FROM ' .WLSM_HOMEWORK_SUBMISSION.' as hs WHERE hs.student_id = '.$student_id.' ORDER BY hs.ID DESC '));
?>
<div class="wlsm-content-area wlsm-section-homeworks wlsm-student-homeworks">

	<div class="wlsm-st-main-title">
		<span>
			<?php esc_html_e('Home Work', 'school-management'); ?>
		</span>
		<a href="<?php echo esc_url(add_query_arg(array('action' => 'submit-homework'), $current_page_url)); ?>" class="wlsm-btn float-right" id="wlsm-save-class-btn"><?php esc_html_e('Submit Homework', 'school-management'); ?></a>
	</div>
	<div class="wlsm-st-homeworks-section">
		<?php
		if (count($homeworks)) {
		?>
			<ul class="wlst-st-list wlsm-st-homeworks">
				<?php
				foreach ($homeworks as $key => $homework) {
				?>
					<li>
						<span>
							<?php echo esc_html(stripslashes($homework->title)); ?> <span class="wlsm-st-homework-date wlsm-font-bold"><?php echo esc_html(WLSM_Config::get_date_text($homework->homework_date)); ?></span>
							<a class="wlsm-st-view-homework wlsm-ml-1" data-homework="<?php echo esc_attr($homework->ID); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('st-view-homework-' . $homework->ID)); ?>" href="#" data-message-title="<?php echo esc_attr(stripslashes($homework->title)); ?>" data-close="<?php echo esc_attr__('Close', 'school-management'); ?>">
								<?php esc_html_e('View', 'school-management'); ?>
							</a>
						</span>
					</li>
				<?php
				}
				?>
			</ul>
			<div class="wlsm-text-right wlsm-font-medium wlsm-font-bold wlsm-mt-2">
				<?php
				echo paginate_links(
					array(
						'base'      => add_query_arg('homeworks_page', '%#%'),
						'format'    => '',
						'prev_text' => '&laquo;',
						'next_text' => '&raquo;',
						'total'     => ceil($homeworks_total / $homeworks_per_page),
						'current'   => $homeworks_page,
					)
				);
				?>
			</div>
		<?php
		} else {
		?>
			<div>
				<span class="wlsm-font-medium wlsm-font-bold">
					<?php esc_html_e('There is no homework.', 'school-management'); ?>
				</span>
			</div>
		<?php
		}
		?>

	</div>
<div class="wlsm-st-main-title wlsm-mt-3">
		<span>
		<?php esc_html_e( 'Recent Homework Submission', 'school-management' ); ?>
		</span>
	</div>

	<div class="wlsm-st-leaves-section">
		<?php
		if ( count( $homeworks_student ) ) {
		?>
		<!-- Student homework requests. -->
		<div class="wlsm-table-section">

			<div class="table-responsive w-100 wlsm-w-100">
				<table class="table table-bordered wlsm-student-payment-history-table wlsm-w-100">
					<thead>
						<tr class="bg-primary text-white">
							<th><?php esc_html_e( 'Disciption', 'school-management' ); ?></th>
							<th><?php esc_html_e( 'Date', 'school-management' ); ?></th>
							<th class="text-nowrap"><?php esc_html_e( 'Action', 'school-management' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $homeworks_student as $row ) {
						?>
						<tr>
							<td>
								<?php echo esc_html( WLSM_Config::limit_string( WLSM_M_Staff_Class::get_name_text( $row->description ) ) ); ?>
							</td>
							<td> <?php echo '<span class="wlsm-font-bold">' . esc_html( WLSM_Config::get_date_text( $row->created_at ) ) . '</span>'; ?>
							</td>
							<td>
								<a class="btn btn-primary btn-sm ml-2" id="homework_submission_edit" href="<?php echo esc_url(($page_url).'/?action=submit-homework&id='.($row->ID)) ?>" ><?php esc_html_e( 'Edit', 'school-management' ); ?></a>
							</td>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="wlsm-text-right wlsm-font-medium wlsm-font-bold wlsm-mt-2">
		<?php
		echo paginate_links(
			array(
				'base'      => add_query_arg('homeworks_page', '%#%'),
				'format'    => '',
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
				'total'     => ceil($homeworks_total / $homeworks_per_page),
				'current'   => $homeworks_page,
			)
		);
		?>
		</div>
		<?php
		} else {
		?>
		<div>
			<span class="wlsm-font-medium wlsm-font-bold">
				<?php esc_html_e( "You haven't made any homework submission yet.", 'school-management' ); ?>
			</span>
		</div>
		<?php
		}
		?>
	</div>
</div>
</div>
