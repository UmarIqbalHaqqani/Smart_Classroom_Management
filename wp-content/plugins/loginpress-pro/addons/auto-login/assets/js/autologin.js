'use strict';

(function ($) {

	/**
	 * [loginpress_create_new_link]
	 * @return {[string]}
	 * @since 1.0.0
	 */
	function loginpress_create_new_link() {
		var autoLoginString = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

		var result = "";
		while (result.length < 30) {
			result += autoLoginString.charAt(Math.floor(Math.random() * autoLoginString.length));
		}

		return result;
	}



	$(function () {
		autologin_table = $('#loginpress_autologin_users').DataTable({
			"dom": 'fl<"loginpress_table_wrapper"t>ip',
			"fnDrawCallback": function (oSettings) {
				if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
					$(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
				} else {
					$(oSettings.nTableWrapper).find('.dataTables_paginate').show();
				}
			},
			"oLanguage": {
				"sSearch": loginpress_autologin.translate[8],
				"searchPlaceholder": "Search records"
			},
			"initComplete": function (settings, json) {
				$('.dataTables_filter input').attr('placeholder', loginpress_autologin.translate[9]);
			}
		});
		setTimeout(function () {
			autologin_table.draw();
		}, 400);

		$('[href="#loginpress_autologin"]').on('click', function () {
			setTimeout(function () {
				autologin_table.draw();
			}, 400);
		});

		// Globals for this scope.
		const loginPressPopUpContainer = $('.loginpress-edit-popup-container');
		const hidePopUpWindow = function () {
			loginPressPopUpContainer.css({ display: 'none' });
			loginPressPopUpContainer.html('');
			loginPressPopUpContainer.attr('data-for', 'NULL');
		}

		// Apply ajax on click new button.
		$(document).on("click", ".loginpress-new-link", function (event) {
			event.preventDefault();

			var code = loginpress_create_new_link();
			var el   = $(this);
			var tr   = el.closest('tr');
			var id   = tr.attr("data-autologin");
			var _nonce = loginpress_autologin.loginpress_autologin_nonce;

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: 'code=' + code + '&id=' + id + '&action=loginpress_autologin' + '&security=' + _nonce,
				beforeSend: function () {
					el.closest('tr').removeClass("menu-active");
					el.closest('tr').find('.loginpress-autologin-code').val('');
					el.closest('tr').find('.autologin-sniper').show();
					el.closest('tr').find('.loginpress-new-link').attr("disabled", "disabled");
					el.closest('tr').find('svg.autologin-copy-svg').hide();
				},
				success: function (response) {
					el.closest('tr').find('.autologin-sniper').hide();
					el.closest('tr').find('.loginpress-new-link').removeAttr("disabled");
					el.closest('tr').find('.loginpress-autologin-duration').removeAttr("disabled");
					el.closest('tr').find('.loginpress-autologin-state').removeAttr("disabled");
					el.closest('tr').find('.loginpress-autologin-email-settings').removeAttr("disabled");
					el.closest('tr').find('.loginpress-autologin-email').removeAttr("disabled");
					el.closest('tr').find('td.loginpress_user_status span').remove();
					el.closest('tr').find('loginpress_autologin_code .loginpress-autologin-remain-notice span').remove();
					el.closest('tr').find('td.loginpress_user_status span').remove();
					el.closest('tr').find('.loginpress-action-list-menu-wrapper').removeClass('menu-active');
					el.closest('tr').find('.loginpress_autologin_actions').removeClass('sticky-active');
					el.closest('tr').removeClass('list-active');
					el.closest('tr').find('td.loginpress_user_status').html('<span class="loginpress-autologin-remain-notice">' + loginpress_autologin.translate[3] + '</span>');
					el.closest('tr').find('.loginpress-autologin-code').val(response);
					el.closest('tr').find('svg.autologin-copy-svg').show();

				}
			}); // !Ajax.
		}); // !click .loginpress-new-link.

		// Apply ajax on click delete button.
		$(document).on("click", ".loginpress-del-link", function (event) {

			event.preventDefault();

			var el = $(this);
			var tr = el.closest('tr');
			var id = tr.attr("data-autologin");
			var _nonce = loginpress_autologin.loginpress_autologin_nonce;

			$.ajax({

				url: ajaxurl,
				type: 'POST',
				data: 'id=' + id + '&action=loginpress_autologin_delete' + '&security=' + _nonce,
				beforeSend: function () {
					tr.find('.loginpress-autologin-code').val('');
					tr.find('.autologin-copy-svg').hide();
					tr.find('.autologin-sniper').show();
					tr.find('.loginpress-new-link').attr("disabled", "disabled");
					tr.find('.loginpress-del-link').attr("disabled", "disabled");
				},
				success: function (response) {
					// $( '#loginpress_user_id_' + id ).remove();
					autologin_table
						.row(el.closest('tr'))
						.remove()
						.draw();
				}
			}); // !Ajax.
		}); // !click .loginpress-del-link.

		// Apply ajax on click state button.
		$(document).on("click", ".loginpress-autologin-state", function (event) {

			event.preventDefault();

			var el = $(this);
			var tr = el.closest('tr');
			var id = tr.attr("data-autologin");
			var _nonce = loginpress_autologin.loginpress_autologin_nonce;
			let state = el.attr('data-state');

			tr.addClass('this-selected');

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'loginpress_change_autologin_state',
					id: id,
					security: _nonce,
					state: state
				},
				beforeSend: function () {
					tr.find('.autologin-sniper').show();
					tr.find('.loginpress-new-link').attr("disabled", "disabled");
					tr.find('.loginpress-del-link').attr("disabled", "disabled");
					tr.find('.loginpress-autologin-state').attr("disabled", "disabled");
					//   tr.find( '.action-menu-list' ).attr( "disabled", false );
					tr.find('.loginpress-autologin-code').hide();
					tr.find('svg.autologin-copy-svg').hide();
					tr.find(".loginpress-action-list-menu-wrapper").removeClass("menu-active");
				},
				success: function (response) {
					tr.find('.autologin-sniper').hide();
					tr.find('.loginpress-new-link').removeAttr("disabled");
					tr.find('.loginpress-del-link').removeAttr("disabled");
					tr.find('.loginpress-autologin-state').removeAttr("disabled");
					tr.find('.loginpress-autologin-code').show();
					tr.find('svg.autologin-copy-svg').show();

					if (state == 'enable') {
						tr.find('.loginpress-autologin-state').removeClass('enable').addClass('disable');
						tr.find('.loginpress-autologin-state').attr('data-state', 'disable');
						tr.find('.loginpress-autologin-state').val('disable');

						tr.removeClass('autologin-disabled');
						tr.find('.loginpress-autologin-duration').prop('disabled', false);
						tr.find('.loginpress-new-link').prop('disabled', false);
						tr.find('.loginpress-autologin-email').prop('disabled', false);
						tr.find('.loginpress-autologin-email-settings').prop('disabled', false);
						tr.find('.loginpress-autologin-remain-notice').removeClass('disable');
						tr.find('.loginpress-autologin-remain-notice').removeClass('loginpress-autologin-expired-notice').html(loginpress_autologin.translate[0]);
						tr.find('.loginpress-autologin-remain-notice').removeClass('loginpress-autologin-remain-notice-last-day').html(loginpress_autologin.translate[0]);
						tr.find('.loginpress-autologin-remain-notice').css({ "color": "green" });
						tr.find('.loginpress-autologin-remain-notice-red').removeClass().addClass('loginpress-autologin-remain-notice');
						$('.loginpress_autologin_users tr').removeClass('this-selected');

					} else {
						tr.find('.loginpress-autologin-state').removeClass('disable').addClass('enable');
						tr.find('.loginpress-autologin-state').attr('data-state', 'enable');
						tr.find('.loginpress-autologin-state').val('enable');

						tr.addClass('autologin-disabled');
						tr.find('.loginpress-autologin-duration').prop('disabled', true);
						tr.find('.loginpress-new-link').prop('disabled', true);
						tr.find('.loginpress-autologin-email').prop('disabled', true);
						tr.find('.loginpress-autologin-email-settings').prop('disabled', true);
						tr.find('.loginpress-autologin-remain-notice').css({ "color": "red" });
						tr.find('.loginpress-autologin-remain-notice').addClass('disable').addClass('loginpress-autologin-expired-notice').html(loginpress_autologin.translate[1]);
						tr.find('.loginpress-autologin-remain-notice').removeClass('loginpress-autologin-remain-notice-last-day').html(loginpress_autologin.translate[1]);
					}
					tr.removeClass('this-selected');

				}
			}); // !Ajax.
		}); // !click .loginpress-del-link.

		// Link duration
		$(document).on("click", ".loginpress-autologin-duration", function (event) {
			event.stopPropagation();
			event.preventDefault();

			var el = $(this);
			var tr = el.closest('tr');
			var _nonce = loginpress_autologin.loginpress_autologin_nonce;
			var id = tr.attr("data-autologin");
			tr.addClass('this-selected');
			loginPressPopUpContainer.css({ display: 'flex' });
			loginPressPopUpContainer.attr('data-for', id);

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'loginpress_populate_popup_duration',
					id: id,
					security: _nonce,
				},
				beforeSend: function () {
					tr.find(".loginpress-action-list-menu-wrapper").removeClass("menu-active");
				},
				success: function (res) {
					$('.loginpress-edit-popup-container').html(res);
				}
			}); // !Ajax.
		});

		// Link duration
		$(document).on("click", ".loginpress-autologin-email-settings", function (event) {
			event.stopPropagation();
			event.preventDefault();

			var el = $(this);
			var tr = el.closest('tr');
			var _nonce = loginpress_autologin.loginpress_autologin_nonce;
			var id = tr.attr("data-autologin");

			if (tr.find('.loginpress-autologin-email').prop('disabled') == true) {
				return;
			}

			loginPressPopUpContainer.css({ display: 'flex' });
			loginPressPopUpContainer.attr('data-for', id);

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'loginpress_populate_popup_email',
					id: id,
					security: _nonce,
				},
				beforeSend: function () {
					tr.find(".loginpress-action-list-menu-wrapper").removeClass("menu-active");
				},
				success: function (res) {
					$('.loginpress-edit-popup-container').html(res);
				}
			}); // !Ajax.
		});

		// Apply ajax on click new button.
		$(document).on("click", ".loginpress-autologin-email", function (event) {
			event.preventDefault();

			var el = $(this);
			var tr = el.closest('tr');
			var id = tr.attr("data-autologin");
			var code = tr.find('.loginpress-autologin-code').val();
			var _nonce = loginpress_autologin.loginpress_autologin_nonce;

			$.ajax({

				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'loginpress_autologin_emailuser',
					id: id,
					code: code,
					security: _nonce,
				},
				beforeSend: function () {
					el.closest('tr').find('.autologin-sniper').show();
					tr.find(".loginpress-action-list-menu-wrapper").removeClass("menu-active");
					el.closest('tr').find('.loginpress-new-link').attr("disabled", "disabled");
					el.closest('tr').find('.loginpress-autologin-code').hide();
					el.closest('tr').find('.autologin-copy-code').hide();
				},
				success: function (response) {
					el.closest('tr').find('.autologin-sniper').hide();
					el.closest('tr').find('.loginpress-autologin-code').show();
					el.closest('tr').find('.autologin-copy-code').show();
					el.closest('tr').find('.loginpress_autologin_code .loginpress-autologin-email-sent').fadeIn();
					el.closest('tr').find('.loginpress-new-link').removeAttr("disabled");
					setInterval(function () { el.closest('tr').find('.loginpress_autologin_code .loginpress-autologin-email-sent').fadeOut() }, 5000);
				}
			}); // !Ajax.
		});

		if ($('.loginpress-autologin-state').hasClass('enable')) {
			let disableRow = $('.loginpress-autologin-state.enable').parent().parent();
			disableRow.addClass('autologin-disabled');
			disableRow.find('.loginpress_autologin_actions .loginpress-autologin-duration').prop('disabled', true);
			disableRow.find('.loginpress_autologin_actions .loginpress-new-link').prop('disabled', true);
			disableRow.find('.loginpress_autologin_actions .loginpress-autologin-email').prop('disabled', true);
			disableRow.find('.loginpress_autologin_actions .loginpress-autologin-email-settings').prop('disabled', true);
		}

		$(document).on("click", ".autologin-save-duration", function (event) {
			event.preventDefault();

			let id = $('.loginpress-edit-popup-container').attr('data-for');
			let _nonce = loginpress_autologin.loginpress_autologin_popup;
			let neverExpire = 'unchecked';
			let expireDuration = $('#autologin-expire-date').val();
			const date = new Date();
			let today = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0') + '-' + String(date.getDate()).padStart(2, '0');
			const date2 = new Date(expireDuration);
			const diffTime = Math.abs(date2 - date);
			var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
			var dayString = loginpress_autologin.translate[5];
			var left = loginpress_autologin.translate[6];
			;
			if (diffDays == 1) {
				dayString = loginpress_autologin.translate[4];
				if (date.getDate() == date2.getDate()) {
					diffDays = loginpress_autologin.translate[7];
					$('.loginpress_autologin_users tr').removeClass('autologin-expired');
					$('.loginpress_autologin_users tr.this-selected').find('.loginpress-autologin-remain-notice').addClass('loginpress-autologin-remain-notice-last-day');
					$('.loginpress_autologin_users tr.this-selected').find('.loginpress-autologin-remain-notice-red').removeClass().addClass('loginpress-autologin-remain-notice loginpress-autologin-remain-notice-last-day').html(diffDays + ' ' + dayString + ' ');
				} else {
					$('.loginpress_autologin_users tr.this-selected').find('.loginpress-autologin-remain-notice').removeClass('loginpress-autologin-remain-notice-last-day');
					$('.loginpress_autologin_users tr.this-selected').find('.loginpress-autologin-remain-notice-red').removeClass().addClass('loginpress-autologin-remain-notice').html(diffDays + ' ' + dayString + ' ' + left);
				}
			} else {
				left = loginpress_autologin.translate[6];
				$('.loginpress_autologin_users tr.this-selected').find('.loginpress-autologin-remain-notice-red').removeClass().addClass('loginpress-autologin-remain-notice').html(diffDays + ' ' + dayString + ' ' + left);
				$('.loginpress_autologin_users tr.this-selected').find('.loginpress-autologin-remain-notice-last-day').removeClass().addClass('loginpress-autologin-remain-notice');

			}
			if ($('#autologin-never-expire').prop('checked')) {
				neverExpire = 'checked';
				$('.loginpress_autologin_users tr.this-selected').find('.loginpress-autologin-remain-notice-last-day').removeClass().addClass('loginpress-autologin-remain-notice');
				$('.loginpress_autologin_users tr.this-selected').find('.loginpress-autologin-remain-notice').html(loginpress_autologin.translate[2]);
				$('.loginpress_autologin_users tr').attr('data-dayleft', 'Lifetime');
				$('.loginpress_autologin_users tr').removeClass('this-selected');
			} else {
				$('.loginpress_autologin_users tr').removeClass('autologin-expired');
				$('.loginpress_autologin_users tr.this-selected').find('.loginpress-autologin-remain-notice').html(diffDays + ' ' + dayString + ' ' + left);
				$('.loginpress_autologin_users tr').attr('data-dayleft', diffDays + ' ' + dayString + ' ' + left);
				$('.loginpress_autologin_users tr').removeClass('this-selected');
			}

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'loginpress_update_duration',
					security: _nonce,
					id: id,
					never_expire: neverExpire,
					expire_duration: expireDuration
				},
				beforeSend: function () {
				},
				success: function (res) {
					hidePopUpWindow();

					// if (today <= expireDuration) {
					let row = $('#loginpress_user_id_' + id + ' .loginpress_autologin_actions');
					row.find('.loginpress-autologin-expired-notice').remove();
					row.find('.loginpress-autologin-duration').prop('disabled', false);
					row.find('.loginpress-new-link').prop('disabled', false);
					row.find('.loginpress-autologin-email').prop('disabled', false);
					row.find('.loginpress-autologin-email-settings').prop('disabled', false);
					row.find('.loginpress-autologin-state').prop('disabled', false);
					row.find('.loginpress-autologin-email-settings').removeClass('disabled');
					// }
				}
			});
		});

		$(document).on("click", ".autologin-save-emails", function (event) {
			event.preventDefault();

			let id = $('.loginpress-edit-popup-container').attr('data-for');
			let _nonce = loginpress_autologin.loginpress_autologin_popup;
			let emails = $('#autologin-emails').val();
			let valid_email = is_valid_emails(emails);
			let start_slice = emails.slice(-1);
			let end_slice = emails.slice(0);
			//If All emails are valid then run the Ajax else show a message for 4 seconds
			if (valid_email && ',' !== start_slice && ',' !== end_slice && emails !== '') {
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'loginpress_update_email',
						security: _nonce,
						id: id,
						emails: emails
					},
					beforeSend: function () {
						$('.loginpress_empty_email').fadeOut();
						$('.loginpress_valid_email').fadeOut();
						$('.autologin_emails_sent').fadeIn();
					},
					success: function (res) {
						setTimeout(function () { hidePopUpWindow(); }, 2000);
					}
				});
			} else if (emails === '') {
				$('.loginpress_empty_email').fadeIn();
				setTimeout(function () { $('.loginpress_empty_email').fadeOut() }, 6000);

			} else {
				$('.loginpress_valid_email').fadeIn();
				setTimeout(function () { $('.loginpress_valid_email').fadeOut() }, 6000);

			}


			function is_valid_emails(emails) {

				var email_array = emails.split(',');
				let valid = '';
				email_array.forEach(function (email, index) {
					if (!validateEmail(email)) {
						valid = false;
					} else {
						valid = true;
					}
				});

				return valid;

			}
			function validateEmail(email) {
				const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				return re.test(email.trim());
			}
		});

		$(document).on('click', '.autologin-close-popup', function () {
			hidePopUpWindow();
		})

		$(document).on('change', '.autologin-never-expire', function () {
			let expireDateContainer = $('.autologin-expire-date-container');

			if ($(this).prop('checked')) {
				expireDateContainer.fadeOut();
			} else {
				expireDateContainer.fadeIn();
			}
		});
		$(document).on('click', '.autologin-copy-code', function (e) {
			// $(this).tooltip( "option", "content", "Copied" );
			let copyText = $(this).prev()[0];
			copyText.select();
			copyText.setSelectionRange(0, 99999);
			document.execCommand("copy");


			let $this = $(this);

			$this.parent().removeClass('copy');
			$this.parent().addClass('copied');
			$this.parent().addClass('autologin-copied');

			setTimeout(function () {
				$this.parent().removeClass('copied');
				$this.parent().removeClass('autologin-copied');
				// $this.tooltip( "option", "content", "Copy" );
			}, 2000);
			document.getSelection().removeAllRanges();
		});

		$(document).on('keydown', '.loginpress-autologin-code', function (e) {
			e.preventDefault();
		});

		$(document).on('mouseenter', '.autologin-copy-svg', function (e) {
			if ($(this).parent().hasClass('copied')) {
				return;
			}
			$(this).parent().addClass('copy');
		});

		$(document).on('mouseleave', '.autologin-copy-svg', function (e) {
			let el = $(this).parent();
			el.removeClass('copy');

			setTimeout(function () {
				el.removeClass('copied');
			}, 2000);
		});

		// Close exipre duration popup on click outside.
		$(document).on('click', '.loginpress-edit-popup-container', function (e) {
			$(this).hide();
			$('.this-selected').removeClass('this-selected');
		});
		$(document).on('click', '.loginpress-edit-popup', function (e) {
			e.stopPropagation();
		});
	});

	$(document).on('click', function (e) {
		if (!$(e.target).parents().andSelf().is('.loginpress-action-list-menu-wrapper')) {
			$('.loginpress-action-list-menu-wrapper').removeClass('menu-active');
			$('.loginpress_autologin_actions').removeClass('sticky-active');
			$('tr').removeClass('list-active');
			$('#loginpress_autologin').removeAttr('data-open');
		}
	});

	$(document).on('click', '.loginpress-action-menu-burger-wrapper', function (e) {
		e.stopPropagation();
		$(this).parent().toggleClass("menu-active").closest('tr').siblings().find('.loginpress-action-list-menu-wrapper').removeClass('menu-active');
		$(this).closest('.loginpress_autologin_actions').toggleClass("sticky-active").closest('tr').siblings().find('.loginpress_autologin_actions').removeClass('sticky-active');
		$(this).closest('tr').addClass("list-active").siblings('tr').removeClass('list-active');
		if ($(this).parent().hasClass("menu-active")) {
			$('#loginpress_autologin').attr('data-open', 'parent-' + $(this).closest('tr').nextAll().length);
		} else {
			$('#loginpress_autologin').removeAttr('data-open');

		}
	});


})(jQuery); // This invokes the function above and allows us to use '$' in place of 'jQuery' in our code.
