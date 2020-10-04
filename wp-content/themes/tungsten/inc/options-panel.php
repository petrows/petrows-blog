<?php

function kanary_options_panel_init()
{
	$options_panel = new Kanary_Options();
	global $theme_settings;

	$theme_settings = $options_panel->get_options();
}

add_action('init', 'kanary_options_panel_init', 0);
add_action('customize_preview_init', 'kanary_options_panel_init');

require_once 'options-framework.php';

/**
 * Returns an array of system fonts
 * Feel free to edit this, update the font fallbacks, etc.
 */
function kanary_supported_os_fonts()
{
	// OS Font Defaults
	$os_faces = array(
		'Arial, sans-serif'                                       => 'Arial',
		"'Avant Garde', sans-serif"                               => 'Avant Garde',
		'Cambria, Georgia, serif'                                 => 'Cambria',
		'Copse, sans-serif'                                       => 'Copse',
		"Garamond, 'Hoefler Text', Times New Roman, Times, serif" => 'Garamond',
		'Georgia, serif'                                          => 'Georgia',
		"'Helvetica Neue', Helvetica, sans-serif"                 => 'Helvetica Neue',
		'Tahoma, Geneva, sans-serif'                              => 'Tahoma'
	);

	return $os_faces;
}

/**
 * Returns a select list of Google fonts
 * Feel free to edit this, update the fallbacks, etc.
 */

function kanary_supported_google_fonts()
{
	// Google Font Defaults
	$google_faces = array(
		'Arvo, serif'                     => 'Arvo (Google)',
		'Copse, sans-serif'               => 'Copse (Google)',
		'Droid Sans, sans-serif'          => 'Droid Sans (Google)',
		'Droid Serif, serif'              => 'Droid Serif (Google)',
		'Lobster, cursive'                => 'Lobster (Google)',
		'Nobile, sans-serif'              => 'Nobile (Google)',
		'Open Sans, sans-serif'           => 'Open Sans (Google)',
		'Oswald, sans-serif'              => 'Oswald (Google)',
		'Pacifico, cursive'               => 'Pacifico (Google)',
		'Rokkitt, serif'                  => 'Rokkit (Google)',
		'PT Sans, sans-serif'             => 'PT Sans (Google)',
		'Quattrocento, serif'             => 'Quattrocento (Google)',
		'Raleway, cursive'                => 'Raleway (Google)',
		'Ubuntu, sans-serif'              => 'Ubuntu (Google)',
		'Yanone Kaffeesatz, sans-serif'   => 'Yanone Kaffeesatz (Google)',
		'Bree Serif, serif'               => 'Bree Serif (Google)',
		'Poiret One, cursive'             => 'Poiret One (Google)',
		'PT Serif, serif'                 => 'PT Serif (Google)',
		'Titillium Web, sans-serif'       => 'Titillium Web (Google)',
		'Merriweather, serif'             => 'Merriweather (Google)',
		'Roboto, sans-serif'              => 'Roboto (Google)',
		'Roboto Slab, serif'              => 'Roboto Slab (Google)',
		'Open Sans Condensed, sans-serif' => 'Open Sans Condensed (Google)'
	);
	return $google_faces;
}

class Kanary_Options extends Kanary_Options_Framework
{

	function __construct()
	{
		parent::__construct($this->get_option_id());
	}

	/**
	 * @return mixed|string|void
	 */
	public static function get_option_id()
	{
		$option_id = "kanary_theme_options";
		$option_id = apply_filters('kanary_option_id', $option_id);
		return $option_id;
	}

	protected function get_setting_sections()
	{
		$settings_sections = array(
			'general' => __('Kanary General', 'tungsten'),
			'home'    => __('Kanary Homepage', 'tungsten'),
			'slider'  => __('Kanary Slider', 'tungsten'),
			'layouts' => __('Kanary Layout', 'tungsten'),
			'styling' => __('Kanary Styling', 'tungsten'),
			'fonts'   => __('Kanary Fonts', 'tungsten'),
			'social'  => __('Kanary Social', 'tungsten'),
			'footer'  => __('Kanary Footer', 'tungsten')
		);

		return apply_filters('kanary_setting_sections', $settings_sections);
	}

