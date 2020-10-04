<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function kanary_body_classes($classes)
{
	// Adds a class of group-blog to blogs with more than 1 published author.
	if (is_multi_author()) {
		$classes[] = 'group-blog';
	}

	// Add layout class.
	$effective_layout = sanitize_html_class(kanary_get_effective_layout());

	if ($effective_layout)
		$classes[] = $effective_layout;

	global $theme_settings;

	// Add the class specifying the sidebar width.
	$sidebar_class = sanitize_html_class($theme_settings['layouts']['sidebar_width']);
	if ($sidebar_class) {
		$classes[] = $sidebar_class;
	}

	return $classes;
}

add_filter('body_class', 'kanary_body_classes');

/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function kanary_post_classes($classes)
{
	// Clearfix.
	$classes[] = 'clear';
	return $classes;
}

add_filter('post_class', 'kanary_post_classes');

/*
* Adds items to the head section.
*/
function kanary_head()
{
	global $theme_settings;
	?>

	<?php if ($theme_settings['general']['custom_favicon']): ?>
	<link rel="icon" type="image" href="<?php echo esc_url($theme_settings['general']['custom_favicon']); ?>"/>
	<!--[if IE]>
	<link rel="shortcut icon" href="<?php echo esc_url($theme_settings['general']['custom_favicon']); ?>"/><![endif]-->
<?php endif; ?>

	<!--[if lt IE 9]>
	<script type="text/javascript"
			src="<?php echo get_template_directory_uri() . '/assets/libs/html5shiv/dist/html5shiv.min.js'; ?>"></script>
	<script type="text/javascript"
			src="<?php echo get_template_directory_uri() . '/assets/libs/html5shiv/dist/html5shiv-printshiv.min.js'; ?>"></script>
	<script type="text/javascript"
			src="<?php echo get_template_directory_uri() . '/assets/libs/respond/dest/respond.min.js'; ?>"></script>
	<![endif]-->

	<?php
}

add_action('wp_head', 'kanary_head');

if (version_compare($GLOBALS['wp_version'], '4.1', '<')) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function kanary_wp_title($title, $sep)
	{
		if (is_feed()) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo('name', 'display');

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo('description', 'display');
		if ($site_description && (is_home() || is_front_page())) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if (($paged >= 2 || $page >= 2) && !is_404()) {
			$title .= " $sep " . sprintf(__('Page %s', 'tungsten'), max($paged, $page));
		}

		return $title;
	}

	add_filter('wp_title', 'kanary_wp_title', 10, 2);

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function kanary_render_title()
	{
		?>
		<title><?php wp_title('|', true, 'right'); ?></title>
		<?php
	}

	add_action('wp_head', 'kanary_render_title');
endif;

/**
 * Adds CSS for theme customizations.
 */
function kanary_customization_css()
{
	global $theme_settings;
	$styles = apply_filters('kanary_customization_css_styles', array_merge($theme_settings['styling'], $theme_settings['fonts']));
	$css = '';

	foreach ($styles as $item) {
		if ($item['value']) {
			$css .= sprintf($item['format'], $item['value']);
		}
	}

	?>
	<style type='text/css'>
		<?php echo kanary_minify_css( esc_html($css) ); ?>
	</style>
	<?php
}

add_action('wp_head', 'kanary_customization_css', 10);

/**
 * Minifies CSS for better performance.
 *
 * @param string $css Raw CSS.
 * @return string Minified CSS.
 */
