jQuery(document).ready(function ($) {

	$('.wp-customizer').on('click', '.media-items-clear', function (e) {
		e.preventDefault();
		$container = $(this).closest('.media-items-control');
		$selected_media = $('.selected-media-items', $container);
		$ids_input = $('input[type="hidden"]', $container);
		$ids_input.val('');
		$ids_input.change();
		$selected_media.html('');
	});

	$('.wp-customizer').on('click', '.media-items-select', function (e) {
		e.preventDefault();
		$container = $(this).closest('.media-items-control');
		$selected_media = $('.selected-media-items', $container);
		$ids_input = $('input[type="hidden"]', $container);

		var media_items_uploader = wp.media.frames.file_frame = wp.media({
			multiple: true
		});

		media_items_uploader.on('open', function () {
			if ($ids_input.val() == '')
				return;

			var selection = media_items_uploader.state().get('selection');
			ids = $ids_input.val().split(',');
			ids.forEach(function (id) {
				attachment = wp.media.attachment(id);
				selection.add(attachment ? [attachment] : []);
			});
		});

		media_items_uploader.on('select', function () {
			var media_objects = media_items_uploader.state().get('selection').toJSON();
			$selected_media.html('');

			media_objects.forEach(function (item) {
				if (typeof item.sizes.thumbnail != 'undefined')
					item = item.sizes.thumbnail;

				$('<img>')
					.attr('src', item.url)
					.attr('alt', item.caption)
					.attr('title', item.title)
					.appendTo($selected_media);
			});

			var media_ids = [];
			for (var i = 0; i < media_objects.length; i++) {
				media_ids.push(media_objects[i].id);
			}
			;
			var final_val = media_ids.join(',');
			$ids_input.val(final_val);
			$ids_input.change();
		});
		media_items_uploader.open();
	});

});