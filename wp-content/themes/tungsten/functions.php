<?php
/**
 * Kanary functions and definitions
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
	$content_width = 1000; /* pixels */
}

if (!function_exists('kanary_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function kanary_setup()
	{

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Kanary, use a find and replace
		 * to change 'kanary' to the name of your theme in all the template files
		 */
		load_theme_textdomain(kanary_get_theme_slug(), get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'primary' => __('Primary Menu', 'tungsten')
		));

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support('html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
		));

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support('post-formats', array(
			'aside', 'image', 'video', 'quote', 'link',
		));
	}
endif; // kanary_setup
add_action('after_setup_theme', 'kanary_setup');

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function kanary_widgets_init()
{
	register_sidebar(array(
		'name'          => __('Sidebar', 'tungsten'),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'name'          => __('Footer 1', 'tungsten'),
		'id'            => 'footer-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="footer-widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'name'          => __('Footer 2', 'tungsten'),
		'id'            => 'footer-2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="footer-widget-title">',
		'after_title'   => '</h3>',
	));

	register_sidebar(array(
		'name'          => __('Footer 3', 'tungsten'),
		'id'            => 'footer-3',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="footer-widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="footer-widget-title">',
		'after_title'   => '</h3>',
	));
}

add_action('widgets_init', 'kanary_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function kanary_scripts()
{
	wp_enqueue_style('kanary-style', get_template_directory_uri() . '/style.css');

	wp_enqueue_style("kanary-fontawesome", get_template_directory_uri() . "/assets/libs/font-awesome/css/font-awesome.min.css", null, "4.3.0", "all");

	wp_register_script("kanary-owl-carousel", get_template_directory_uri() . '/assets/libs/owlcarousel/owl-carousel/owl.carousel.min.js', array('jquery'), '1.3.2', true);

	wp_enqueue_script("kanary-script", get_template_directory_uri() . '/assets/js/script.js', array('jquery'), '1', true);

	global $theme_settings;
	wp_localize_script("kanary-script", 'theme_settings', $theme_settings);

	wp_enqueue_script('kanary-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20130115', true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	if ($theme_settings['general']['responsive_tables']) {
		wp_enqueue_script("kanary-stackable-js", get_template_directory_uri() . '/assets/libs/stacktable.js/stacktable.js', array('jquery'), '1.0.1', true);

		wp_enqueue_style("kanary-stackable-css", get_template_directory_uri() . "/assets/libs/stacktable.js/stacktable.css", null, "1.0.1", "all");
	}
}

add_action('wp_enqueue_scripts', 'kanary_scripts');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load theme options panel.
 */
require get_template_directory() . '/inc/options-panel.php';

/**
 * Load custom walker class for the mobile menu.
 */
require get_template_directory() . '/inc/nav-walker.php';

/**
 * Load custom walker class for the mobile menu fallback.
 */
require get_template_directory() . '/inc/page-walker.php';

/**
 * Overrides for the derived themes.
 */
require kanary_get_theme_overrides_path();