	protected function get_setting_fields()
	{

		$fonts = array_merge(kanary_supported_os_fonts(), kanary_supported_google_fonts());
		asort($fonts);
		$fonts = array_merge(array('' => 'Inherit'), $fonts);

		$layouts = array(
			''                => __('Inherit', 'tungsten'),
			'content-sidebar' => __('Right Sidebar', 'tungsten'),
			'sidebar-content' => __('Left Sidebar', 'tungsten'),
			'fullwidth'       => __('Fullwidth', 'tungsten')
		);

		$setting_fields = array(
			'general' => array(
				'custom_favicon' => array(
					'label' => __('Favicon', 'tungsten'),
					'type'  => 'media_url'
				),

				'logo' => array(
					'label' => __('Logo URL', 'tungsten'),
					'type'  => 'media_url'
				),

				'responsive_tables' => array(
					'label'   => __('Make HTML tables responsive', 'tungsten'),
					'type'    => 'checkbox',
					'default' => false
				)
			),

			'layouts' => array(
				'sidebar_width' => array(
					'label'   => __('Sidebar Width', 'tungsten'),
					'type'    => 'select',
					'choices' => array(
						''            => '250px',
						'sidebar-300' => '300px',
						'sidebar-350' => '350px',
						'sidebar-400' => '400px'
					)
				),

				'general' => array(
					'label'   => __('Layout', 'tungsten'),
					'type'    => 'select',
					'choices' => $layouts
				),

				'single' => array(
					'label'   => __('Single Layout', 'tungsten'),
					'type'    => 'select',
					'choices' => $layouts
				),

				'single_post' => array(
					'label'   => __('Single Post Layout', 'tungsten'),
					'type'    => 'select',
					'choices' => $layouts
				),

				'single_page' => array(
					'label'   => __('Single Page Layout', 'tungsten'),
					'type'    => 'select',
					'choices' => $layouts
				),

				'archive' => array(
					'label'   => __('Archive Layout', 'tungsten'),
					'type'    => 'select',
					'choices' => $layouts
				),

				'archive_category' => array(
					'label'   => __('Category Archive Layout', 'tungsten'),
					'type'    => 'select',
					'choices' => $layouts
				),

				'archive_tag' => array(
					'label'   => __('Tag Archive Layout', 'tungsten'),
					'type'    => 'select',
					'choices' => $layouts
				)
			),

			'home' => array(
				'show_excerpts' => array(
					'label' => __('Show Excerpts', 'tungsten'),
					'type'  => 'checkbox'
				),
			),

			'slider' => array(
				'disable_slider' => array(
					'label' => __('Disable slider?', 'tungsten'),
					'type'  => 'checkbox'
				),

				'slides' => array(
					'label' => __('Slider Images', 'tungsten'),
					'type'  => 'media_items'
				),

				'transitionStyle' => array(
					'label'   => __('Transition Style', 'tungsten'),
					'type'    => 'select',
					'choices' => array('' => __('Slide', 'tungsten'), 'fade' => __('Fade', 'tungsten'), 'backSlide' => __('Back Slide', 'tungsten'), 'goDown' => __('Go Down', 'tungsten'), 'fadeUp' => __('Fade Up', 'tungsten')),
				),

				'pagination' => array(
					'label'   => __('Display Pagination', 'tungsten'),
					'type'    => 'checkbox',
					'default' => true
				),

				'autoPlay' => array(
					'label'   => __('Auto Play', 'tungsten'),
					'type'    => 'checkbox',
					'default' => false
				),

				'stopOnHover' => array(
					'label'   => __('Stop On Hover', 'tungsten'),
					'type'    => 'checkbox',
					'default' => false
				),

				'navigation' => array(
					'label'   => __('Display Navigation', 'tungsten'),
					'type'    => 'checkbox',
					'default' => false
				)
			),

			'styling' => array(
				'bg_color' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => 'body{background-color:%s;}',
							'customizer' => false
						),
						'value'  => array(
							'label' => __('Background Color', 'tungsten'),
							'type'  => 'color'
						)
					)
				),

