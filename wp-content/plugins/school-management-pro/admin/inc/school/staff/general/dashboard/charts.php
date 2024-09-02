<?php
defined( 'ABSPATH' ) || die();

$ajax_url = esc_url( admin_url( 'admin-ajax.php' ) );

$date       = new DateTime();
$month_year = $date->format('m-Y');
$start      = new DateTime( $current_session['start_date'] );
$end        = new DateTime( $current_session['end_date'] );
$interval   = DateInterval::createFromDateString('1 month');
$period     = new DatePeriod( $start, $interval, $end );
