<?php
defined( 'ABSPATH' ) || die();

class WLSM_Export {
	public static function export_and_close_csv_file( $f, $filename ) {
		fseek( $f, 0 );

		header( 'Content-Type: text/html' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '";' );

		fpassthru( $f );

		fclose( $f );

		exit;
	}
}
