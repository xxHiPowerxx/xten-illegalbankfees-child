// Write JS here
(function($) {
	$(document).on("ready", function() {
		function addHTMLValidationToCF() {
			$("form.wpcf7-form").each(function() {
				$(this)
					.removeAttr("novalidate")
					.find('[aria-required="true"]')
					.prop("required", true);
			});
		}
		function scrollToTarget() {
			if ($("body").hasClass("xten-mobile-menu-active")) {
				$("#sidebar-modal").modal("show");
			} else {
				var sideBarTop = $("#right-sidebar").position().top,
					siteHeaderHeight = window.siteHeaderHeight
						? parseFloat(window.siteHeaderHeight)
						: $(".site-header")[0].getBoundingClientRect().height,
					padding = 30,
					scrollPosition = sideBarTop - siteHeaderHeight - padding;
				$("html, body").animate({ scrollTop: scrollPosition }, 350);
			}
		}
		function interceptHashChange() {
			$(window).on("load hashchange", function(e) {
				if (window.location.hash === "#contact-us") {
					scrollToTarget();
				}
			});
		}
		function scrollToTargetOnClick() {
			$('[href*="#contact-us"]').on("click", function() {
				scrollToTarget();
			});
		}
		function initScrollToTarget() {
			interceptHashChange();
			scrollToTargetOnClick();
		}
		function readyFuncs() {
			addHTMLValidationToCF();
			initScrollToTarget();
		}
		readyFuncs();
	});
})(jQuery);
