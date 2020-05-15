// Write JS here
(function ($) {
	$(document).on("ready", function () {
		function addHTMLValidationToCF() {
			$("form.wpcf7-form").each(function () {
				$(this)
					.removeAttr("novalidate")
					.find('[aria-required="true"]')
					.prop("required", true);
			});
		}
		function scrollToTarget($target) {
			// Make sure $target is a jQuery object.
			$target = $target instanceof jQuery ? $target : $($target);
			var targetTop = $target.position().top,
				siteHeaderHeight = window.siteHeaderHeight
					? parseFloat(window.siteHeaderHeight)
					: $(".site-header")[0].getBoundingClientRect().height,
				padding = 30,
				scrollPosition = targetTop - siteHeaderHeight - padding;
			$("html, body").animate({ scrollTop: scrollPosition }, 350);
		}
		function interceptHashChange($target = null) {
			$(window).on("load hashchange", function (e) {
				if (window.location.hash && $(window.location.hash).length) {
					$target = $target || $(window.location.hash);
				}
				if ($target !== null && $target.length) {
					scrollToTarget($target);
				}
			});
		}
		function scrollToTargetOnClick() {
			$('[href*="#contact-us"]').on("click keyup", function (e) {
				var key = e.key || e.keyCode;
				if (key) {
					var enterKey = key === "Enter" || key === 13;
					var spaceKey = key === " " || key === 32;
					if (!(enterKey || spaceKey)) {
						return;
					}
				}
				e.stopImmediatePropagation();
				e.preventDefault();
				if ($("body").hasClass("xten-mobile-menu-active")) {
					$("#sidebar-modal").modal("show");
				} else {
					history.replaceState(
						null,
						null,
						document.location.pathname + $(this).attr("href")
					);
					scrollToTarget($("#right-sidebar"));
				}
			});
		}
		function checkForQualify() {
			$('.checkForQualify').each(function () {
				var form = $(this).closest('.wpcf7');
				// Localhost cannot send mail,
				// but modal should only be triggered on successful mail send.
				// form.on('wpcf7submit ', function () {
				form.on('wpcf7mailsent', function () {
					var qualifier = $(this).find('.qualifier'),
						qualifierVals = [],
						realQualifier,
						modal,
						allQualify;
					qualifier.each(function (index) {
						var input = $(this).find('input');
						if (input.length > 1) {
							realQualifier = $(this).find('input[value="Yes"]');
						} else {
							realQualifier = input;
						}
						qualifierVals.push(realQualifier.prop('checked'));
					});
					allQualify = qualifierVals.every(function (val) { return val });
					if (allQualify) {
						modal = $('#contact-qualify-modal');
					} else {
						modal = $('#contact-rejection-modal');
					}
					modal.modal('show');
				});
			});
		}
		function initScrollToTarget() {
			interceptHashChange();
			scrollToTargetOnClick();
			checkForQualify();
		}
		function readyFuncs() {
			addHTMLValidationToCF();
			initScrollToTarget();
		}
		readyFuncs();
	});
})(jQuery);
