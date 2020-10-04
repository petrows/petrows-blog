<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if (has_post_thumbnail()): ?>
	<div class="entry-thumb">
		<?php the_post_thumbnail(); ?>
	</div>
	<div class="entry-container">
		<?php endif; ?>
		<header class="entry-header">
			<?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

			<?php if ('post' == get_post_type()) : ?>
				<div class="entry-meta">
					<?php kanary_posted_on(); ?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<?php global $theme_settings; ?>
		<div class="<?php echo $theme_settings['home']['show_excerpts'] ? 'entry-summary' : 'entry-content' ?>">
			<?php
			if ((bool)$theme_settings['home']['show_excerpts']) {
				the_excerpt();
			} else {
				/* translators: %s: Name of current post */
				the_content(sprintf(
					__('Continue reading %s <span class="meta-nav">&rarr;</span>', 'tungsten'),
					the_title('<span class="screen-reader-text">"', '"</span>', false)
				));
			}
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
		</footer><!-- .entry-footer -->
		<?php if (has_post_thumbnail()): ?>
	</div>
<?php endif; ?>
</article><!-- #post-## -->