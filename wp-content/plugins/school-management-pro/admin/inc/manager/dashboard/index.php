<?php
defined( 'ABSPATH' ) || die();

require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_School.php';
require_once WLSM_PLUGIN_DIR_PATH . 'includes/helpers/WLSM_M_Role.php';

$current_school_id = NULL;

$user_info = WLSM_M_Role::get_user_info();

if ( $user_info['current_school'] ) {
	$current_school_id = $user_info['current_school']['id'];
}

global $wpdb;
$schools = $wpdb->get_results( 'SELECT s.ID, s.label, s.phone, s.is_active, COUNT(cs.ID) as classes_count, s.is_active FROM ' . WLSM_SCHOOLS . ' as s LEFT OUTER JOIN ' . WLSM_CLASS_SCHOOL . ' as cs ON cs.school_id = s.ID GROUP BY s.ID' );
?>

<div class="wlsm container-fluid">
	<div class="wlsm-main-header card col">
		<div class="card-header">
			<h1 class="h3 text-center">
				<i class="fas fa-school text-primary"></i>
				<?php esc_html_e( 'Dashboard', 'school-management' ); ?>
			</h1>
		</div>
	</div>
	<?php
	if ( count( $schools ) ) {
	?>
	<div class="row">
		<?php
		foreach ( $schools as $school ) {
		?>
		<div class="col-sm-6 col-md-4">
			<a href="javascript:void(0)" class="wlsm-school-card-link" data-school="<?php echo esc_attr( $school->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'set-school-' . $school->ID ) ); ?>">
				<div class="card wlsm-school-card <?php if ( $school->ID === $current_school_id ) { echo 'wlsm-school-card-border'; } ?>">
					<div class="card-body">
						<h6 class="card-title wlsm-school-card-title wlsm-school-card-dark"><?php echo esc_html( WLSM_M_School::get_label_text( $school->label ) ); ?></h6>
						<ul>
							<li>
								<span class="wlsm-school-card-light wlsm-font-bold"><?php esc_html_e( 'Phone:', 'school-management' ); ?></span>
								<span class="wlsm-school-card-dark wlsm-font-bold"><?php echo esc_html( $school->phone ); ?></span>
							</li>
							<li>
								<span class="wlsm-school-card-light wlsm-font-bold"><?php esc_html_e( 'Total Classes:', 'school-management' ); ?></span>
								<span class="wlsm-school-card-dark wlsm-font-bold">
									<?php
									if ( $school->classes_count ) {
										echo esc_html( $school->classes_count );
									} else {
										esc_html_e( 'Assign Classes', 'school-management');
									}
									?>
								</span>
							</li>
							<li>
								<span class="wlsm-school-card-light wlsm-font-bold"><?php esc_html_e( 'Status:', 'school-management' ); ?></span>
								<span class="wlsm-school-card-dark wlsm-font-bold"><?php echo esc_html( WLSM_M_School::get_status_text( $school->is_active ) ); ?></span>
							</li>
						</ul>
					</div>
				</div>
			</a>
		</div>
		<?php
		}
		?>
	</div>
	<?php
	} else {
	?>
	<div class="row mt-2">
		<div class="col-md-12">
			<div class="alert alert-secondary text-center">
				<?php esc_html_e( 'There is no school.', 'school-management' ); ?>&nbsp;
				<a href="<?php echo esc_url( WLSM_M_School::get_page_url() . '&action=save' ); ?>" class="btn btn-sm btn-primary ">
					<?php esc_html_e( 'Add a New School', 'school-management' ); ?>
				</a>
			</div>
		</div>
	</div>
	<?php
	}
	?>
</div>
