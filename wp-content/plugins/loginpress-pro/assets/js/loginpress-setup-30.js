(function ($) {

	$(document).ready(function () {
		// First upgrade setp.
		$(document).on('click', '#loginpress-pro-setup-30-update-addons', function (e) {
			$('#loginpress-pro-setup-30-update-addons').find("button[type='button']").prop("disabled", true);
			e.preventDefault();
			e.stopPropagation();
			getAddonPlugins('');
		});

		// Second upgrade setup.
		$(document).on('click', '#loginpress-pro-setup-30-update-free', function (e) {
			e.preventDefault();
			e.stopPropagation();

			let nonce = loginpress_setup.upgrade_free_security;
			update_free_plugin('loginpress/loginpress.php', nonce);
		});

		// Extract plugin base name from slug.
		const getCleanedSlug = function (pluginName) {
			let cleanedSlug = pluginName.split("/");
			cleanedSlug = cleanedSlug[0].split("loginpress-");
			cleanedSlug = cleanedSlug[1];

			return cleanedSlug;
		}

		// Check and send update calls for addons.
		const getAddonPlugins = function (pluginUpdated) {

			let nonce = loginpress_setup.update_addon_security;
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'loginpress_pro_update_addon_plugin',
					plugin_updated: pluginUpdated,
					security: nonce,
				},
				success: function (res) {
					if (true == res) {
						$('.loginpress-pro-30-all-update').show();
						setTimeout(function () {
							$('.loginpress-pro-30-update-addons-wrapper').hide();
							$('.loginpress-pro-30-finish-wrapper').css('display', 'flex');

							loginPressConfetti();
							setTimeout(function () {
								$("#loginPressConfetti").fadeOut();
							}, 3500);
						}, 1500);
					} else {
						upadtePlugin(res[0], res[1], true);
					}

					// location.reload();
				},
				error: function (res) {
				}
			});
		}

		// Update the LoginPress Free.
		function update_free_plugin(pluginName, nonce) {

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					plugin: pluginName,
					update: pluginName,
					slug: 'loginpress',
					action: 'loginpress_free_update_plugin',
					security: nonce,
				},
				beforeSend: function () {
					$('#loginpress-pro-setup-30-update-addons').attr('disabled', 'disabled')

					// first step loader initiate
					$('.loginpress-install.updating').css('display', 'flex');
				},
				success: function (res) {

					// first step loader stop
					$('.loginpress-install.updating').css('display', 'none');
					$('.loginpress-install.updated-30').css('display', 'flex');
					$('.circle-loader-30').addClass('load-complete-30');
					$('.checkmark').show();
					setTimeout(function () {
						$('.loginpress-install.updated-30, .loginpress-pro-30-update-main-wrapper').css('display', 'none');
						$('.loginpress-pro-30-update-addons-wrapper').css('display', 'flex-start');
					}, 1500);
					if (res.data.allUpdated) {
						location.replace(res.data.settings_redirect);
					} else {
						location.reload();
					}
				},
				error: function (res) {
				}
			});
		}

		// Update plugin.
		const upadtePlugin = function (pluginName, nonce, isAddon) {

			let pluginBaseName = getCleanedSlug(pluginName);

			$.ajax({
				type: 'GET',
				url: 'update.php',
				data: {
					plugin: pluginName,
					action: 'upgrade-plugin',
					_wpnonce: nonce,
				},
				beforeSend: function () {
					$('#loginpress-pro-setup-30-update-addons').attr('disabled', 'disabled')

					// first step loader initiate
					$('.loginpress-install.updating').css('display', 'flex');

					// second step loader initiate
					$('.loginpress-pro-30-update-addons-list #' + pluginBaseName).addClass('addon-activating');
					$('.loginpress-pro-30-update-addons-list #' + pluginBaseName).find('.progress').html('Updating ...');
					$('.loginpress-pro-30-update-addons-list #' + pluginBaseName).find('.status').html('<div class="loginpressAddons-install updated-30" style="display:none;"> <div class="circle-loader-30"> <div class="checkmark draw"></div> </div></div>');
				},
				success: function (res) {
					// second step loader stop
					$('.loginpress-pro-30-update-addons-list #' + pluginBaseName).addClass('addon-activated');
					$('.loginpress-pro-30-update-addons-list #' + pluginBaseName).find('.progress').html('Updated');

					if (true === isAddon) {
						getAddonPlugins(pluginName)
					} else {
						// first step loader stop
						$('.loginpress-install.updating').css('display', 'none');
						$('.loginpress-install.updated-30').css('display', 'flex');
						$('.circle-loader-30').addClass('load-complete-30');
						$('.checkmark').show();
						setTimeout(function () {
							$('.loginpress-install.updated-30, .loginpress-pro-30-update-main-wrapper').css('display', 'none');
							$('.loginpress-pro-30-update-addons-wrapper').css('display', 'flex-start');
						}, 1500);
					}
				},
				error: function (res) {
					// console.log(res);
				}
			});
		}
		function loginPressConfetti() {
			var loginPressConfetti = document.createElement('canvas');
			loginPressConfetti.id = 'loginPressConfetti';
			document.body.prepend(loginPressConfetti);
			// global variables
			const confetti = document.getElementById('loginPressConfetti');
			const confettiCtx = confetti.getContext('2d');
			let container, confettiElements = [], clickPosition;

			// helper
			rand = (min, max) => Math.random() * (max - min) + min;

			// params to play with
			const confettiParams = {
				// number of confetti per "explosion"
				number: 70,
				// min and max size for each rectangle
				size: { x: [5, 20], y: [10, 18] },
				// power of explosion
				initSpeed: 25,
				// defines how fast particles go down after blast-off
				gravity: 0.65,
				// how wide is explosion
				drag: 0.58,
				// how slow particles are falling
				terminalVelocity: 6,
				// how fast particles are rotating around themselves
				flipSpeed: 0.017,
			};
			const colors = [
				{ front: '#7ED15E', back: '#539930' },
				{ front: '#FFA64D', back: '#995826' },
				{ front: '#FF7062', back: '#C73D38' },
				{ front: '#FF6E99', back: '#CC4A70' },
				{ front: '#A590C6', back: '#78678F' },
				{ front: '#8096C6', back: '#5E759D' },
				{ front: '#33CCCC', back: '#008080' }
			];

			setupCanvas();
			updateConfetti();

			confetti.addEventListener('click', addConfetti);
			window.addEventListener('resize', () => {
				setupCanvas();
				hideConfetti();
			});

			// Confetti constructor
			function Conf() {
				this.randomModifier = rand(-1, 1);
				this.colorPair = colors[Math.floor(rand(0, colors.length))];
				this.dimensions = {
					x: rand(confettiParams.size.x[0], confettiParams.size.x[1]),
					y: rand(confettiParams.size.y[0], confettiParams.size.y[1]),
				};
				this.position = {
					x: clickPosition[0],
					y: clickPosition[1]
				};
				this.rotation = rand(0, 2 * Math.PI);
				this.scale = { x: 1, y: 1 };
				this.velocity = {
					x: rand(-confettiParams.initSpeed, confettiParams.initSpeed) * 0.4,
					y: rand(-confettiParams.initSpeed, confettiParams.initSpeed)
				};
				this.flipSpeed = rand(0.2, 1.5) * confettiParams.flipSpeed;

				if (this.position.y <= container.h) {
					this.velocity.y = -Math.abs(this.velocity.y);
				}

				this.terminalVelocity = rand(1, 1.5) * confettiParams.terminalVelocity;

				this.update = function () {
					this.velocity.x *= 0.98;
					this.position.x += this.velocity.x;

					this.velocity.y += (this.randomModifier * confettiParams.drag);
					this.velocity.y += confettiParams.gravity;
					this.velocity.y = Math.min(this.velocity.y, this.terminalVelocity);
					this.position.y += this.velocity.y;

					this.scale.y = Math.cos((this.position.y + this.randomModifier) * this.flipSpeed);
					this.color = this.scale.y > 0 ? this.colorPair.front : this.colorPair.back;
				}
			}

			function updateConfetti() {
				confettiCtx.clearRect(0, 0, container.w, container.h);

				confettiElements.forEach((c) => {
					c.update();
					confettiCtx.translate(c.position.x, c.position.y);
					confettiCtx.rotate(c.rotation);
					const width = (c.dimensions.x * c.scale.x);
					const height = (c.dimensions.y * c.scale.y);
					confettiCtx.fillStyle = c.color;
					confettiCtx.fillRect(-0.5 * width, -0.5 * height, width, height);
					confettiCtx.setTransform(1, 0, 0, 1, 0, 0)
				});

				confettiElements.forEach((c, idx) => {
					if (c.position.y > container.h ||
						c.position.x < -0.5 * container.x ||
						c.position.x > 1.5 * container.x) {
						confettiElements.splice(idx, 1)
					}
				});
				window.requestAnimationFrame(updateConfetti);
			}

			function setupCanvas() {
				container = {
					w: confetti.clientWidth,
					h: confetti.clientHeight
				};
				confetti.width = container.w;
				confetti.height = container.h;
			}

			function addConfetti(e) {
				const canvasBox = confetti.getBoundingClientRect();
				if (e) {
					clickPosition = [
						e.clientX - canvasBox.left,
						e.clientY - canvasBox.top
					];
				} else {
					clickPosition = [
						canvasBox.width * Math.random(),
						canvasBox.height * Math.random()
					];
				}
				for (let i = 0; i < confettiParams.number; i++) {
					confettiElements.push(new Conf())
				}
			}

			function hideConfetti() {
				confettiElements = [];
				window.cancelAnimationFrame(updateConfetti)
			}

			confettiLoop();
			function confettiLoop() {
				addConfetti();
				setTimeout(confettiLoop, 200 + Math.random() * 500);
			}
		}
	})

})(jQuery)