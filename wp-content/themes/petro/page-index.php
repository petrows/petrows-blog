<?php
/*
	Template Name: Indexed page
 */

function _petro_get_pages($id, $pages)
{
	$out = '';
	$has_child = false;
	// Child pages?
	$child = get_page_children( $id, $pages );
	if (!$child) return;
	
	$out .= '<ul class="pws_catlist">';
	foreach ($child as $p)
	{
		if ($p->post_parent != $id) continue;
		$has_child = true;
		
		$item_title = $p->post_title;
		$item_title = apply_filters('the_title', $item_title);
		$item_child = _petro_get_pages($p->ID, $pages);
		if (!$item_child) $item_title = '<b>'.$item_title.'</b>';
		
		$out .= '<li><a href="'.get_post_permalink($p->ID).'">'.$item_title.'</a></li>';
		$out .= $item_child;
	}	
	$out .= '</ul>';
	if ($has_child) return $out;
	return '';
}

get_header(); ?>

<div id="primary" class="content-area">
<main id="main" class="site-main" role="main">

<?php while ( have_posts() ) : the_post(); ?>

<?php get_template_part( 'content', 'page' ); ?>

<?php
	# Now print the page index 	
	
	// Set up the objects needed
	$my_wp_query = new WP_Query();
	$all_wp_pages = $my_wp_query->query(array('post_type' => 'page', 'order' => 'ASC', 'orderby' => 'menu_order'));
	$child_pages = _petro_get_pages(get_the_ID(), $all_wp_pages);		
?>

<?php if ($child_pages) { ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<header class="entry-header">
	<h1 class="entry-title"><?php echo __("Pages"); ?></h1>
</header>

<div class="entry-content">

<?php
	echo $child_pages;
?>

</div><!-- .entry-content -->	
</article>

<?php } // end if ($child_pages) ?>

<?php endwhile; // end of the loop. ?>

</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
