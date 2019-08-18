$(document).ready(function() {

	/**
	 * Edit a review. Display dialog with a form containing review data. 
	 */

	$('.r-edit').on('click', function() {
		var review_id = $(this).val(); // Get value of the button => it's reviews id.
		var review = $(this).closest('section.news');
		var review_title = review.find('.r-title').text(); // Get title and content of the review.
		var review_content = review.find('.r-content').text();
		var dialog = $('#dialog-review-edit');
		var form = $('[name="review-edit-form"]');
		form.attr('action', '/user/reviewe/' + review_id); // Update forms action.
		var dialog_title = form.find('[name="review_title"]');
		var dialog_content = form.find('[name="review_content"]');
		dialog_title.val(review_title);
		dialog_content.trumbowyg('html', review_content); // Trumbowyg.
		dialog.dialog('open'); // Display dialog after updating all the data.
		form.on('submit', function(event) {
			var valid = $(this).validation(event, {prevent: false});
			if(!valid) {
				event.preventDefault();
			}
		});
		$('.review-message').hide();
	});
});