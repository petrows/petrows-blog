<?php
/**
 * @package Tungsten
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if(has_post_thumbnail()): ?>
		<div class="entry-thumbnail"><?php the_post_thumbnail(); ?></div>
		<div class="entry-text">
	<?php endif; ?>
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php kanary_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->
	<?php global $themename; $home_settings = get_option($themename.'_home_settings');
		if($home_settings['show_excerpts'] || is_search()): ?>
			<div class="entry-summary">
					<?php the_excerpt(); ?>
		<?php else: ?>
			<div class="entry-content">
			<?php
			the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'tungsten' ) );
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'tungsten' ),
					'after'  => '</div>',
				) );
			endif; ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">

	</footer><!-- .entry-meta -->
	<?php if(has_post_thumbnail()): ?>
		</div>
	<?php endif; ?>
</article><!-- #post-## -->
