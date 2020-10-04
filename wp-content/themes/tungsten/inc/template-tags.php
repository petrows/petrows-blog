<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced %4$s core features.
 */

if (!function_exists('the_posts_navigation')) :
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 *
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function the_posts_navigation()
	{
		// Don't print empty markup if there's only one page.
		if ($GLOBALS['wp_query']->max_num_pages < 2) {
			return;
		}
		?>
		<nav class="navigation posts-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php _e('Posts navigation', 'tungsten'); ?></h2>
			<div class="nav-links">

				<?php if (get_next_posts_link()) : ?>
					<div class="nav-previous"><?php next_posts_link(__('Older posts', 'tungsten')); ?></div>
				<?php endif; ?>

				<?php if (get_previous_posts_link()) : ?>
					<div class="nav-next"><?php previous_posts_link(__('Newer posts', 'tungsten')); ?></div>
				<?php endif; ?>

			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
endif;

if (!function_exists('the_post_navigation')) :
	/**
	 * Display navigation to next/previous post when applicable.
	 *
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function the_post_navigation()
	{
		// Don't print empty markup if there's nowhere to navigate.
		$previous = (is_attachment()) ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);
		$next = get_adjacent_post(false, '', false);

		if (!$next && !$previous) {
			return;
		}
		?>
		<nav class="navigation post-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php _e('Post navigation', 'tungsten'); ?></h2>
			<div class="nav-links">
				<?php
				previous_post_link('<div class="nav-previous">%link</div>', '%title');
				next_post_link('<div class="nav-next">%link</div>', '%title');
				?>
			</div><!-- .nav-links -->
		</nav><!-- .navigation -->
		<?php
	}
endif;

if (!function_exists('kanary_posted_on')) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function kanary_posted_on()
	{
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if (get_the_time('U') !== get_the_modified_time('U')) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf($time_string,
			esc_attr(get_the_date('c')),
			esc_html(get_the_date()),
			esc_attr(get_the_modified_date('c')),
			esc_html(get_the_modified_date())
		);

		$posted_on = '<i class="fa fa-clock-o"></i> <a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>';

		$byline = '<i class="fa fa-user"></i> <span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>';

		echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';

	}
endif;

if (!function_exists('kanary_entry_footer')) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function kanary_entry_footer()
	{
		// Hide category and tag text for pages.
		if ('post' == get_post_type()) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list(__(', ', 'tungsten'));
			if ($categories_list && kanary_categorized_blog()) {
				echo '<span class="cat-links"><i class="fa fa-folder-open"></i> ' . $categories_list . '</span>';
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list('', __(', ', 'tungsten'));
			if ($tags_list) {
				echo '<span class="tags-links"><i class="fa fa-tags"></i> ' . $tags_list . '</span>';
			}
		}

		if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
			echo '<span class="comments-link"><i class="fa fa-comment"></i> ';
			comments_popup_link(__('Leave a comment', 'tungsten'), __('1 Comment', 'tungsten'), __('% Comments', 'tungsten'));
			echo '</span>';
		}

		edit_post_link(__('Edit', 'tungsten'), '<span class="edit-link"><i class="fa fa-edit"></i> ', '</span>');
	}
endif;

function kanary_archive_title()
{
	if (is_category()) {
		$title = '<i class="fa fa-folder-open"></i> ' . single_cat_title('', false);
	} elseif (is_tag()) {
		$title = '<i class="fa fa-tags"></i> ' . single_tag_title('', false);
	} elseif (is_author()) {
		$title = '<i class="fa fa-user"></i> <span class="vcard">' . get_the_author() . '</span>';
	} elseif (is_year()) {
		$title = '<i class="fa fa-calendar"></i> ' . get_the_date(_x('Y', 'yearly archives date format', 'tungsten'));
	} elseif (is_month()) {
		$title = '<i class="fa fa-calendar"></i> ' . get_the_date(_x('F Y', 'monthly archives date format', 'tungsten'));
	} elseif (is_day()) {
		$title = '<i class="fa fa-calendar"></i> ' . get_the_date(_x('F j, Y', 'daily archives date format', 'tungsten'));
	} elseif (is_tax('post_format')) {
		if (is_tax('post_format', 'post-format-aside')) {
			$title = _x('Asides', 'post format archive title', 'tungsten');
		} elseif (is_tax('post_format', 'post-format-gallery')) {
			$title = _x('Galleries', 'post format archive title', 'tungsten');
		} elseif (is_tax('post_format', 'post-format-image')) {
			$title = _x('Images', 'post format archive title', 'tungsten');
		} elseif (is_tax('post_format', 'post-format-video')) {
			$title = _x('Videos', 'post format archive title', 'tungsten');
		} elseif (is_tax('post_format', 'post-format-quote')) {
			$title = _x('Quotes', 'post format archive title', 'tungsten');
		} elseif (is_tax('post_format', 'post-format-link')) {
			$title = _x('Links', 'post format archive title', 'tungsten');
		} elseif (is_tax('post_format', 'post-format-status')) {
			$title = _x('Statuses', 'post format archive title', 'tungsten');
		} elseif (is_tax('post_format', 'post-format-audio')) {
			$title = _x('Audio', 'post format archive title', 'tungsten');
		} elseif (is_tax('post_format', 'post-format-chat')) {
			$title = _x('Chats', 'post format archive title', 'tungsten');
		}
	} elseif (is_post_type_archive()) {
		$title = sprintf(__('Archives: %s', 'tungsten'), post_type_archive_title('', false));
	} elseif (is_tax()) {
		$tax = get_taxonomy(get_queried_object()->taxonomy);
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf(__('%1$s: %2$s', 'tungsten'), $tax->labels->singular_name, single_term_title('', false));
	} else {
		$title = __('Archives', 'tungsten');
	}

	return $title;
}

add_filter('get_the_archive_title', 'kanary_archive_title');

if (!function_exists('the_archive_title')) :
	/**
	 * Shim for `the_archive_title()`.
	 *
	 * Display the archive title based on the queried object.
	 *
	 * @todo Remove this function when WordPress 4.3 is released.
	 *
	 * @param string $before Optional. Content to prepend to the title. Default empty.
	 * @param string $after Optional. Content to append to the title. Default empty.
	 */
	function the_archive_title($before = '', $after = '')
	{

		$title = kanary_archive_title();

		/**
		 * Filter the archive title.
		 *
		 * @param string $title Archive title to be displayed.
		 */
		$title = apply_filters('get_the_archive_title', $title);

		if (!empty($title)) {
			echo $before . $title . $after;
		}
	}
endif;

if (!function_exists('the_archive_description')) :
	/**
	 * Shim for `the_archive_description()`.
	 *
	 * Display category, tag, or term description.
	 *
	 * @todo Remove this function when WordPress 4.3 is released.
	 *
	 * @param string $before Optional. Content to prepend to the description. Default empty.
	 * @param string $after Optional. Content to append to the description. Default empty.
	 */
	function the_archive_description($before = '', $after = '')
	{
		$description = apply_filters('get_the_archive_description', term_description());

		if (!empty($description)) {
			/**
			 * Filter the archive description.
			 *
			 * @see term_description()
			 *
			 * @param string $description Archive description to be displayed.
			 */
			echo $before . $description . $after;
		}
	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function kanary_categorized_blog()
{
	if (false === ($all_the_cool_cats = get_transient('kanary_categories'))) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories(array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		));

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count($all_the_cool_cats);

		set_transient('kanary_categories', $all_the_cool_cats);
	}

	if ($all_the_cool_cats > 1) {
		// This blog has more than 1 category so kanary_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so kanary_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in kanary_categorized_blog.
 */
function kanary_category_transient_flusher()
{
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient('kanary_categories');
}

add_action('edit_category', 'kanary_category_transient_flusher');
add_action('save_post', 'kanary_category_transient_flusher');

function kanary_available_val($array, $index)
{
	while (!isset($array[ $index ])) {
		$index = intval($index / 2);
	}

	return $array[ $index ];
}

/**
 * Prints the theme credits.
 *
 * @todo Make the texts translation ready.
 **/
function kanary_credits()
{
	global $theme_settings;
	if ((bool)$theme_settings['footer']['hide_credits'])
		return;

	if (is_front_page() || is_home() || is_page()) {
		$formats = array('%1$s %5$s <a href="%3$s">%2$s</a>', '%1$s %5$s <a rel="nofollow" href="%3$s">%2$s</a>', '%1$s %5$s <a title="%2$s" href="%3$s">%2$s</a>', '%1$s %5$s <a title="%2$s" rel="nofollow" href="%3$s">%2$s</a>', '<a href="%3$s">%1$s</a> %5$s %2$s', '<a rel="nofollow" href="%3$s">%1$s</a> %5$s %2$s', '<a href="%4$s">%1$s</a> %5$s %2$s', '<a rel="nofollow" href="%4$s">%1$s</a> %5$s %2$s', '<a title="%1$s" href="%4$s">%1$s</a> %5$s %2$s');
		$anchors = array(__('WP Theme', 'tungsten'), __('WordPress Theme', 'tungsten'), __('Theme', 'tungsten'), __('Design', 'tungsten'), __('Free WordPress Theme', 'tungsten'), __('Free Theme', 'tungsten'), __('Designed', 'tungsten'), __('Developed', 'tungsten'));
		$secondary_anchors = array(__('WPGurus', 'tungsten'), __('WP Gurus', 'tungsten'), __('WordPress Gurus', 'tungsten'));
		$urls = array('http://wpgurus.net/', 'http://www.wpgurus.net/', 'http://wpgurus.com/', 'http://www.wpgurus.com/', 'https://wpgurus.net/', 'https://www.wpgurus.net/');
		$theme_urls = array('http://wpgurus.net/kanary/', 'http://www.wpgurus.net/kanary/', 'http://wpgurus.com/kanary/', 'http://www.wpgurus.com/kanary/', 'https://wpgurus.net/kanary/', 'https://www.wpgurus.net/kanary/');

		$formats = apply_filters('kanary_credit_formats', $formats);
		$anchors = apply_filters('kanary_credit_anchors', $anchors);
		$secondary_anchors = apply_filters('kanary_credit_secondary_anchors', $secondary_anchors);
		$urls = apply_filters('kanary_credit_urls', $urls);
		$theme_urls = apply_filters('kanary_credit_theme_urls', $theme_urls);

		$numbers = array_map('intval', str_split((string)crc32(get_home_url())));

		printf(
			kanary_available_val($formats, $numbers[0]),
			kanary_available_val($anchors, $numbers[1]),
			kanary_available_val($secondary_anchors, $numbers[2]),
			kanary_available_val($urls, $numbers[3]),
			kanary_available_val($theme_urls, $numbers[4]),
			__('by', 'tungsten')
		);
	} else {
		printf(__('Powered by %s', 'tungsten'), sprintf('<a href="http://wordpress.org/"><i class="fa fa-wordpress"></i></a>'));
	}
}

function kanary_social_links()
{
	global $theme_settings;
	?>
	<div class="social_wrap">
		<div class="social">
			<ul>
				<?php if ($theme_settings['social']['fb']): ?>
					<li class="soc_fb">
						<a href="<?php echo esc_url($theme_settings['social']['fb']); ?>" target="_blank"
						   title="Facebook">
							<i class="fa fa-facebook-square"></i>
						</a>
					</li>
				<?php endif; ?>

				<?php if ($theme_settings['social']['twitter']): ?>
					<li class="soc_tw">
						<a href="<?php echo esc_url($theme_settings['social']['twitter']); ?>" target="_blank"
						   title="Twitter">
							<i class="fa fa-twitter-square"></i>
						</a>
					</li>
				<?php endif; ?>

				<?php if ($theme_settings['social']['google_plus']): ?>
					<li class="soc_plus">
						<a href="<?php echo esc_url($theme_settings['social']['google_plus']); ?>" target="_blank"
						   title="Google Plus">
							<i class="fa fa-google-plus-square"></i>
						</a>
					</li>
				<?php endif; ?>

				<?php if ($theme_settings['social']['feedburner']): ?>
					<li class="soc_rss">
						<a href="<?php echo esc_url($theme_settings['social']['feedburner']); ?>" target="_blank"
						   title="RSS">
							<i class="fa fa-rss-square"></i>
						</a>
					</li>
				<?php endif; ?>

				<?php if ($theme_settings['social']['youtube']): ?>
					<li class="soc_yt">
						<a href="<?php echo esc_url($theme_settings['social']['youtube']); ?>" target="_blank"
						   title="RSS">
							<i class="fa fa-youtube"></i>
						</a>
					</li>
				<?php endif; ?>

				<?php if ($theme_settings['social']['pinterest']): ?>
					<li class="soc_pinterest">
						<a href="<?php echo esc_url($theme_settings['social']['pinterest']); ?>" target="_blank"
						   title="RSS">
							<i class="fa fa-pinterest-square"></i>
						</a>
					</li>
				<?php endif; ?>

				<?php if ($theme_settings['social']['instagram']): ?>
					<li class="soc_instagram">
						<a href="<?php echo esc_url($theme_settings['social']['instagram']); ?>" target="_blank"
						   title="RSS">
							<i class="fa fa-instagram"></i>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<?php
}

function kanary_any_footer_widgets_active()
{
	return is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3');
}