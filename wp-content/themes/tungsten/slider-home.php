<?php
global $theme_settings;
$attachment_ids = $theme_settings['slider']['slides'];

if (!is_front_page() || (bool)$theme_settings['slider']['disable_slider'] || !$attachment_ids)
	return;

wp_enqueue_script("kanary-owl-carousel");

$attachment_ids = explode(',', $attachment_ids);
$attachment_ids = array_map('trim', $attachment_ids);
$attachment_ids = array_map('intval', $attachment_ids);

$args = array(
	'posts_per_page' => -1,
	'post_type'      => 'attachment',
	'post__in'       => $attachment_ids
);

$slider_images = get_posts($args);
?>
<div class="home-slider kanary-slider">
	<?php foreach ($slider_images as $slider_image): ?>
		<div class="home-slide-container">
			<img class="home-slide" src="<?php echo $slider_image->guid; ?>"/>
			<?php if ($slider_image->post_excerpt): ?>
				<p class="slide-caption"><?php echo $slider_image->post_excerpt; ?></p>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>