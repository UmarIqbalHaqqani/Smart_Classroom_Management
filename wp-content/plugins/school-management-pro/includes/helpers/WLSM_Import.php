<?php
defined( 'ABSPATH' ) || die();

class WLSM_Import {
	public static function get_csv_error_msg( $row, $column, $space = ' ' ) {
		return $space . sprintf(
			wp_kses(
				/* translators: 1: CSV row number, 2: CSV column number */
				__( 'Error in CSV: <span class="wlsm-font-bold">row</span>: %1$s, <span class="wlsm-font-bold">column</span>: %2$s', 'school-management' ),
				array(
					'span' => array(
						'class' => array()
					)
				)
			),
			esc_html( $row ),
			esc_html( $column )
		);
	}
}
