(function ($) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	**/

	/* Submenu toggle */
	const FreskaMobileMenuHandler = function ($scope, $) {
		var hasChildren = $scope.find('.menu-item-has-children');

		hasChildren.each(function () {
			var $btnToggle = $('<span class="bt-toggle-icon"></span>');

			$(this).append($btnToggle);

			$btnToggle.on('click', function (e) {
				e.preventDefault();

				if ($(this).parent().hasClass('bt-is-active')) {
					$(this).parent().removeClass('bt-is-active');
					$(this).parent().children('ul').slideUp();
				} else {
					$(this).parent().addClass('bt-is-active');
					$(this).parent().children('ul').slideDown();
					$(this).parent().siblings().removeClass('bt-is-active').children('ul').slideUp();
					$(this).parent().siblings().find('li').removeClass('bt-is-active').children('ul').slideUp();
				}
			});
		});
	}
	

	// Make sure you run this code under Elementor.
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/bt-mobile-menu.default', FreskaMobileMenuHandler);
		
	});

})(jQuery);
