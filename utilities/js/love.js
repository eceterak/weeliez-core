$(document).ready(function() {

	/**
	 * When user click's on heart icon send a ajax request and update the icon and 'loves' count.
	 * If user is not logged in, display login/signup dialog box with a error message.
	 */

	$('#love').on('click', function() {
		var love_bike_id = $(this).val(); // Get bike id (button value).
		var icon = $(this).find('.fa-heart');
		var dataPrefix = icon.attr('data-prefix'); // To update the icon, change svg data-prefix propety.
		var message = $('#love-sign');
		var $this = $(this);
		$.ajax({
			method: 'POST',
			dataType: 'json',
			url: '/ajax/love.php',
			data: {love_bike_id},
			success: function(data) {
				if(data.success === true) {
					$('.loves').text(data.loves);
					if(dataPrefix == 'fas') {
						icon.attr('data-prefix', 'far');
					}
					else {
						icon.attr('data-prefix', 'fas');
					}
				}
				else if(data.success === false && data.error == 'login') {
					$(".dialog-sign").dialog("open"); // User is not logged in.
				}
				else {
					alert('Database error, please try later.');
				}
			},
			statusCode: {
				404: function() {
					alert('Page not found');
				}
			}
		});
	});
});