<?php
defined( 'ABSPATH' ) || die();

$event_title = WLSM_M_Staff_Class::get_name_text( $event->title );
$event_date  = WLSM_Config::get_date_text( $event->event_date );
$description = $event->description;
$image_id    = $event->image_id;
$image_url   = wp_get_attachment_url( $image_id );

$student_joined = $event->student_joined;
