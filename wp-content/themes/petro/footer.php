<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Tungsten
 */
?>
		<div class="clear"></div>
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'tungsten_credits' ); ?>
			<?php global $themename; $footer_options = get_option($themename.'_footer_settings'); ?>
			<p class="copyright-text"><?php echo isset($footer_options['footer_text'])?$footer_options['footer_text']:''; ?></p>
			<p class="credits"><?php tungsten_credit_links($footer_options['hide_credits']); ?></p>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-58341709-1', 'auto');
ga('send', 'pageview');

</script>

<?php wp_footer(); ?>

</body>
</html>