<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'public/inc/account/student/partials/navigation.php';

$class_school_id = $student->class_school_id;

$study_materials_per_page = WLSM_M::study_materials_per_page();

$study_materials_query = WLSM_M::study_materials_query();

$study_materials_total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(1) FROM ({$study_materials_query}) AS combined_table", $class_school_id ) );

$study_materials_page = isset( $_GET['study_materials_page'] ) ? absint( $_GET['study_materials_page'] ) : 1;

$study_materials_page_offset = ( $study_materials_page * $study_materials_per_page ) - $study_materials_per_page;

$study_materials = $wpdb->get_results( $wpdb->prepare( $study_materials_query . ' ORDER BY cssm.ID DESC LIMIT %d, %d', $class_school_id, $study_materials_page_offset, $study_materials_per_page ) );
?>
<div class="wlsm-content-area wlsm-section-study-materials wlsm-student-study-materials">
	<div class="wlsm-st-main-title">
		<span>
		<?php esc_html_e( 'Study Materials', 'school-management' ); ?>
		</span>
	</div>

	<div class="wlsm-st-study-materials-section">
		<?php
		if ( count( $study_materials ) ) {
		?>
		<ul class="wlst-st-list wlsm-st-study-materials">
			<?php
			foreach ( $study_materials as $key => $study_material ) {
			?>
			<li>
				<span>
					<?php echo esc_html( stripslashes( $study_material->title ) ); ?> <span class="wlsm-st-study-material-date wlsm-font-bold"><?php echo esc_html( WLSM_Config::get_date_text( $study_material->created_at ) ); ?></span>
					<a class="wlsm-st-view-study-material wlsm-ml-1" data-study-material="<?php echo esc_attr( $study_material->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'st-view-study-material-' . $study_material->ID ) ); ?>" href="#" data-message-title="<?php echo esc_attr( stripslashes( $study_material->title ) ); ?>" data-close="<?php echo esc_attr__( 'Close', 'school-management' ); ?>">
						<?php esc_html_e( 'View', 'school-management' ); ?>
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
				'base'      => add_query_arg( 'study_materials_page', '%#%' ),
				'format'    => '',
				'prev_text' => '&laquo;',
				'next_text' => '&raquo;',
				'total'     => ceil( $study_materials_total / $study_materials_per_page ),
				'current'   => $study_materials_page,
			)
		);
		?>
		</div>
		<?php
		} else {
		?>
		<div>
			<span class="wlsm-font-medium wlsm-font-bold">
				<?php esc_html_e( 'There is no study material.', 'school-management' ); ?>
			</span>
		</div>
		<?php
		}
		?>
	</div>
</div>
