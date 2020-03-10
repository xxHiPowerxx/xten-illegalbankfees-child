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
		function readyFuncs() {
			addHTMLValidationToCF();
		}
		readyFuncs();
	});
})(jQuery);
