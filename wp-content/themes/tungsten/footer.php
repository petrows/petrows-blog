<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 */
?>
</div><!-- .content-inner -->
</div><!-- #content -->

<footer id="colophon" class="site-footer clearfix" role="contentinfo">
	<div class="footer-inner">
		<?php if (kanary_any_footer_widgets_active()): ?>
			<div class="footer-widgets">
				<div class="footer-widgets-inner">
					<div class="footer-widget-area">
						<?php dynamic_sidebar('footer-1'); ?>
					</div>
					<?php if (kanary_footer_columns() > 1): ?>
						<div class="footer-widget-area">
							<?php dynamic_sidebar('footer-2'); ?>
						</div>
					<?php endif; ?>
					<?php if (kanary_footer_columns() > 2): ?>
						<div class="footer-widget-area">
							<?php dynamic_sidebar('footer-3'); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="clear">
			<div class="site-copyright">
				<?php
				global $theme_settings;
				echo esc_html($theme_settings['footer']['footer_text']);
				?>
			</div>
			<div class="site-info">
				<?php kanary_credits(); ?>
			</div><!-- .site-info -->
		</div>
	</div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>