jQuery(document).ready(function($) {

	$("#loginpressActiveFree .loginpress-btn").on( 'click', function(e) {

		e.preventDefault();
	
		$("#loginpressActiveFree").hide();
		$("#loginpressActivatingFree").show();
	
		var path = 'loginpress/loginpress.php';
		var _wpnonce = loginpress_pro_local.active_nonce;
	
		$.ajax({
			url: ajaxurl,
			type: 'post',
			data:{
				path : path,
				action: 'loginpress_activate_free',
				_wpnonce: _wpnonce,
			},
			success: function(response) {
	
				// setTimeout(function(){
					$("#loginpressActivatingFree").hide();
		
					$("#loginpressActivatedFree").fadeIn( 1000, function() {
						$('.circle-loader').addClass('load-complete');
						$('.checkmark').show();
					} );
		
				// }, 2000);
		
				setTimeout(function() {
					location.reload();
				}, 4000);
	
			},
			error: function(xhr, textStatus, errorThrown) {
				// console.log('Ajax Not Working');
			}
		}); // end ajax.
	});
  
	$('#loginpressInstallFree .loginpress-btn').on('click', function(e) {
  
		e.preventDefault();
		var el = $(this);
	
		$("#loginpressInstallFree").hide();
		$("#loginpressInstallingFree").show();
	
		var _wpnonce = loginpress_pro_local.update_nonce;
		var slug = 'loginpress';
		$.ajax({
			url : ajaxurl,
			type : 'post',
			data:{
				slug : slug,
				_wpnonce : _wpnonce,
				action: 'install-plugin',
			},
			success : function( res ) {
				if ( res.success ) {
					setTimeout(function(){
					$("#loginpressInstallingFree").hide();
		
					$("#loginpressActivatedFree").fadeIn( 1000, function() {
						$('.circle-loader').addClass('load-complete');
						$('.checkmark').show();
					});
		
					}, 2000);
		
					window.location = res.data.activateUrl;
				} else {
					alert( res.data.errorMessage );
				}
	
			},
			error: function(xhr, textStatus, errorThrown) {
			// console.log('Ajax Not Working');
			}
		});
	});
	
});
