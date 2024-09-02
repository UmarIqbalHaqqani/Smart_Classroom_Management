<?php
defined( 'ABSPATH' ) || die();
?>
<div class="tab-pane fade" id="wlsm-school-shortcodes" role="tabpanel" aria-labelledby="wlsm-school-shortcodes-tab">

	<div class="row">
		<div class="col-md-12">
			<ul class="list-group list-group-flush">
				<li class="list-inline-item">
					<div class="alert alert-light">
						<?php
						printf(
							/* translators: %s: session label */
							__( 'To display fees submission form on a page or post for default session %s, use shortcode', 'school-management' ),
							$default_session_label
						);
						?>:<br>
							<span id="wlsm_school_management_fees_default_session_shortcode" class="wlsm-font-bold text-dark">[school_management_fees school_id="<?php echo esc_html( $school_id ); ?>"]</span>
						<button id="wlsm_school_management_fees_default_session_copy_btn" class="btn btn-outline-success btn-sm" type="button">
							<?php esc_html_e( 'Copy', 'school-management' ); ?>
						</button>
					</div>
				</li>

				<li class="list-inline-item">
					<div class="alert alert-light">
						<?php esc_html_e( 'To display admission inquiry form on a page or post, use shortcode', 'school-management' ); ?>:<br>
							<span id="wlsm_school_management_inquiry_shortcode" class="wlsm-font-bold text-dark">[school_management_inquiry school_id="<?php echo esc_html( $school_id ); ?>"]</span>
						<button id="wlsm_school_management_inquiry_copy_btn" class="btn btn-outline-success btn-sm" type="button">
							<?php esc_html_e( 'Copy', 'school-management' ); ?>
						</button>
					</div>
				</li>

				<li class="list-inline-item">
					<div class="alert alert-light">
						<?php esc_html_e( 'To display registration form on a page or post, use shortcode', 'school-management' ); ?>:<br>
							<span id="wlsm_school_management_registration_shortcode" class="wlsm-font-bold text-dark">[school_management_registration school_id="<?php echo esc_html( $school_id ); ?>"]</span>
						<button id="wlsm_school_management_registration_copy_btn" class="btn btn-outline-success btn-sm" type="button">
							<?php esc_html_e( 'Copy', 'school-management' ); ?>
						</button>
					</div>
				</li>

				<li class="list-inline-item">
					<div class="alert alert-light">
						<?php esc_html_e( 'To display staff_registration form on a page or post, use shortcode', 'school-management' ); ?>:<br>
							<span id="wlsm_school_management_staff_registration_shortcode" class="wlsm-font-bold text-dark">[school_management_staff_registration school_id="<?php echo esc_html( $school_id ); ?>"]</span>
						<button id="wlsm_school_management_staff_registration_copy_btn" class="btn btn-outline-success btn-sm" type="button">
							<?php esc_html_e( 'Copy', 'school-management' ); ?>
						</button>
					</div>
				</li>

				<li class="list-inline-item">
					<div class="alert alert-light">
						<?php esc_html_e( 'To display noticeboard on a page or post, use shortcode', 'school-management' ); ?>:<br>
							<span id="wlsm_school_management_noticeboard_shortcode" class="wlsm-font-bold text-dark">[school_management_noticeboard school_id="<?php echo esc_html( $school_id ); ?>"]</span>
						<button id="wlsm_school_management_noticeboard_copy_btn" class="btn btn-outline-success btn-sm" type="button">
							<?php esc_html_e( 'Copy', 'school-management' ); ?>
						</button>
					</div>
				</li>

				<li class="list-inline-item">
					<div class="alert alert-light">
						<?php esc_html_e( 'To display exam time table form on a page or post, use shortcode', 'school-management' ); ?>:<br>
							<span id="wlsm_school_management_exam_time_table_shortcode" class="wlsm-font-bold text-dark">[school_management_exam_time_table school_id="<?php echo esc_html( $school_id ); ?>"]</span>
						<button id="wlsm_school_management_exam_time_table_copy_btn" class="btn btn-outline-success btn-sm" type="button">
							<?php esc_html_e( 'Copy', 'school-management' ); ?>
						</button>
					</div>
				</li>

				<li class="list-inline-item">
					<div class="alert alert-light">
						<?php esc_html_e( 'To display exam admit cards form on a page or post, use shortcode', 'school-management' ); ?>:<br>
							<span id="wlsm_school_management_exam_admit_card_shortcode" class="wlsm-font-bold text-dark">[school_management_exam_admit_card school_id="<?php echo esc_html( $school_id ); ?>"]</span>
						<button id="wlsm_school_management_exam_admit_card_copy_btn" class="btn btn-outline-success btn-sm" type="button">
							<?php esc_html_e( 'Copy', 'school-management' ); ?>
						</button>
					</div>
				</li>

				<li class="list-inline-item">
					<div class="alert alert-light">
						<?php esc_html_e( 'To display exam results form on a page or post, use shortcode', 'school-management' ); ?>:<br>
							<span id="wlsm_school_management_exam_result_shortcode" class="wlsm-font-bold text-dark">[school_management_exam_result school_id="<?php echo esc_html( $school_id ); ?>"]</span>
						<button id="wlsm_school_management_exam_result_copy_btn" class="btn btn-outline-success btn-sm" type="button">
							<?php esc_html_e( 'Copy', 'school-management' ); ?>
						</button>
					</div>
				</li>

				<li class="list-inline-item">
					<div class="alert alert-light">
						<?php esc_html_e( 'To display certificate form on a page or post, use shortcode', 'school-management' ); ?>:<br>
							<span id="wlsm_school_management_certificate_shortcode" class="wlsm-font-bold text-dark">[school_management_certificate school_id="<?php echo esc_html( $school_id ); ?>"]</span>
						<button id="wlsm_school_management_certificate_copy_btn" class="btn btn-outline-success btn-sm" type="button">
							<?php esc_html_e( 'Copy', 'school-management' ); ?>
						</button>
					</div>
				</li>

				<li class="list-inline-item">
					<div class="alert alert-light">
						<?php esc_html_e( 'To display lesson form on a page or post, use shortcode', 'school-management' ); ?>:<br>
							<span id="wlsm_school_management_lesson_shortcode" class="wlsm-font-bold text-dark">[school_management_lesson school_id="<?php echo esc_html( $school_id ); ?>"]</span>
						<button id="wlsm_school_management_lesson_copy_btn" class="btn btn-outline-success btn-sm" type="button">
							<?php esc_html_e( 'Copy', 'school-management' ); ?>
						</button>
					</div>
				</li>
			</ul>
		</div>
	</div>

</div>
