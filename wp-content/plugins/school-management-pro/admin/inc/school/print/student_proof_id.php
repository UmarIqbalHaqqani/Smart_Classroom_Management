<?php defined( 'ABSPATH' ) || die(); 

$photo_id_proof = $student->id_proof;
?>


<div class="wlsm-container d-flex mb-2">
	<div class="col-md-12 wlsm-text-center">
		<img src="<?php echo wp_get_attachment_url($photo_id_proof); ?>" alt="sid">
	</div>
</div>