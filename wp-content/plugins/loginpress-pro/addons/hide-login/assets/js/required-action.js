jQuery(document).ready(function($) {


	$(".hidelogin-slug-input").on('change paste keyup', function() {
		$('.hidelogin-copy-svg').hide();
	});
    $(document).on('click','.hide-login_site', function () {
        var valsrch = $('.hidelogin-slug-input').val();
        $('.hidelogin-slug-input').val('').focus().val(valsrch);
    });
    $("#loginpress_reset_login_slug").on('click', function() {

        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: 'reset_login_slug',
                security: loginpress_hidelogin_local.security,
            },
            success: function(res) {

                location.reload();
            },
            error: function(xhr, textStatus, errorThrown) {
                // console.log('Ajax Not Working');
            }
        }); // !Ajax.
    });
	$('#loginpress_hidelogin .form-table input[type="hidelogin"], #loginpress_hidelogin .form-table input[type="email"]').on('keydown', function (evt) {
		if (evt.keyCode == 13) {
			evt.preventDefault();
		  }
	});

	$(document).on( 'click', '.hidelogin-copy-code', function(e){

			if( '' !== $('.hidelogin-slug-input').val() ) {
			let copyText = $('.hidelogin_slug_hidden').val();

			const elem = document.createElement('textarea');
			elem.value = copyText;
			document.body.appendChild(elem);
			elem.select();
			document.execCommand('copy');
			document.body.removeChild(elem);

		} else {

			const elem = document.createElement('textarea');
			elem.value = $('.rename_login_slug .description strong a').attr('href');
			document.body.appendChild(elem);
			elem.select();
			document.execCommand('copy');
			document.body.removeChild(elem);
		}

		let $this = $(this);

		$this.parent().removeClass('copy');
		$this.parent().addClass('copied');
		$this.parent().addClass('hidelogin-copied');
		$('.hidelogin_slug_copied').show();

		setTimeout(function(){
			$('.hidelogin_slug_copied').hide();
			$this.parent().removeClass('copied');
			$this.parent().removeClass('hidelogin-copied');
		}, 2000);
	});

	$(document).on( 'mouseenter', '.hidelogin-copy-svg', function(e){
		if ($(this).parent().hasClass('copied')) {
			return;
		}
		$(this).parent().addClass('copy');
	});

	$(document).on( 'mouseleave', '.hidelogin-copy-svg', function(e){
		let el =  $(this).parent();
		el.removeClass('copy');

		setTimeout(function(){
		el.removeClass('copied');
		}, 2000);
	});

    /**
     * [loginpress_hidelogin_link]
     * @return {[string]}
     * @since 1.0.0
     * @version 1.1.4
     */
    function loginpress_hidelogin_link() {

        var hideLoginString = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        var result = "";
        while (result.length < 20) {
            result += hideLoginString.charAt(Math.floor(Math.random() * hideLoginString.length));
        }

        return result;
    }

    // Change slug on click Random button.
    $("#loginpress_create_new_hidelogin_slug").on("click", function(event) {
        event.preventDefault();
        var slug = loginpress_hidelogin_link();
        $("#loginpress_hidelogin\\[rename_login_slug\\]").val(slug);

    });

    $("#wpb-loginpress_hidelogin\\[is_rename_send_email\\]").on('click', function() {
        if ($('#wpb-loginpress_hidelogin\\[is_rename_send_email\\]').is(":checked")) {
            $('tr.rename_email_send_to').show();
        } else {
            $('tr.rename_email_send_to').hide();
        }
    });

    $(window).on('load', function() {
        if ($('#wpb-loginpress_hidelogin\\[is_rename_send_email\\]').is(":checked")) {
            $('tr.rename_email_send_to').show();
        }
    });

});