function kanary_minify_css($css)
{
	$css = preg_replace(array('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '!\s+!'), ' ', $css);
	$css = str_replace(array(': ', ' :'), ':', $css);
	$css = str_replace(array('; ', ' ;'), ';', $css);
	$css = str_replace(array('{ ', ' {'), '{', $css);
	$css = str_replace(array('} ', ' }'), '}', $css);
	return $css;
}

/*
* Dynamically checks which fonts are required by the theme and enqueues them.
*/
function kanary_enqueue_google_fonts()
{
	global $theme_settings;
	$url = "http://fonts.googleapis.com/css?family=";
	$fonts = array();
	foreach ($theme_settings['fonts'] as $key => $item) {
		$face = $item['value'];

		if (strpos($key, 'font_family') !== false && array_key_exists($item['value'], kanary_supported_google_fonts())) {

			if (strpos($face, ',') !== false) {
				$face = explode(',', $item['value']);
				$face = $face[0];
				$face = str_replace("'", '', $face);
			}

			$face = str_replace(' ', '+', $face);

			if (!in_array($face, $fonts)) {
				$fonts[] = $face;
			}
		}
	}

	if (count($fonts)) {
		wp_enqueue_style('kanary-google-fonts', $url . implode('|', $fonts));
	}
}

add_action('wp_enqueue_scripts', 'kanary_enqueue_google_fonts');

/*
* Finds out which layout should be used for the current page.
*/
function kanary_get_effective_layout()
{
	global $theme_settings;

	$effective_layout = '';

	if ($theme_settings['layouts']['general']) {
		$effective_layout = $theme_settings['layouts']['general'];
	}

	if (is_singular()) {
		if ($theme_settings['layouts']['single'])
			$effective_layout = $theme_settings['layouts']['single'];

		if (is_single() && $theme_settings['layouts']['single_post']) {
			$effective_layout = $theme_settings['layouts']['single_post'];
		}

		if (is_page() && $theme_settings['layouts']['single_page']) {
			$effective_layout = $theme_settings['layouts']['single_page'];
		}

		$post = get_queried_object();
		$post_meta = get_post_meta($post->ID, 'kanary_options', true);

		if (isset($post_meta['layout']) && $post_meta['layout']) {
			$effective_layout = $post_meta['layout'];
		}
	}

	if (is_archive()) {
		if ($theme_settings['layouts']['archive'])
			$effective_layout = $theme_settings['layouts']['archive'];

		if (is_category() && $theme_settings['layouts']['archive_category']) {
			$effective_layout = $theme_settings['layouts']['archive_category'];
		}

		if (is_tag() && $theme_settings['layouts']['archive_tag']) {
			$effective_layout = $theme_settings['layouts']['archive_tag'];
		}
	}

	return ($effective_layout) ? $effective_layout : false;
}

function kanary_dropdown_nav_wrapper()
{
	$option_name = __('Select a page', 'tungsten');
	return '<select id="drop-nav" autocomplete="off"><option value="">' . $option_name . '...</option>%3$s</select>';
}

function kanary_dropdown_pages()
{
	$walker_page = new Kanary_Walker_Page_Dropdown();
	$pages = get_pages(
		array(
			'sort_column' => 'menu_order, post_title'
		)
	);

	$options = $walker_page->walk($pages, 0);
	printf(
		kanary_dropdown_nav_wrapper(),
		null,
		null,
		$options
	);
}

function kanary_get_theme_overrides_path()
{
	return get_template_directory() . '/overrides/' . kanary_get_theme_slug() . '.php';
}

function kanary_get_theme_slug()
{
	/**
	 * @var $theme WP_Theme
	 */
	$theme = wp_get_theme();
	$theme_slug = $theme->get_template() != 'kanary' ? $theme->get_template() : str_replace(' ', '-', strtolower($theme->get('Name')));

	return $theme_slug;
}

function kanary_footer_columns()
{
	global $theme_settings;
	return intval($theme_settings['footer']['columns']);
}

function kanary_move_custom_css_to_wp()
{
	global $theme_settings;

	$theme_custom_css = isset($theme_settings['general']['custom_css']) ? $theme_settings['general']['custom_css'] : '';
	if($theme_custom_css)
	{
		$wp_custom_css = wp_get_custom_css();

		$wp_custom_css .= "\n\n/**\n";
		$wp_custom_css .= "Custom Theme CSS\n";
		$wp_custom_css .= "This CSS was previously saved in the theme settings but now that WP has it's own section for css, it has been moved here.\n";
		$wp_custom_css .= "*/\n\n";

		$wp_custom_css .= $theme_custom_css;

		wp_update_custom_css_post($wp_custom_css);

		unset($theme_settings['general']['custom_css']);
		update_option(Kanary_Options::get_option_id(), $theme_settings);
	}
}
add_action('init', 'kanary_move_custom_css_to_wp', 11);