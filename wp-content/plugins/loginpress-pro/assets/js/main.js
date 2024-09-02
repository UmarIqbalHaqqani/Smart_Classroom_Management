jQuery(document).ready( function($) {

  	$("#loginpress-license").on('click', function(e) {

		e.preventDefault();
		var loginpress_license = $("#license_key").val();

    	// console.log(loginpress_license);

		$.ajax({
			url: loginpressLicense.ajaxurl,
			nonce: loginpressLicense.license_nonce,
			type: 'post',
			data: 'loginpress_license=' + loginpress_license +
				'&action=loginpress_activate_license',
			success: function(response) {

				// console.log(response);
			},
			error: function(xhr, textStatus, errorThrown) {
				// console.log('Ajax Not Working');
			}
		}); // end ajax.
  	});
	  $('#loginpress_autologin_users_select').on('change', function(){
		 $('[name="loginpress_autologin_users_length"]').val($(this).val());
		 $('[name="loginpress_autologin_users_length"]').trigger('change');
	  });
	  $('#loginpress_login_redirect_users_select').on('change', function(){
		 $('[name="loginpress_login_redirect_users_length"]').val($(this).val());
		 $('[name="loginpress_login_redirect_users_length"]').trigger('change');
	  });
	  $('#loginpress_login_redirect_roles_select').on('change', function(){
		 $('[name="loginpress_login_redirect_roles_length"]').val($(this).val());
		 $('[name="loginpress_login_redirect_roles_length"]').trigger('change');
	  });
	  $('#loginpress_limit_login_log_select').on('change', function(){
		 $('[name="loginpress_limit_login_log_length"]').val($(this).val());
		 $('[name="loginpress_limit_login_log_length"]').trigger('change');
	  });
	  $('#loginpress_limit_login_whitelist_select').on('change', function(){
		 $('[name="loginpress_limit_login_whitelist_length"]').val($(this).val());
		 $('[name="loginpress_limit_login_whitelist_length"]').trigger('change');
	  });
	  $('#loginpress_limit_login_blacklist_select').on('change', function(){
		 $('[name="loginpress_limit_login_blacklist_length"]').val($(this).val());
		 $('[name="loginpress_limit_login_blacklist_length"]').trigger('change');
	  });

	$("#deactivate-loginpress").on('click', function(e) {

		e.preventDefault();
		
		$.ajax({
			url: loginpressLicense.ajaxurl,
			nonce: loginpressLicense.license_nonce,
			type: 'post',
			data: 'action=loginpress_deactivate_license',
			success: function(response) {

				// console.log(response);
			},
			error: function(xhr, textStatus, errorThrown) {
				// console.log('Ajax Not Working');
			}
		}); // end ajax.
	});

	function lognPressShowRecatchaSettings() {

		if ( $('#wpb-loginpress_setting\\[enable_repatcha\\]').is(":checked") ) {

			$('tr.recaptcha_type').show();
			$('tr.good_score').hide();
			var recaptchaType = $('tr.recaptcha_type select').val();
			// var recaptchaType = 'v2-robot'; // 2.1.3
			if ( recaptchaType == 'v2-robot' ) {

				$('tr.captcha_theme').show();
				$('tr.captcha_language').show();
				$('tr.site_key').show();
				$('tr.secret_key').show();
				$('tr.site_key_v2_invisible').hide();
				$('tr.secret_key_v2_invisible').hide();
				$('tr.site_key_v3').hide();
				$('tr.secret_key_v3').hide();
			}

			if ( recaptchaType == 'v2-invisible' ) {

				// $('tr.captcha_language').show();
				$('tr.site_key_v2_invisible').show();
				$('tr.secret_key_v2_invisible').show();
				$('tr.site_key').hide();
				$('tr.secret_key').hide();
				$('tr.site_key_v3').hide();
				$('tr.secret_key_v3').hide();
			}

			if ( recaptchaType == 'v3' ) {

				$('tr.site_key_v3').show();
				$('tr.secret_key_v3').show();
				$('tr.good_score').show();
				$('tr.site_key').hide();
				$('tr.secret_key').hide();
				$('tr.site_key_v2_invisible').hide();
				$('tr.secret_key_v2_invisible').hide();
			}

			// $('tr.site_key').show();
			// $('tr.secret_key').show();
			$('tr.captcha_enable').show();
			$('tr.woo_captcha_enable').show();


		} else {
			$('tr.recaptcha_type').hide();
			$('tr.site_key').hide();
			$('tr.secret_key').hide();
			$('tr.captcha_theme').hide();
			$('tr.captcha_language').hide();
			$('tr.captcha_enable').hide();
			$('tr.woo_captcha_enable').hide();
			$('tr.good_score').hide();
			$('tr.site_key_v2_invisible').hide();
			$('tr.secret_key_v2_invisible').hide();
			$('tr.site_key_v3').hide();
			$('tr.secret_key_v3').hide();
		}
	}

	$("#wpb-loginpress_setting\\[enable_repatcha\\]").on('click', function() {
console.log('sd');
		lognPressShowRecatchaSettings();
	});
	// generate_select('.selectbox');
	
	$('tr.recaptcha_type select').on( 'change' , function() {

		var recaptchaType = $('tr.recaptcha_type select').val();

		if ( recaptchaType == 'v2-robot' ) {

			$('tr.site_key').show();
			$('tr.secret_key').show();
			$('tr.captcha_theme').show();
			$('tr.captcha_language').show();
			$('tr.site_key_v2_invisible').hide();
			$('tr.secret_key_v2_invisible').hide();
			$('tr.site_key_v3').hide();
			$('tr.secret_key_v3').hide();
			$('tr.good_score').hide();

		} else if ( recaptchaType == 'v2-invisible' ) {

			$('tr.site_key_v2_invisible').show();
			$('tr.secret_key_v2_invisible').show();
			$('tr.good_score').hide();
			$('tr.site_key').hide();
			$('tr.secret_key').hide();
			$('tr.site_key_v3').hide();
			$('tr.secret_key_v3').hide();
			$('tr.captcha_theme').hide();
			$('tr.captcha_language').hide();

		} else if ( recaptchaType == 'v3' ) {

			$('tr.site_key_v3').show();
			$('tr.secret_key_v3').show();
			$('tr.good_score').show();
			$('tr.site_key').hide();
			$('tr.secret_key').hide();
			$('tr.site_key_v2_invisible').hide();
			$('tr.secret_key_v2_invisible').hide();
			$('tr.captcha_theme').hide();
			$('tr.captcha_language').hide();
		}

	} );

	$('#loginpress_pro_license-tab').parent().on('click', function(){
		// Simulate a mouse click:
		window.location.href = loginpressLicense.admin_url + "/admin.php?page=loginpress-license";

	});

	$(window).on( 'load', function() { lognPressShowRecatchaSettings();
	 } );

	// Add class for unapproved user row.
	$('.submitapprove').parent().parent().parent().parent().addClass('loginpress-unapproved-user-row');
	// Add class for approved user row.
	$('.submitunapprove').parent().parent().parent().parent().addClass('loginpress-approved-user-row');

} );
