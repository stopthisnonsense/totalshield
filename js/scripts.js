//Place your JavaScript of jQuery snippets here. Remember to remove the <script> tags
jQuery(document).ready(function ($) {
	function gallerySet(item) {
		var gallery = item;
		var selector = `${item} .et_pb_image_wrap`;
		$(selector).each(function (index) {
			itemIndex = index;
			$(this).data('slider', index);
			$(this).on('click', function () {
				$(selector).removeClass('active');
				$(this).addClass('active');
				itemIndex = ` ${gallery} .et-pb-controllers a:nth-child(${index + 1})`;
				$(itemIndex).trigger('click');
			});
		});
	}
	gallerySet(`.js--gallery-1`);
	gallerySet(`.js--gallery-2`);
	gallerySet(`.js--gallery-3`);
	gallerySet(`.js--gallery-4`);
});
