jQuery(document).ready(function ($) {

	$('.kanary-slider').each(function () {
		var fixed_options = {
				singleItem: true,
				navigationText: ['<i class="fa fa-angle-double-left"></i>', '<i class="fa fa-angle-double-right"></i>']
			},
			slider_options = theme_settings.slider,
			user_options = {
				autoPlay: Boolean(slider_options.autoPlay),
				pagination: Boolean(slider_options.pagination),
				stopOnHover: Boolean(slider_options.stopOnHover),
				navigation: Boolean(slider_options.navigation),
				transitionStyle: ( slider_options.transitionStyle === '' ) ? false : slider_options.transitionStyle
			};

		$(this).owlCarousel($.extend(fixed_options, user_options));
	});

	$('#drop-nav').change(function () {
		window.location.href = $(this).val();
	});

	if (theme_settings.general.responsive_tables) {
		$('table').stacktable();
	}
});