				'bg_img' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => 'body{background-image:url(%s);}',
							'customizer' => false
						),
						'value'  => array(
							'label' => __('Background Image', 'tungsten'),
							'type'  => 'media_url'
						)
					)
				),

				'bg_repeat' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => 'body{background-repeat:%s;}',
							'customizer' => false
						),
						'value'  => array(
							'label'   => __('Background Repeat', 'tungsten'),
							'type'    => 'select',
							'choices' => array('repeat' => __('Horizontally & vertically', 'tungsten'), 'repeat-x' => __('Horizontally', 'tungsten'), 'repeat-y' => __('Vertically', 'tungsten'))
						)
					)
				),

				'a_color' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => 'a{color:%s;}',
							'customizer' => false
						),
						'value'  => array(
							'label' => __('Link Color', 'tungsten'),
							'type'  => 'color'
						)
					)
				),

				'a_visited_color' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => 'a:visited{color:%s;}',
							'customizer' => false
						),
						'value'  => array(
							'label' => __('Link Visited Color', 'tungsten'),
							'type'  => 'color'
						)
					)
				),

				'a_hover_color' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => 'a:hover{color:%s;}',
							'customizer' => false
						),
						'value'  => array(
							'label' => __('Link Hover Color', 'tungsten'),
							'type'  => 'color'
						)
					)
				),

				'entry_title_a_color' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => '.entry-title a{color:%s;}',
							'customizer' => false
						),
						'value'  => array(
							'label' => __('Entry Title Link Color', 'tungsten'),
							'type'  => 'color'
						)
					)
				),

				'entry_title_a_visited_color' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => '.entry-title a:visited{color:%s;}',
							'customizer' => false
						),
						'value'  => array(
							'label' => __('Entry Title Link Visited Color', 'tungsten'),
							'type'  => 'color'
						)
					)
				),

				'entry_title_a_hover_color' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => '.entry-title a:hover{color:%s;}',
							'customizer' => false
						),
						'value'  => array(
							'label' => __('Entry Title Link Hover Color', 'tungsten'),
							'type'  => 'color'
						)
					)
				)

			),

			'fonts' => array(
				'font_size' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => 'body, button, input, select, textarea{font-size:%spx;}',
							'customizer' => false
						),
						'value'  => array(
							'label'   => __('Font Size', 'tungsten'),
							'type'    => 'number',
							'default' => 16
						)
					)
				),

				'font_family' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => 'body, button, input, select, textarea{font-family:%s;}',
							'customizer' => false
						),
						'value'  => array(
							'label'   => __('Basic Font', 'tungsten'),
							'type'    => 'select',
							'choices' => $fonts
						)
					)
				),

				'headings_font_family' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => 'h1,h2,h3,h4,h5,h6{font-family:%s;}',
							'customizer' => false
						),
						'value'  => array(
							'label'   => __('Headings Font', 'tungsten'),
							'type'    => 'select',
							'choices' => $fonts
						)
					)
				),

				'site_title_font_family' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => '.site-title{font-family:%s;}',
							'customizer' => false
						),
						'value'  => array(
							'label'   => __('Site Title Font', 'tungsten'),
							'type'    => 'select',
							'choices' => $fonts
						)
					)
				),

				'post_title_font_family' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => '.entry-title{font-family:%s;}',
							'customizer' => false
						),
						'value'  => array(
							'label'   => __('Post Title Font', 'tungsten'),
							'type'    => 'select',
							'choices' => $fonts
						)
					)
				),

				'widget_title_font_family' => array(
					'type'  => 'nested',
					'items' => array(
						'format' => array(
							'type'       => 'hidden',
							'default'    => '.widget-title{font-family:%s;}',
							'customizer' => false
						),
						'value'  => array(
							'label'   => __('Widget Title Font', 'tungsten'),
							'type'    => 'select',
							'choices' => $fonts
						)
					)
				),
			),

			'social' => array(
				'twitter' => array(
					'label' => __('Twitter URL', 'tungsten'),
					'type'  => 'url'
				),

				'fb' => array(
					'label' => __('Facebook URL', 'tungsten'),
					'type'  => 'url'
				),

				'google_plus' => array(
					'label' => __('Google Plus URL', 'tungsten'),
					'type'  => 'url'
				),

				'instagram' => array(
					'label' => __('Instagram URL', 'tungsten'),
					'type'  => 'url'
				),

				'pinterest' => array(
					'label' => __('Pinterest URL', 'tungsten'),
					'type'  => 'url'
				),

				'youtube' => array(
					'label' => __('Youtube URL', 'tungsten'),
					'type'  => 'url'
				),

				'feedburner' => array(
					'label' => __('Feedburner URL', 'tungsten'),
					'type'  => 'url'
				)
			),

			'footer' => array(
				'columns' => array(
					'label'   => __('Columns', 'tungsten'),
					'type'    => 'select',
					'choices' => array(
						'3' => '3',
						'2' => '2',
						'1' => '1'
					),
					'default' => '3'
				),

				'footer_text' => array(
					'label'   => __('Footer Copyright Text', 'tungsten'),
					'type'    => 'text',
					'default' => '&copy; ' . get_bloginfo('name')
				),

				'hide_credits' => array(
					'label' => __('Hide credit link?', 'tungsten'),
					'type'  => 'checkbox'
				)
			)
		);

		return apply_filters('kanary_setting_fields', $setting_fields);
	}
}