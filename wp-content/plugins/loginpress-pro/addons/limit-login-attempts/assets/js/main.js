(function ($) {

	'use strict';

	$(function () {

		const loginPressPopUpContainer = $('.llla_remove_all_popup');
		const hidePopUpWindow = function () {
			loginPressPopUpContainer.fadeOut();
		}
		$(document).on('click','.loginpress-edit-overlay', function(){
			hidePopUpWindow();
		});
		var bulkIps = [];
		var limitTable;
		limitTable = $('#loginpress_limit_login_log').DataTable({
			"dom": 'fl<"loginpress_table_wrapper"t>ip',
			"lengthMenu": [10, 25, 50, 75, 100],
			"fnDrawCallback": function(oSettings) {
				if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
					$(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
				} else {
					 $(oSettings.nTableWrapper).find('.dataTables_paginate').show();
				}
			},
			'columnDefs': [
				{
				'targets': 0,
				'searchable': false,
				'orderable': false,
				'className': 'dt-body-center',
				'render': function (data, type, full, meta) {

					return '<div class="lp-tbody-cell"><input type="checkbox" name="id[]" class="llla_inside_check" value="' + $('<div/>').text(data).html() + '"></div>';
					
				}
			}],
			'order': [
				[1, 'asc']
			],
		});
		$('[href="#loginpress_limit_logs"]').on('click', function () {
			setTimeout(function(){
				limitTable.draw();
			}, 400);
		});

		  $(window).on('resize', function() {
			// limitTable.columns.adjust().responsive.recalc();
			// limitTable.column(0).visible( screen.width > 767);
			// limitTable.column(2).visible( screen.width > 766);
			// limitTable.column(3).visible( screen.width > 766);
			// limitTable.column(4).visible( screen.width > 766);

		  });


		var blackTable = $('#loginpress_limit_login_blacklist').DataTable({
			"dom": 'fl<"loginpress_table_wrapper"t>ip',
			"fnDrawCallback": function(oSettings) {
				if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
					$(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
				} else {
					 $(oSettings.nTableWrapper).find('.dataTables_paginate').show();
				}
			}
		});
		var whiteTable = $('#loginpress_limit_login_whitelist').DataTable({
			"dom": 'fl<"loginpress_table_wrapper"t>ip',
			"fnDrawCallback": function(oSettings) {
				if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
					$(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
				} else {
					 $(oSettings.nTableWrapper).find('.dataTables_paginate').show();
				}
			}
		});

		// Handle click on "Select all" control
		$('.lla-select-all').on('click', function () {
			// Get all rows with search applied
			var rows = limitTable.rows({ 'search': 'applied' }).nodes();
			// Check/uncheck checkboxes for all rows in the table
			$('input[type="checkbox"]', rows).prop('checked', this.checked);
			$('.lla-select-all').prop('checked', this.checked);
		});

		// Handle click on checkbox to set state of "Select all" control
		$('#loginpress_limit_login_log tbody').on('change', 'input[type="checkbox"]', function () {
			// If checkbox is not checked
			if (!this.checked) {
				var el = $('.lla-select-all').get(0);
				// If "Select all" control is checked and has 'indeterminate' property
				if (el && el.checked && ('indeterminate' in el)) {
					// Set visual state of "Select all" control
					// as 'indeterminate'
					el.indeterminate = true;
				}
			}
		});

		/**
		 * Add `llla__bulk-action` class in the rows that has same IP addesses data.
		 * @return string
		 * @since 2.1.0
		 */
		limitTable.$('input[type="checkbox"]').on('change', function () {
			$(this).closest('tr').removeClass('llla__bulk-action');
			if (this.checked) {
				bulkIps.push($(this).parent().parent().parent().data('ip'));
				limitTable.$('tr.llla__bulk-action');
				var keyip = $(this).closest('tr').attr('data-ip');
				$('[data-ip="' + keyip + '"]').addClass('llla__bulk-action');
			}
		});

		/**
		 * Handle Bulk Action form submission event
		 * @return void
		 * @since 2.1.0
		 */
		$('#loginpress_limit_bulk_blacklist_submit').on('click', function (e) {

			const bulkAction = $('#loginpress_limit_bulk_blacklist').val();
			const _nonce = loginpress_llla.bulk_nonce;
			let $this = $(this);
			
			// Iterate over all checkboxes in the table
			limitTable.$('input[type="checkbox"]').each(function () {
				if (this.checked) {
					bulkIps.push($(this).parent().parent().parent().data('ip'));
				}
			});

			// Error Handling Check
			if ('' == bulkAction || '' == bulkIps) {
				if ($('.llla-bulk-attempts').length < 1) {
					$("#loginpress_limit_bulk_blacklist_submit").after('<div id="no-items-selected" class="notice notice-error loginpress-llla-bulk-no-item llla-bulk-attempts"><p>' + loginpress_llla.translate[0] + '</p></div>');
				}
				setTimeout(function () {
					$('div#no-items-selected').fadeOut();
					$('div#no-items-selected').remove();

				}, 3000);
				return;
			} else {
				$('.loginpress-llla-bulk-no-item').hide();
			}
			// Send Ajax request
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'loginpress_attempts_bulk',
					bulk_action: bulkAction,
					bulk_ips: bulkIps,
					security: _nonce,
				},
				beforeSend: function () {

					$('<div class="loginpress_limit_login_log_message"> Updating .... </div>').appendTo($('#loginpress_limit_login_log_wrapper'));
					$('#loginpress_limit_login_log_wrapper .loginpress_limit_login_log_message').fadeIn();
				},
				success: function (response) {
					if ('white_list' == bulkAction || 'black_list' == bulkAction) {
						$(response.data.updated_ips).each(function (index, ip) {
							let action = '';
							var tr = $('[data-ip="' + ip + '"]');
							var id = tr.attr("data-login-attempt-user");
							var ip = $('[data-ip="' + ip + '"]').attr("data-ip");
							var _nonce = loginpress_llla.user_nonce;
							var list_ip = $('[data-ip="' + ip + '"]').attr('data-ip');
							var listView = bulkAction.replace('_list', '');
							
							if ('white_list' == bulkAction) {
								action = 'whitelist';
							} else if ('black_list' == bulkAction) {
								action = 'blacklist';
							}

							var list_tr = '<tr id="loginpress_' + listView + 'list_id_' + id + '" data-login-' + listView + 'list-user="' + id + '" role="row" class="even"><td class="loginpress_limit_login_'+action+'_ips" data-' + action + '-ip="' + ip + '"><div class="lp-tbody-cell">' + list_ip + '</div></td><td class="loginpress_limit_login_'+action+'_actions"><div class="lp-tbody-cell"><input class="loginpress-' + listView + 'list-clear button button-primary" type="button" value="' + loginpress_llla.translate[1] + '"></div></td></tr>';

							var getNode = $.parseHTML(list_tr);

							if ('white_list' == bulkAction) {
								whiteTable.row.add(getNode[0]).draw();
							} else if ('black_list' == bulkAction) {
								blackTable.row.add(getNode[0]).draw();
							}
						});
					}

					limitTable.rows('.llla__bulk-action').remove().draw();
					$('.loginpress_limit_login_log_message').remove();
					setTimeout(function () {
						$('#loginpress_limit_login_log_wrapper .loginpress_limit_login_log_message').fadeOut();
					}, 5000);

				}
			}); // !Ajax.

			bulkIps = [];
		});

		
		/**
		 * Handle Clear All submission event.
		 * @return void
		 * @since 3.0.0
		 */
		 $('#loginpress_limit_bulk_attempts_submit').on('click', function (e) {
			bulkIps = [];

			$('#loginpress_limit_login_log tbody').find('tr').each(function () {
				bulkIps.push($(this).data('ip'));
			});
			
			if ( $('#loginpress_limit_login_log tbody').find('tr .dataTables_empty').length ) {
				e.stopPropagation();
				e.preventDefault();
				if ($('.llla-bulk-attempts').length < 1 ) {

					$("#loginpress_limit_bulk_attempts_submit").after('<div id="no-ip-found" class="notice notice-error loginpress-llla-bulk-no-item llla-attempts-log"><p>' + loginpress_llla.translate[10] + '</p></div>');
				}
				setTimeout(function () {
					$('.loginpress-llla-bulk-no-item').fadeOut();
					$('div.loginpress-llla-bulk-no-item').remove();

				}, 3000);
			} else {
				$('.loginpress-edit-attempts-popup-containers').show();
			}
			bulkIps = [];

		});

		$(document).on( "click", ".loginpress_confirm_remove_all_attempts", function(event) {
			
			const _nonce = loginpress_llla.bulk_nonce;
			
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'loginpress_clear_all_attempts',
						security: _nonce,
					},
					beforeSend: function () {

						$('<div class="loginpress_limit_login_log_message"> Updating .... </div>').appendTo($('#loginpress_limit_login_log'));
						$('#loginpress_limit_login_log_message').fadeIn();

					},
					success: function (response) {
						limitTable.clear().draw();
						$('.loginpress-edit-attempts-popup-containers').hide();

					}
				});
				setTimeout(function () {
					$('.loginpress_limit_login_log_message').fadeOut();
				}, 700);
			hidePopUpWindow();
			bulkIps = [];
		});

		/**
		 * Handle Bulk Action form submission event
		 * @return void
		 * @since 2.1.0
		 */
		$('#loginpress_limit_bulk_blacklists_submit').on('click', function (e) {
			bulkIps = [];

			$('#loginpress_limit_login_blacklist').find('tr td.loginpress_limit_login_blacklist_ips').each(function () {
				bulkIps.push($(this).data('blacklist-ip'));
			});

			if ( bulkIps.length === 0 ) {
				e.stopPropagation();
				e.preventDefault();
				if ($('.llla-bulk-bl').length < 1) {

					$("#loginpress_limit_bulk_blacklists_submit").after('<div id="no-ip-found" class="notice notice-error loginpress-llla-bulk-no-item llla-bulk-bl"><p>' + loginpress_llla.translate[9] + '</p></div>');
				}
				setTimeout(function () {
					$('.loginpress-llla-bulk-no-item').fadeOut();
					$('div.loginpress-llla-bulk-no-item').remove();

				}, 3000);
			} else {

				$('.loginpress-edit-black-popup-containers').show();
			}
		});

		$(document).on( "click", ".loginpress_confirm_remove_all_blacklist", function(event) {
			const _nonce = loginpress_llla.bulk_nonce;
			bulkIps = [];
			$('#loginpress_limit_login_blacklist').find('tr td.loginpress_limit_login_blacklist_ips').each(function () {
				bulkIps.push($(this).data('blacklist-ip'));
			});
			if ( bulkIps.length === 0 ) {
				if ($('.llla-bulk-bl').length < 1) {
					$("#loginpress_limit_bulk_blacklists_submit").after('<div id="no-ip-found" class="notice notice-error loginpress-llla-bulk-no-item llla-bulk-bl"><p>' + loginpress_llla.translate[9] + '</p></div>');
					setTimeout(function () {
						$('.loginpress-llla-bulk-no-item').fadeOut();
						$('div.loginpress-llla-bulk-no-item').remove();
					}, 3000);
				}
			} else {
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'loginpress_clear_all_blacklist',
						bulk_ips: bulkIps,
						security: _nonce,
					},
					beforeSend: function () {

						$('<div class="loginpress_limit_login_log_message"> Updating .... </div>').appendTo($('#loginpress_limit_login_blacklist'));
						$('#loginpress_limit_login_log_message').fadeIn();

					},
					success: function (response) {
						$(response.data.updated_ips).each(function (index, ip) {
							blackTable.clear().draw();
						});
						$('.loginpress-edit-black-popup-containers').show();

					}
				});
				setTimeout(function () {
					$('.loginpress_limit_login_log_message').fadeOut();
				}, 700);
			}
			hidePopUpWindow();
			bulkIps = [];
		});
	
		/**
		 * Handle Bulk Action form submission event
		 * @return void
		 * @since 2.1.0
		 */
		$('#loginpress_limit_bulk_whitelists_submit').on('click', function (e) {
			bulkIps = [];
			$('#loginpress_limit_login_whitelist').find('tr td.loginpress_limit_login_whitelist_ips').each(function () {
				bulkIps.push($(this).data('whitelist-ip'));
			});

			if ( bulkIps.length === 0 ) {
				$('.loginpress-llla-bulk-no-item').remove();
				$("#loginpress_limit_bulk_whitelists_submit").after('<div id="no-ip-found" class="notice notice-error loginpress-llla-bulk-no-item"><p>' + loginpress_llla.translate[8] + '</p></div>');
				
				setTimeout(function () {
					$('.loginpress-llla-bulk-no-item').fadeOut();
					$('div.loginpress-llla-bulk-no-item').remove();

				}, 3000);
			} else {
				$('.loginpress-edit-white-popup-containers').show();
			}

		});


		$(document).on( "click", ".loginpress_confirm_remove_all_whitelist", function(event) {
			bulkIps = [];

			const _nonce = loginpress_llla.bulk_nonce;
			$('#loginpress_limit_login_whitelist').find('tr td.loginpress_limit_login_whitelist_ips').each(function () {
				bulkIps.push($(this).data('whitelist-ip'));
			});

			if ( bulkIps.length === 0 ) {

				$("#loginpress_limit_bulk_whitelists_submit").after('<div id="no-ip-found" class="notice notice-error loginpress-llla-bulk-no-item"><p>' + loginpress_llla.translate[8] + '</p></div>');
				
				setTimeout(function () {
					$('.loginpress-llla-bulk-no-item').fadeOut();
					$('div.loginpress-llla-bulk-no-item').remove();

				}, 3000);

			} else {
				// Send Ajax request
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'loginpress_clear_all_whitelist',
						bulk_ips: bulkIps,
						security: _nonce,
					},
					beforeSend: function () {
						$('<div class="loginpress_limit_login_log_message"> Updating .... </div>').appendTo($('#loginpress_limit_login_whitelist'));
						$('#loginpress_limit_login_log_message').fadeIn();
					},
					success: function (response) {
						$(response.data.updated_ips).each(function (index, ip) {
							whiteTable.clear().draw();
						});
						$('.loginpress-edit-whitelist-popup-containers').show();

					}
				});
				setTimeout(function () {
					$('.loginpress_limit_login_log_message').fadeOut();
				}, 700);
			}
			hidePopUpWindow();
			bulkIps = [];
		});
		$(document).on( "click", ".limit-login-attempts-close-popup", function(event) {
			hidePopUpWindow();
		});

		// Handle LoginPress - Limit Login Attemps tabs.
		$('.loginpress-limit-login-tab').on('click', function (event) {

			event.preventDefault();

			var target = $(this).attr('href');
			$(target).show().siblings('table').hide();
			$(this).addClass('loginpress-limit-login-active').siblings().removeClass('loginpress-limit-login-active');

			if ('#loginpress_limit_login_settings' == target) { // Settings Tab.
				$('#loginpress_limit_logs').hide();
				$('#loginpress_limit_login_whitelist_wrapper2').hide();
				$('#loginpress_limit_login_blacklist_wrapper2').hide();
				$('#loginpress_limit_login_attempts .form-table').show();
				$('#loginpress_limit_login_attempts .submit').show();
			}

			if ('#loginpress_limit_logs' == target) { // Attempts Log Tab.
				$('#loginpress_limit_logs').show();
				$('#loginpress_limit_login_whitelist_wrapper2').hide();
				$('#loginpress_limit_login_blacklist_wrapper2').hide();
				$('#loginpress_limit_login_attempts .form-table').hide();
				$('#loginpress_limit_login_attempts .submit').hide();
			}

			if ('#loginpress_limit_login_whitelist' == target) { // Whitelist Tab.
				$('#loginpress_limit_logs').hide();
				$('#loginpress_limit_login_whitelist_wrapper2').show();
				$('#loginpress_limit_login_whitelist_wrapper2').css("position", "relative");
				$('#loginpress_limit_login_whitelist_wrapper2').show();
				$('#loginpress_limit_login_blacklist_wrapper2').hide();
				$('#loginpress_limit_login_attempts .form-table').hide();
				$('#loginpress_limit_login_attempts .submit').hide();
			}

			if ('#loginpress_limit_login_blacklist' == target) { // Blacklist Tab.
				$('#loginpress_limit_logs').hide();
				$('#loginpress_limit_login_whitelist_wrapper2').hide();
				$('#loginpress_limit_login_blacklist_wrapper2').show();
				$('#loginpress_limit_login_blacklist_wrapper2').css("position", "relative");
				$('#loginpress_limit_login_attempts .form-table').hide();
				$('#loginpress_limit_login_attempts .submit').hide();
			}
		});

		// Apply ajax on click attempts tab whitelist button.
		$(document).on("click", "input.loginpress-attempts-whitelist", function (event) {
			$('.loginpress_llla_loader_inner').show();
			event.preventDefault();

			var el = $(this);
			var tr = el.closest('tr');
			var id = tr.attr("data-login-attempt-user");
			var ip = el.closest('tr').attr("data-ip");
			var _nonce = loginpress_llla.user_nonce;

			$.ajax({

				url: ajaxurl,
				type: 'POST',
				data: 'id=' + id + '&ip=' + ip + '&action=loginpress_attempts_whitelist' + '&security=' + _nonce,
				beforeSend: function () {

					// tr.find( '.loginpress_autologin_code p' ).html('');
					tr.find('.autologin-sniper').show();
					tr.find('.loginpress-attempts-unlock').attr("disabled", "disabled");
					tr.find('.loginpress-attempts-whitelist').attr("disabled", "disabled");
					tr.find('.loginpress-attempts-blacklist').attr("disabled", "disabled");
				},
				success: function (response) {
					$('#loginpress_limit_login_whitelist .dataTables_empty').remove();
					var white_list_ip = $('#loginpress_attempts_id_' + id).find('.lg_attempts_ip').html();
					$('#loginpress_attempts_id_' + id).find('td').eq(2).find('.attempts-sniper').remove();
					var white_list_user = $('#loginpress_attempts_id_' + id).find('td').eq(2).html();

					var whitelist_tr = '<tr id="loginpress_whitelist_id_' + id + '" data-login-whitelist-user="' + id + '" role="row" class="even"><td class="loginpress_limit_login_whitelist_ips">' + white_list_ip + '</td><td class="loginpress_limit_login_whitelist_actions"><div class="lp-tbody-cell"><button class="loginpress-whitelist-clear button button-primary" type="button" value="' + loginpress_llla.translate[1] + '"></button></div></td></tr>';
					// Remove data from limit attempts table.
					var row = limitTable.row(el.parents('tr'));
					row.remove();
					// Add data to white_table.
					var getNode = $.parseHTML(whitelist_tr);
					whiteTable.row.add(getNode[0]).draw();
					var ip = el.closest('tr').attr("data-ip");
					limitTable.rows('[data-ip="' + ip + '"]').remove().draw(false);
					$('.loginpress_llla_loader_inner').hide();
					if ($('#loginpress_limit_login_log_wrapper .loginpress_limit_login_log_message').length == 0) {

						$('<div class="loginpress_limit_login_log_message"><span>' + loginpress_llla.translate[2] + '(<em>' + ip + '</em>) ' + loginpress_llla.translate[3] + ' </span></div>').appendTo($('#loginpress_limit_login_log_wrapper'));
						$('#loginpress_limit_login_log_wrapper .loginpress_limit_login_log_message').fadeIn();
						setTimeout(function () {
							$('#loginpress_limit_login_log_wrapper .loginpress_limit_login_log_message').fadeOut();
						}, 900);
					} else {
						$('#loginpress_limit_login_log_wrapper .loginpress_limit_login_log_message').children('span').html('' + loginpress_llla.translate[2] + '(<em>' + ip + '</em>) ' + loginpress_llla.translate[3] + '');
						$('#loginpress_limit_login_log_wrapper .loginpress_limit_login_log_message').fadeIn();
						setTimeout(function () {
							$('#loginpress_limit_login_log_wrapper .loginpress_limit_login_log_message').fadeOut();
						}, 900);
					}
				}
			}); // !Ajax.

		}); // !click .loginpress-attempts-whitelist.

		// Apply ajax on click attempts tab blacklist button.
		$(document).on("click", "input.loginpress-attempts-blacklist", function (event) {
			$('.loginpress_llla_loader_inner').show();

			event.preventDefault();

			var el = $(this);
			var tr = el.closest('tr');
			var id = tr.attr("data-login-attempt-user");
			var ip = el.closest('tr').attr("data-ip");
			var _nonce = loginpress_llla.user_nonce;

			$.ajax({

				url: ajaxurl,
				type: 'POST',
				data: 'id=' + id + '&ip=' + ip + '&action=loginpress_attempts_blacklist' + '&security=' + _nonce,
				beforeSend: function () {

					// tr.find( '.loginpress_autologin_code p' ).html('');
					tr.find('.autologin-sniper').show();
					tr.find('.loginpress-attempts-unlock').attr("disabled", "disabled");
					tr.find('.loginpress-attempts-whitelist').attr("disabled", "disabled");
					tr.find('.loginpress-attempts-blacklist').attr("disabled", "disabled");
				},
				success: function (response) {

					$('#loginpress_limit_login_blacklist .dataTables_empty').remove();
					var blacklist_ip = $('#loginpress_attempts_id_' + id).find('.lg_attempts_ip').html();
					$('#loginpress_attempts_id_' + id).find('td').eq(2).find('.attempts-sniper').remove();
					var blacklist_user = $('#loginpress_attempts_id_' + id).find('td').eq(2).html();

					var blacklist_tr = '<tr id="loginpress_blacklist_id_' + id + '" data-login-blacklist-user="' + id + '" role="row" class="even"><td class="loginpress_limit_login_blacklist_ips">' + blacklist_ip + '</td><td class="loginpress_limit_login_blacklist_actions"><div class="lp-tbody-cell"><button class="loginpress-blacklist-clear button button-primary" type="button" value="Clear"></button></div></td></tr>';
					// Remove data from limit attemps table.
					var row = limitTable.row(el.parents('tr'));
					row.remove();

					// Add data to black_table.
					var getNode = $.parseHTML(blacklist_tr);
					blackTable.row.add(getNode[0]).draw();
					var ip = el.closest('tr').attr("data-ip");
					limitTable.rows('[data-ip="' + ip + '"]').remove().draw(false);
					$('.loginpress_llla_loader_inner').hide();

					if ($('.loginpress_limit_login_log_message').length == 0) {
						$('<div class="loginpress_limit_login_log_message"><span>' + loginpress_llla.translate[2] + '(<em>' + ip + '</em>) ' + loginpress_llla.translate[4] + '</span></div>').appendTo($('#loginpress_limit_login_log_wrapper'));
						$('#loginpress_limit_login_log_wrapper .loginpress_limit_login_log_message').fadeIn();
						setTimeout(function () {
							$('#loginpress_limit_login_log_wrapper .loginpress_limit_login_log_message').fadeOut();
						}, 500);
					} else {
						$('#loginpress_limit_login_log_wrapper .loginpress_limit_login_log_message').children('span').html('' + loginpress_llla.translate[2] + '(<em>' + ip + '</em>) ' + loginpress_llla.translate[4] + '');
						$('#loginpress_limit_login_log_wrapper .loginpress_limit_login_log_message').fadeIn();
						setTimeout(function () {
							$('#loginpress_limit_login_log_wrapper .loginpress_limit_login_log_message').fadeOut();
						}, 500);
					}
				}
			}); // !Ajax.

		}); // !click .loginpress-attempts-blacklist.

		// Apply ajax on click attempts tab unlock button.
		$(document).on("click", ".loginpress-attempts-unlock", function (event) {
			$('.loginpress_llla_loader_inner').show();

			event.preventDefault();

			var el = $(this);
			var tr = el.closest('tr');
			var id = tr.attr("data-login-attempt-user");
			var ip = el.closest('tr').attr("data-ip");
			var _nonce = loginpress_llla.user_nonce;

			$.ajax({

				url: ajaxurl,
				type: 'POST',
				data: 'id=' + id + '&ip=' + ip + '&action=loginpress_attempts_unlock' + '&security=' + _nonce,
				beforeSend: function () {
					// tr.find( '.loginpress_autologin_code p' ).html('');
					tr.find('.autologin-sniper').show();
					tr.find('.loginpress-attempts-unlock').attr("disabled", "disabled");
					tr.find('.loginpress-attempts-whitelist').attr("disabled", "disabled");
					tr.find('.loginpress-attempts-blacklist').attr("disabled", "disabled");
				},
				success: function (response) {
					$('.loginpress_llla_loader_inner').hide();

					var ip = el.closest('tr').attr("data-ip");
					limitTable.rows('[data-ip="' + ip + '"]').remove().draw(false);
				}
			}); // !Ajax.

		}); // !click .loginpress-attempts-unlock.


		// Apply ajax on click whitelist tab clear button.
		$(document).on("click", ".loginpress-whitelist-clear", function (event) {

			event.preventDefault();

			var el = $(this);
			var tr = el.closest('tr');
			var ip = tr.children('td:first-child').data('whitelist-ip');
			var _nonce = loginpress_llla.user_nonce;

			$.ajax({

				url: ajaxurl,
				type: 'POST',
				data: 'ip=' + ip + '&action=loginpress_whitelist_clear' + '&security=' + _nonce,
				beforeSend: function () {
					// tr.find( '.loginpress_autologin_code p' ).html('');
					tr.find('.autologin-sniper').show();
					tr.find('.loginpress-whitelist-clear').attr("disabled", "disabled");
				},
				success: function (response) {
					var row = whiteTable.row(el.parents('tr'))
						.remove()
						.draw();
				}
			}); // !Ajax.

		}); // !click .loginpress-whitelist-clear.

		// Apply ajax on click blacklist tab clear button.
		$(document).on("click", ".loginpress-blacklist-clear", function (event) {

			event.preventDefault();

			var el = $(this);
			var tr = el.closest('tr');
			var ip = tr.children('td:first-child').data('blacklist-ip');

			var _nonce = loginpress_llla.user_nonce;
			$.ajax({

				url: ajaxurl,
				type: 'POST',
				data: 'ip=' + ip + '&action=loginpress_blacklist_clear' + '&security=' + _nonce,
				beforeSend: function () {
					// tr.find( '.loginpress_autologin_code p' ).html('');
					tr.find('.autologin-sniper').show();
					tr.find('.loginpress-blacklist-clear').attr("disabled", "disabled");
				},
				success: function (response) {
					var row = blackTable.row(el.parents('tr'))
						.remove()
						.draw();
					// blackTable.rows('.seleted').remove().draw(false);
				}
			}); // !Ajax.

		}); // !click .loginpress-whitelist-clear.

		// Block "+", "-" in input fields.
		$('#loginpress_limit_login_attempts .form-table input[type="number"]').on('keypress', function (evt) {
			if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
				evt.preventDefault();
			}
		});
		$('#loginpress_limit_login_attempts .form-table input[type="text"]').on('keydown', function (evt) {
			if (evt.keyCode == 13) {
				evt.preventDefault();
			  }
		});
		$(document).on("submit", "#loginpress_limit_login_attempts form", function (event) {
			$('.ip_add_remove input[type="text"]').val('');
		});

		$(document).on('click', '.add_white_list , .add_black_list', function (el) {

			var ip = $('.ip_add_remove input[type="text"]').val();
			var _security = loginpress_llla.manual_ip_cta;
			var action = $(this).data('action');

			$('.ip_add_remove td .message').remove();

			if ('' == ip) {
				$('.ip_add_remove td').append('<p class="message error"> <span>' + loginpress_llla.translate[6] + '</span> </p>');
				$('.ip_add_remove td .error').delay(5000).fadeOut(500);
				return false;
			} else{
				var special_ips = ['255.255.255.255', '0.0.0.0'];
				if( special_ips.includes(ip) ) {
					$('.ip_add_remove td').append('<p class="message error"> <span>' + loginpress_llla.translate[7] + '</span> </p>');
					$('.ip_add_remove td .error').delay(5000).fadeOut(500);
					return false;
				}
			}

			if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ip)) {
				$('.ip_add_remove td .message').remove();
			} else {
				$('.ip_add_remove td').append('<p class="message error"> <span>' + loginpress_llla.translate[5] + '</span> </p>');
				$('.ip_add_remove td .error').delay(5000).fadeOut(500);
				return false;
			}

			var request_data = {
				'security': _security,
				'ip_action': action,
				'ip': ip,
				'action': 'loginpress_white_black_list_ip',
			};
			$.ajax({

				url: ajaxurl,
				type: 'POST',
				data: request_data,
				beforeSend: function () {
					$('.ip_add_remove button').attr('disabled', true);
					$('.lla-spinner').css('display', 'inline-block');
				},
				success: function (res) {
					$('.ip_add_remove button').attr('disabled', false);
					if (res.success) {
						$('.lla-spinner').css('display', 'none');
						$('.ip_add_remove td').append('<p class="message success"><span>' + res.data.message + '</span></p>');
						$('.ip_add_remove td .success').delay(5000).fadeOut(500);

						refreshIpList('white_list', _security);
						refreshIpList('black_list', _security);
					} else {
						$('.ip_add_remove td').append('<p class="message error"><span>' + res.data.message + '</span></p>');
						$('.ip_add_remove td .error').delay(5000).fadeOut(500);
					}
				}
			}); // !Ajax.

		});

		/**
		 * Get and update list of ip.
		 *
		 * @since 1.3.0
		 * @param {string} list name on list to update
		 */
		function refreshIpList(list, _security) {

			var request_data = {
				'security': _security,
				'action': 'loginpress_' + list + '_records',
			};
			$.ajax({

				url: ajaxurl,
				type: 'POST',
				data: request_data,
				success: function (res) {
					let tableWhiteList = '#loginpress_limit_login_whitelist';
					let tableBlackList = '#loginpress_limit_login_blacklist';
					if (res.success) {
						if (list == 'white_list') {
							whiteTable.draw();
							whiteTable.rows.add($(res.data.tbody)).draw();
							whiteTable.clear();
						}

						if (list == 'black_list') {
							// $(tableBlackList).find('tbody').html(res.data.tbody);
							blackTable.clear();
							blackTable.rows.add($(res.data.tbody)).draw();
							blackTable.draw();

						}
						$('.ip_add_remove button').attr('disabled', false);
						$('.lla-spinner').hide();
					} else {

						if (list == 'white_list') {
							let tableWhiteList = '#loginpress_limit_login_whitelist';
							// $(tableWhiteList).DataTable();
							whiteTable.clear().draw();
						}

						if (list == 'black_list') {
							let tableBlackList = '#loginpress_limit_login_blacklist';
							blackTable.clear().draw();
							// $(tableBlackList).DataTable();

						}
					}
					//var table = jQuery('#loginpress_limit_login_whitelist').dataTable()
					//table.fnClearTable()
				}
			}); // !Ajax.
		}
	});
})(jQuery);