// Write JS here
(function ($) {
	$(document).on("ready", function () {
		var body = $("body"),
			sideBarModal = $("#sidebar-modal").first(),
			bottomOut,
			rightSidebar = $('#right-sidebar').first(),
			secondary = rightSidebar.children('.primary-sidebar').first(),
			sideBarParent = $([rightSidebar[0], $('#sidebar-modal')[0]]),
			formStates = [];

		sideBarParent.each(function () {
			var thisFormStates = $(this).find('.formState');
			this['formStates'] = thisFormStates;
			if (thisFormStates.length) {
				$(this).addClass('formStateParent');
				formStates.push($(this));
			}
		});

		function startWork(elem) {
			window.workStarted = window.workStarted || {};
			window.workStarted[elem] = true;
		}
		function finishWork(elem) {
			window.dispatchEvent(new CustomEvent('finishWork'));
			delete window.workStarted[elem];
		}

		function renameSideBarModalIds() {
			var dataParentId;
			sideBarModal.find('[id]:not(.wpcf7)').each(function () {
				var currentId = $(this).attr('id'),
					newId = 'sidebar-modal-' + currentId;
				if ($(this).is('.primary-sidebar')) {
					dataParentId = newId;
				}
				$(this).attr('id', newId);
				if ($(this).is('.formState')) {
					$(this).attr('data-parent', '#' + dataParentId);
					$(this).find('[data-target]').each(function () {
						var oldDataTarget = $(this).attr('data-target'),
							dataTargetSansHash = oldDataTarget.replace('#', ''),
							newDataTarget = 'sidebar-modal-' + dataTargetSansHash;
						$(this).attr('data-target', '#' + newDataTarget).attr('aria-controls', newDataTarget);
					});
				} // endif ( $(this).is('.formState') ) {
			})
		}
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
		function toggleGideonChatBot() {
			$('.wc-floating-wrap:visible').first().trigger('click');
		}
		function triggerGideonChatBot() {
			$('[data-open="gideon-chat-bot"').on("click keyup", function (e) {
				var key = e.key || e.keyCode;
				if (key) {
					var enterKey = key === "Enter" || key === 13;
					var spaceKey = key === " " || key === 32;
					if (!(enterKey || spaceKey)) {
						return;
					}
				}
				toggleGideonChatBot();
			});
		}
		function contactUsClick() {
			var _hash = '#contact-us';
			// function coreFunc($this) {
			// 	var targetForm;
			// 	if (body.hasClass("xten-mobile-menu-active")) {
			// 		sideBarModal.modal("show");
			// 		targetForm = sideBarModal;
			// 	} else {
			// 		var secondaryPosition = secondary.css('position');
			// 		targetForm = rightSidebar;
			// 		if (
			// 			secondaryPosition !== 'fixed' &&
			// 			secondaryPosition !== 'absolute'
			// 		) {
			// 			scrollToTarget(rightSidebar);
			// 		}
			// 	}
			// 	// $this is only defined on the click/keyup events
			// 	// Not the initial load _hash check usage.
			// 	if ( $this !== undefined ) {
			// 		history.replaceState(
			// 			null,
			// 			null,
			// 			document.location.pathname + $this.attr("href")
			// 		);
			// 	}
			// 	targetForm.find('input:visible, select:visible, textarea:visible').first().focus();
			// }
			$('[href*="' + _hash + '"]').on("click keyup", function (e) {
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
				toggleGideonChatBot();
				// coreFunc( $(this) );
			});
			// Fire on Load if location hash is _hash
			if ( location.hash === _hash ) {
				// coreFunc();
				toggleGideonChatBot();
			}
		}
		function dynamicInput($formFilled) {
			$('.dynamicInput').each(function () {
				$(this).find('.dynamicInputField').each(function () {
					var dynamicInputTar = $formFilled.find('.dynamicInputTar').first(),
						getVal = $(dynamicInputTar).val();
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

					var $formStateResponse = $(this).find('.formStateResponse');
					if ($formStateResponse.length) {
						$(this).addClass('showformStateResponse');
						$formStateResponse.collapse('show');
					}

					dynamicInput(form);
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
				textInput.on('blur', function(){
					// Only Add other option on blur if the
					// addOtherOption Button has not been tabbed to
					setTimeout(function(){
						if ( ! addOtherOption.is(':focus') ) {
							addOtherOptionFunc();
						} else {
							// If it has been tabbed to, make sure that the function is
							// triggered on the buttons blur event.
							addOtherOption.one('blur', function(){
								addOtherOptionFunc();
							});
						}
					});
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
		function onEnterTriggerClick() {
			$('.onEnterTriggerClick').on('keypress', function (e) {
				var key = e.which === 13;
				if ($(this).is('.onSpaceTriggerClick')) {
					key = e.which === 13 || e.which === 32
				}
				if (key) {
					e.preventDefault();
					$(this).trigger('click');
				}
			});
		}
		function formStateNav() {
			function setAttributes(el, attrs) {
				for (var key in attrs) {
					el.setAttribute(key, attrs[key]);
				}
			}
			$('.wpcf7-form').each(function () {
				var $form = $(this),
					$formStateParent = $(this).closest('.formStateParent');
				if ($formStateParent.length) {
					var $formStates = $formStateParent[0]['formStates'],
						parentId = $(this).parent().attr('id'),
						nav = document.createElement('nav'),
						ctnrBtnNav = document.createElement('div'),
						progress = document.createElement('div'),
						validationOutPut = document.createElement('div'),
						responseOutPut = $(this).find('.wpcf7-response-output');
					ctnrBtnNav.classList.add('ctnr-btn-formState-nav');
					$(progress).addClass('formState-nav-progress progress-bar xten-theme-color-bg');
					$(validationOutPut).addClass('formState-validation-output nowrap-parent');
					$(validationOutPut).html('<span>One or more fields have an error.</span> <span>Please check and try again.</span>');

					nav.setAttribute('id', 'formState-nav-' + parentId);
					nav.classList.add('formState-nav');

					$form.before(nav);
					nav.appendChild(ctnrBtnNav);
					nav.appendChild(validationOutPut);
					ctnrBtnNav.appendChild(progress);

					$formStates.each(function () {

						var formStateId = $(this).attr('id'),
							button = document.createElement('button'),
							ariaExpanded = $(this).is('.show');

						$(button).addClass('btn-formState-nav preventExpandedCollapse');
						if ($(this).is('.formStateResponse')) {
							button.classList.add('btn-formStateResponse');
						}
						if ($(this).is('.formStateSubmit')) {
							button.classList.add('btn-formStateSubmit');
							responseOutPut.detach();
							$(this).find('.formState-content').after(responseOutPut);
						}
						setAttributes(button, {
							'type': 'button',
							'data-toggle': 'collapse',
							'data-target': '#' + formStateId,
							'aria-expanded': ariaExpanded,
							'aria-controls': formStateId
						});
						ctnrBtnNav.appendChild(button);
					});
				}
			});
		}
		function sizeSideBar() {
			sideBarParent.each(function () {
				if (formStates.length) {
					startWork(this);
					var thisSideBarParent = $(this),
						isRightSideBar = $(this).is('#right-sidebar'),
						// if is NOT right sidebar "secondary" has not yet been found.
						thisSecondary = isRightSideBar ? secondary : $(this).find('.primary-sidebar').first(),
						sideBarFormWrapper = thisSecondary.find('.wpcf7').first(),
						sideBarForm = sideBarFormWrapper.children('.wpcf7-form'),
						sizer = sideBarForm.find('.sizer'),
						sideBarHeight = 0,
						sideBarWidth = 'auto',
						windowHeight = window.innerHeight,
						maxHeight;
					// console.log('thisSecondary', thisSecondary);
					// console.log('sideBarFormWrapper', sideBarFormWrapper);
					// console.log('sideBarForm', sideBarForm);
					// console.log('sideBarForm', sideBarForm);
					if ( isRightSideBar ) {
						var sideBarFormPositionTop = Math.max( 0, sideBarForm[0].getBoundingClientRect().top ),
						padTargetClass = isRightSideBar ?
							'.main-container' :
							'.xten-modal',
						padTarget = sideBarForm.closest(padTargetClass),
						padTargetMarginTop = 0;
						if ( padTarget.length ) {
							var padTargetComputedStyle = getComputedStyle( padTarget[0] );
							padTargetMarginTop = parseInt( padTargetComputedStyle.marginTop ) + parseInt( padTargetComputedStyle.paddingTop );
							// If Modal add padding bottom.
							// if ( ! isRightSideBar ) {
							// 	padTargetMarginTop += parseInt( padTargetComputedStyle.paddingBottom );
							// }
						}

						maxHeight = windowHeight - sideBarFormPositionTop - padTargetMarginTop;

					}
					// console.log('windowHeight', windowHeight);
					// console.log('sideBarFormPositionTop', sideBarFormPositionTop);
					// console.log('padTarget', padTarget);
					// console.log('padTargetMarginTop', padTargetMarginTop);
					// console.log('maxHeight', windowHeight - sideBarFormPositionTop - padTargetMarginTop);
					if (isRightSideBar && thisSecondary.css('position') === 'fixed') {
						sideBarWidth = 0;
						var sideBarParentComputedStyle = getComputedStyle(this),
							sideBarParentInnerWidth = parseFloat(sideBarParentComputedStyle.width) - parseFloat(sideBarParentComputedStyle.paddingLeft) - parseFloat(sideBarParentComputedStyle.paddingRight),
							secondaryWidth = secondary[0].getBoundingClientRect().width,
							sideBarCurrentWidth = sideBarForm[0].getBoundingClientRect().width,
							sidePadding = secondaryWidth - sideBarCurrentWidth,
							sideBarWidth = sideBarParentInnerWidth - sidePadding;
					} // endif ( sideBarParent.is('#right-sidebar') ) {
					// Set the sideBarHeight to the tallest formState.
					sizer.each(function () {
						var sizeRef = $(this).find('.sizeRef'),
							innerHeight = 0;
						thisSideBarParent.addClass('sizing');
						sizeRef.each(function () {
							var computedStyle = getComputedStyle(this),
								sizeRefHeight = this.getBoundingClientRect().height,
								sizeRefMarginTop = parseFloat(computedStyle.marginTop),
								sizeRefMarginBottom = parseFloat(computedStyle.marginBottom);
							sizeRefOuterHeight = sizeRefHeight + sizeRefMarginTop + sizeRefMarginBottom;

							innerHeight += sizeRefOuterHeight;
						});
						if (innerHeight > sideBarHeight) {
							sideBarHeight = innerHeight;
						}
						thisSideBarParent.removeClass('sizing');
					});
					sideBarWidth = sideBarWidth === 0 ? 'auto' : sideBarWidth;
					sideBarFormWrapper.width(sideBarWidth);
					sideBarForm.css({
						'height': sideBarHeight,
						'max-height': maxHeight + 'px'
					});
					// console.log('maxHeight', maxHeight);
					// console.log('sideBarForm', sideBarForm);
					// console.log('sideBarForm.css("max-height")', sideBarForm.css("max-height"));
					finishWork(this);
				}// endif ( formState.length ) {
			});
		}
		function contentAboveOrBelow($tar) {
			// Check if $tar is scrollable first.
			var childHeight = $tar[0].scrollHeight,
					tarHeight = $tar[0].clientHeight;
			if ( childHeight > tarHeight ) {
				var tarScrollTop = $tar.scrollTop(),
					overlayHeight = tarHeight * .15;
				// overlay is not gettable by js so we use the arbitrary number 15%.
				// if ( tarScrollTop <= overlayHeight ) {
				if ( tarScrollTop <= 0 ) {
					$tar.removeClass('contentAbove');
				} else {
					$tar.addClass('contentAbove');
				}
				console.log('tarScrollTop', tarScrollTop);
				console.log('tarHeight', tarHeight);
				console.log('tarScrollTop + tarHeight', tarScrollTop + tarHeight);
				console.log('childHeight', childHeight);
				// if ( tarScrollTop + tarHeight > childHeight - overlayHeight ) {
				if ( tarScrollTop + tarHeight >= childHeight ) {
					$tar.removeClass('contentBelow');
				} else {
					$tar.addClass('contentBelow');
				}
			}
		}
		function contentAboveOrBelowFormStateContent() {
			$('.formState-content').on('scroll', function(){
				console.log('im scrollin');
				contentAboveOrBelow($(this));
			}).trigger('scroll');
		}
		function scrollSpySideBar() {
			rightSidebar.each(function () {
				// See if Sidebar is actually on side.
				var isSideBar = getComputedStyle(this).flexBasis;
				if (isSideBar !== 'auto') {
					secondary.each(function () {
						// offset().top is position of element relative to docuement.
						var top = $(this).offset().top,
							height = this.getBoundingClientRect().height,
							bottom = top + height,
							ref = $('.main-container').first();
						// If bottomOut look for top.
						if (bottomOut) {
							var rect = this.getBoundingClientRect(),
								rectTop = rect.top,
								refOffsetTop = ref.offset().top;
							if (rectTop <= refOffsetTop) {
								body.addClass('scrollSpyBottomOut');
								bottomOut = true;
							} else {
								body.removeClass('scrollSpyBottomOut');
								bottomOut = false;
							}
						} else {
							// position().top is position of element relative to viewport.
							var refTop = ref.position().top,
								refComputedStyle = getComputedStyle(ref[0]),
								refHeight = parseFloat(refComputedStyle.paddingTop) + parseFloat(refComputedStyle.paddingBottom) + parseFloat(refComputedStyle.height),
								refBottom = refTop + refHeight;
							if (bottom >= refBottom) {
								body.addClass('scrollSpyBottomOut');
								bottomOut = true;
							} else {
								body.removeClass('scrollSpyBottomOut');
								bottomOut = false;
							}
						}
					});
				} else {
					body.removeClass('scrollSpyBottomOut');
					bottomOut = false;
				}
			});
		}
		function triggerScrollSpySideBar() {
			$('.archive-wrapper .collapse').on('shown.bs.collapse hidden.bs.collapse', function () {
				scrollSpySideBar();
			});
		}
		function preventExpandedCollapse() {
			$('.preventExpandedCollapse[data-toggle="collapse"]').on('click', function (e) {
				if ($(this).attr('aria-expanded') === 'true') {
					e.stopImmediatePropagation();
					e.preventDefault();
				}
			});
		}
		function formStateProgress() {
			function updateFormStateProgress($activeBtn, $progress, $parent) {
				var $sideBarModal = $activeBtn.closest('#sidebar-modal');
				if ($sideBarModal.length) {
					$sideBarModal.addClass('sizing');
				}
				var $activeBtnRightEdge = $activeBtn[0].offsetLeft + $activeBtn[0].getBoundingClientRect().width,
					$parentWidth = $parent[0].getBoundingClientRect().width,
					scaleX = $activeBtnRightEdge / $parentWidth;
				$progress.css({
					'-webkit-transform': 'scaleX(' + scaleX + ') translateZ(0)',
					'transform': 'scaleX(' + scaleX + ') translateZ(0)'
				});
				$sideBarModal.removeClass('sizing');
			}
			$('.formState-nav-progress').each(function () {
				var $parent = $(this).parent(),
					$progress = $(this),
					$btns = $(this).siblings('.btn-formState-nav'),
					$activeBtn = $btns.filter('[aria-expanded="true"]');
				updateFormStateProgress($activeBtn, $progress, $parent);
				$btns.each(function () {
					var targetFormState = $(this).attr('data-target');
					$(targetFormState).on('show.bs.collapse', function () {
						var thisId = $(this).attr('id'),
							$thisActiveBtn = $btns.filter('[data-target="#' + thisId + '"]');
						updateFormStateProgress($thisActiveBtn, $progress, $parent);
					});
				});
			});
		}
		function invalidFormState() {
			function checkForFalse(value) {
				return value === false;
			}
			function checkForTrue(value) {
				return value === true;
			}
			var $formStateParent = $('.formStateParent');
			$formStateParent.each(function () {
				var $thisFormStateParent = $(this),
					$submit = $(this).find('input[type="submit"]');
				$submit.on('click', function () {
					var $formStates = $thisFormStateParent[0]['formStates'],
						formStateValidity = [],
						$firstInvalidFormState;
					$formStates.each(function (index) {
						var $inputs = $(this).find('input, select, textarea'),
							inputValidity = [],
							formStateId = $(this).attr('id'),
							$btn = $thisFormStateParent.find('.btn-formState-nav[data-target="#' + formStateId + '"]');

						$inputs.each(function (index) {
							var thisValidity,
								$inputElem = $(this);
							// Special condition for exclusive checkboxes
							if ($(this).is('[type="checkbox"]')) {
								var $exclusiveCheckBox = $(this).closest('.wpcf7-exclusive-checkbox');
								if ($exclusiveCheckBox.length) {
									var $checkedBox = $exclusiveCheckBox.find(':checked');
									thisValidity = $checkedBox.length ? true : false;
								} else {
									thisValidity = this.checkValidity();
								}
							} else {
								thisValidity = this.checkValidity();
							}
							inputValidity[index] = thisValidity;
							if (!thisValidity) {
								$inputElem.addClass('input-invalid');
							} else {
								$inputElem.removeClass('input-invalid');
							}
						});

						// Check to make sure that ALL are TRUE validations.
						var someFalseInputValidities = inputValidity.some(checkForFalse);
						if (someFalseInputValidities) {
							$btn.addClass('contains-invalid-input');
							if ($firstInvalidFormState === undefined) {
								$firstInvalidFormState = $(this);
							}
						} else {
							// Else remove invalid class to associated btn.
							$btn.removeClass('contains-invalid-input');
						}
						formStateValidity[index] = someFalseInputValidities;
					});
					var allTrueFormStateValidities = formStateValidity.some(checkForTrue);
					// Check to make sure that ALL are TRUE validations on ALL FormStates.
					if (allTrueFormStateValidities) {
						$thisFormStateParent.addClass('contains-invalid-input');
						$firstInvalidFormState.collapse('show');
					} else {
						// Else remove invalid class to associated btn.
						$thisFormStateParent.removeClass('contains-invalid-input');
					}
				});
			});
		}
		function triggerClickOnClick() {
			$('.triggerClickOnClick').on('click', function () {
				var clickTarget = $(this).attr('data-click-target'),
					parent = $(this).closest('.triggerClickOnClickParent');
				$(clickTarget, parent).click();
			});
		}
		function displayFileNamesOfInput() {
			$('.displayFileNamesOfInput').each(function () {
				var $displayFileNamesOfInput = $(this),
					$parent = $(this).closest('.displayFileNamesOfInputParent'),
					$input = $parent.find('input[type="file"]');
				$input.on('change', function () {
					var files = this.files,
						textOutPut = '';
					if (files.length) {
						if (files.length > 1) {
							textOutPut = files.length + ' files selected.';
						} else {
							textOutPut = files[0].name;
						}
						$displayFileNamesOfInput.show().text(textOutPut);
					} else {
						$displayFileNamesOfInput.hide();
					}
				});
			});
		}
		function sizeCards() {
			$('.cards-wrapper').each(function(){
				var tallest = 0,
				$cards = $(this).children();
				$cards.each(function(){
					var $inner = $(this).children().first(),
						$clone = $inner.clone();
					$clone.css({
						'visibility':'hidden',
						'postition':'absolute',
						'height':'auto'
					}).
					addClass('clone').
					prependTo($(this));
					var cloneHeight = $clone.outerHeight();
					tallest = cloneHeight > tallest ? cloneHeight : tallest;
					// if (cloneHeight)
					$clone.remove();
				});
				$cards.height(tallest);
			});
		}
		function readyFuncs() {
			renameSideBarModalIds();
			interceptHashChange();
			triggerGideonChatBot();
			contactUsClick();
			checkForQualify();
			specifyOther();
			addHTMLValidationToCF();
			onEnterTriggerClick();
			formStateNav();
			sizeSideBar();
			scrollSpySideBar();
			triggerScrollSpySideBar();
			preventExpandedCollapse();
			formStateProgress();
			invalidFormState();
			triggerClickOnClick();
			displayFileNamesOfInput();
			// contentAboveOrBelowFormStateContent();
			sizeCards();
		}
		readyFuncs();
		function resizeFuncs() {
			sizeSideBar();
			sizeCards();
		}
		$(window).on('resize', function () {
			resizeFuncs();
		});
		function scrollFuncs() {
			scrollSpySideBar();
		}
		$(window).on('scroll', function () {
			scrollFuncs();
		});
	});
})(jQuery);
