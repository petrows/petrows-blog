<?php
/*
	Template Name: Catlisted page
 */

get_header(); ?>

<div id="primary" class="content-area">
<main id="main" class="site-main" role="main">

<?php while ( have_posts() ) : the_post(); ?>

<?php get_template_part( 'content', 'page' ); ?>

<?php
	# Now print the page index
	global $post;
	
	// Set up the objects needed
	$cat_name = $post->post_name;
	$page_posts = get_posts(array('category_name'=>$cat_name, 'posts_per_page'=>15));
?>

<?php if ($page_posts) { ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<header class="entry-header">
	<h1 class="entry-title"><?php echo __("Recent Posts"); ?></h1>
</header>

<div class="entry-content">
<ul class="pws_catlist">
<?php
foreach ($page_posts as $p)
{
	echo '<li><a href="'.get_post_permalink($p->ID).'">'.$p->post_title.'</a></li>';
}
?>
</ul>
<br/>
<p><a href="<?php echo get_category_link(get_category_by_slug($cat_name)); ?>"><?php echo __("All Posts"); ?></a></p>
</div><!-- .entry-content -->	
</article>

<?php } // end if ($page_posts) ?>

<?php endwhile; // end of the loop. ?>

</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
