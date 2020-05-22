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
		function dynamicInput() {
			$('.dynamicInput').each(function () {
				$(this).find('.dynamicInputField').each(function () {
					var inputId = '#' + $(this).attr('data-input-id'),
						getVal = $(inputId).first().val();
					$(this).text(getVal);
				});
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
					qualifier.each(function () {
						var input = $(this);
						if (!$(this).is('input') && !$(this).is('select')) {
							input = $(this).find('input, select');
						}
						if (input.is('select') && input.is('.specifyOther')) {
							var selectedOption = input.children(':selected'),
								// If user chooses "Other" in .specifyOther select they do not qualify.
								notOtherOption = selectedOption.attr('data-other-option') ? false : true;
							qualifierVals.push(notOtherOption);
						} else { // ! input.is('select')
							if (input.length > 1) {
								realQualifier = $(this).find('input[value="Yes"]');
							} else {
								realQualifier = input;
							}
							qualifierVals.push(realQualifier.prop('checked'));
						} // endif ( input.is('select') )
					});
					allQualify = qualifierVals.every(function (val) { return val });
					if (allQualify) {
						modal = $('#contact-qualify-modal');
					} else {
						modal = $('#contact-rejection-modal');
					}
					dynamicInput();
					modal.modal('show');
				});
			});
		}
		// Function to add Text Field when "Other" is selected on input.
		function specifyOther() {
			$('.specifyOther').each(function () {
				var specifyOther = $(this),
					parent = $(this).closest('.specifyOtherParent'),
					textInput = parent.find('.specifyOtherTextInput'),
					addOtherOption = parent.find('.addOtherOption'),
					addOtherOptionTitle = addOtherOption.attr('title'),
					addOtherOptionLabel = addOtherOption.attr('aria-label'),
					otherOption;
				$(this).children().each(function () {
					if ($(this).text() === 'Other') {
						otherOption = $(this);
						this.value = '';
						$(this).on('select', function () {
							$(this).parent().trigger('change');
						});
					}
				});
				$(this).on('change', function () {
					if ($(this).val() === '' && otherOption.is(':selected')) {
						parent.addClass('otherSelected');
						textInput.prop('required', true).focus();
					} else {
						parent.removeClass('otherSelected');
						textInput.prop('required', false);
					}
				});
				function addOtherOptionFunc() {
					var textInputVal = textInput.val();
					if (!specifyOther.children('[value="' + textInputVal + '"]').length) {
						var optionTag = document.createElement('option');
						optionTag.value = textInputVal;
						optionTag.text = textInputVal;
						optionTag.setAttribute('data-other-option', true);
						otherOption.before(optionTag);
					}
					$(optionTag).prop('selected', true);
					parent.removeClass('otherSelected');
					textInput.prop('required', false);
				}
				addOtherOption.on('click keyup', function (e) {
					var key = e.key || e.keyCode;
					if (key) {
						var enterKey = key === "Enter" || key === 13;
						var spaceKey = key === " " || key === 32;
						if (!(enterKey || spaceKey)) {
							return;
						}
					}
					addOtherOptionFunc();
				});
				function checkForEmptyInput(elem) {
					if (elem.val().length === 0) {
						addOtherOption.attr('title', 'Cancel').attr('aria-label', 'Cancel');
						parent.addClass('inputEmpty');
					} else {
						addOtherOption.attr('title', addOtherOptionTitle).attr('aria-label', addOtherOptionLabel);
						parent.removeClass('inputEmpty');
					}
				}
				textInput.each(function () {
					checkForEmptyInput($(this));
					$(this).on('keyup', function () {
						checkForEmptyInput($(this));
					}).on('keydown', function (e) {
						var key = e.key || e.keyCode;
						if (key) {
							var enterKey = key === "Enter" || key === 13;
							if (enterKey) {
								e.stopImmediatePropagation();
								e.preventDefault();
								addOtherOptionFunc();
							}
						}
					});
				});
			}).trigger('change');
		}
		function readyFuncs() {
			interceptHashChange();
			scrollToTargetOnClick();
			checkForQualify();
			specifyOther();
			addHTMLValidationToCF();
		}
		readyFuncs();
	});
})(jQuery);
