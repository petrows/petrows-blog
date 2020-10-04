<?php

function tungsten_font_style()
{
	global $theme_settings;

	if (!$theme_settings['fonts']['font_family']['value']) {
		// We need to enqueue a font that will be used throughout the site. If the user has already chosen one that can replace this then there is no need to enqueue it.
		wp_enqueue_style("open-sans-font", "http://fonts.googleapis.com/css?family=Open+Sans:400,300", null, "1.0", "all");
	}
}

add_action('wp_enqueue_scripts', 'tungsten_font_style');

function tungsten_remove_skin_from_printed_styles($styles)
{
	unset($styles['skin']);

	return $styles;
}

add_filter('kanary_customization_css_styles', 'tungsten_remove_skin_from_printed_styles');

function tungsten_skin_body_class($classes)
{
	global $theme_settings;

	// Add the skin class.
	if ($theme_settings['styling']['skin']) {
		$classes[] = sanitize_html_class($theme_settings['styling']['skin']);
	}

	return $classes;
}

add_filter('body_class', 'tungsten_skin_body_class');

function tungsten_add_skin_option($fields)
{
	$skins = array(
		''     => 'Red',
		'blue' => 'Blue'
	);

	$fields['styling'] = array(
		'skin' => array(
			'label'   => __('Skin', 'tungsten'),
			'type'    => 'select',
			'choices' => $skins
		)
	);

	return $fields;
}

add_filter('kanary_setting_fields', 'tungsten_add_skin_option');

function tungsten_change_customizer_titles($translated_text, $text)
{
	if ($translated_text != $text || strpos($text, 'Kanary') === false) {
		return $translated_text;
	}

	return str_replace('Kanary', 'Tungsten', $text);
}

add_filter('gettext', 'tungsten_change_customizer_titles', 20, 2);

function tungsten_update_credit_theme_urls($theme_urls)
{
	return str_replace('kanary', 'tungsten-theme', $theme_urls);
}

add_filter('kanary_credit_theme_urls', 'tungsten_update_credit_theme_urls');

function tungsten_change_option_id()
{
	return 'tungsten_theme_options';
}

add_filter('kanary_option_id', 'tungsten_change_option_id');

function tungsten_support_custom_background()
{
	add_theme_support('custom-background');
}

add_filter('after_setup_theme', 'tungsten_support_custom_background');