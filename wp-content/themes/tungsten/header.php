<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php kanary_social_links(); ?>

<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e('Skip to content', 'tungsten'); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="header-inner">
			<div class="site-branding">
				<?php
				global $theme_settings;
				if ($theme_settings['general']['logo']):
					?>
					<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
						<img alt="<?php bloginfo('name'); ?>" class="site-logo"
							 src="<?php echo esc_url($theme_settings['general']['logo']); ?>"/>
					</a>
				<?php else: ?>
					<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
											  rel="home"><?php bloginfo('name'); ?></a></h1>
					<p class="site-description"><?php bloginfo('description'); ?></p>
				<?php endif; ?>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation" role="navigation">
				<div class="site-nav-inner nav-inner">
					<?php wp_nav_menu(array('theme_location' => 'primary')); ?>
				</div>
			</nav><!-- #site-navigation -->

			<nav id="mobile-navigation" class="mobile-navigation">
				<div class="mobile-nav-inner nav-inner">
					<?php
					$menu_label = __('Menu', 'tungsten');
					// TODO: Use a styleable dropdown.
					?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'fallback_cb'    => 'kanary_dropdown_pages',
							'items_wrap'     => kanary_dropdown_nav_wrapper(),
							'walker'         => new Kanary_Walker_Nav_Menu_Dropdown()
						)
					);
					?>
				</div>
			</nav>
		</div>
		<?php do_action('kanary_before_header_end'); ?>
	</header><!-- #masthead -->

	<?php do_action('kanary_between_header_slider'); ?>

	<?php get_template_part('slider', 'home'); ?>

	<div id="content" class="site-content">
		<div class="content-inner clear">