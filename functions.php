<?php

/**
 * bilskydd-encoder-it functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package bilskydd-encoder-it
 */

if (! defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bilskydd_encoder_it_setup()
{
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on bilskydd-encoder-it, use a find and replace
		* to change 'bilskydd-encoder-it' to the name of your theme in all the template files.
		*/
	load_theme_textdomain('bilskydd-encoder-it', get_template_directory() . '/languages');

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
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__('Primary', 'bilskydd-encoder-it'),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'bilskydd_encoder_it_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action('after_setup_theme', 'bilskydd_encoder_it_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function bilskydd_encoder_it_content_width()
{
	$GLOBALS['content_width'] = apply_filters('bilskydd_encoder_it_content_width', 640);
}
add_action('after_setup_theme', 'bilskydd_encoder_it_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function bilskydd_encoder_it_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'bilskydd-encoder-it'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'bilskydd-encoder-it'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'bilskydd_encoder_it_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function bilskydd_encoder_it_scripts()
{
	wp_enqueue_style('bilskydd-encoder-it-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('bilskydd-encoder-it-style', 'rtl', 'replace');

	wp_enqueue_script('bilskydd-encoder-it-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'bilskydd_encoder_it_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if (class_exists('WooCommerce')) {
	require get_template_directory() . '/inc/woocommerce.php';
}


	
/* Encoder custom code start here */
//hide admin bar
function show_admin_bar_forcefully()
{
	$is_admin_show = (defined('WP_ADMIN_SHOW') && WP_ADMIN_SHOW === true)
		? true
		: false;
	return $is_admin_show;
}
add_filter('show_admin_bar', 'show_admin_bar_forcefully', 999999);

$directories = [
	'/admin/classes',
	'/frontend/classes'
];
foreach ($directories as $dir) {
	// Use glob to search for PHP files recursively in each subdirectory
	foreach (glob(get_template_directory() . $dir . '/*.php') as $file) {
		require_once $file;
	}
}

require get_template_directory() . '/hooks.php';
require get_template_directory() . '/woocommerce-hooks.php';
require get_template_directory() . '/ajax-hooks.php';
require get_template_directory() . '/helpers.php';
require get_template_directory() . '/webp_helpers.php';
require get_template_directory() . '/cron-hooks.php';
require get_template_directory() . '/preloader.php';
require get_template_directory() . '/unknown-param-block.php';
