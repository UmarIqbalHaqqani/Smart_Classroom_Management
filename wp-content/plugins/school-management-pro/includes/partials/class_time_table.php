<?php
defined( 'ABSPATH' ) || die();
?>
<tbody>
	<?php
	foreach ( WLSM_Helper::days_list() as $key => $day ) {
		$routines = WLSM_M_Staff_Class::get_section_routines_by_day( $school_id, $section_id, $key );
	?>
	<tr>
		<td class="wlsm-font-bold"><?php echo esc_html( $day ); ?></td>
		<?php
		foreach ( $routines as $routine ) {
		?>
		<td>
			<div class="clearfix">
				<span class="wlsm-class-timetable-subject wlsm-font-bold float-left">
					<?php
					printf(
						wp_kses(
							/* translators: 1: subject label, 2: subject code */
							_x( '%1$s (%2$s)', 'Subject', 'school-management' ),
							array( 'span' => array( 'class' => array() ) )
						),
						esc_html( WLSM_M_Staff_Class::get_subject_label_text( $routine->subject_label ) ),
						esc_html( $routine->subject_code )
					);
					?>
				</span>

				<?php if ( isset( $from_staff ) ) { ?>
				<span class="wlsm-class-routine-action float-md-right clearfix">
					<a class="text-primary" href="<?php echo esc_url( $page_url . "&action=save&id=" . $routine->ID ); ?>">
						<span class="dashicons dashicons-edit wlsm-font-large"></span>
					</a>
					<a class="text-danger wlsm-delete-routine" data-routine="<?php echo esc_attr( $routine->ID ); ?>" data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete-routine-' . $routine->ID ) ); ?>" data-message-title="<?php esc_attr_e( 'Please Confirm!', 'school-management' ); ?>" data-message-content="<?php esc_attr_e( 'This will delete the routine.', 'school-management' ); ?>" data-cancel="<?php esc_attr_e( 'Cancel', 'school-management' ); ?>" href="<?php echo esc_url( $page_url . "&action=save&id=" . $routine->ID ); ?>" data-submit="<?php esc_attr_e( 'Confirm', 'school-management' ); ?>">
						<span class="dashicons dashicons-trash wlsm-font-large"></span>
					</a>
				</span>
				<?php } ?>
			</div>

			<div class="wlsm-class-timetable-time">
				<?php echo esc_html( WLSM_Config::get_time_text( $routine->start_time ) . ' - ' . WLSM_Config::get_time_text( $routine->end_time ) ); ?>
			</div>

			<div class="wlsm-class-timetable-room-number">
				<?php
				printf(
					wp_kses(
						/* translators: %s: room number */
						__( '<span class="wlsm-font-bold">Room No.</span> %s', 'school-management' ),
						array( 'span' => array( 'class' => array() ) )
					),
					esc_html( $routine->room_number )
				);
				?>
			</div>

			<?php if ( $routine->teacher_name ) { ?>
			<div class="wlsm-class-timetable-teacher">
				<em>
				<?php
				printf(
					wp_kses(
						/* translators: %s: teacher name in class time table */
						_x( '<span>- %s</span>', 'Teacher', 'school-management' ),
						array( 'span' => array( 'class' => array() ) )
					),
					esc_html( WLSM_M_Staff_Class::get_name_text( $routine->teacher_name ) )
				);
				?>
				</em>
			</div>
			<?php } ?>
		</td>
		<?php
		}
		?>
	</tr>
	<?php
	}
	?>
</tbody>
