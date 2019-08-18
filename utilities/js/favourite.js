$(document).ready(function() {

	/**
	 */

	$('#favourite').on('click', function() {
		var favourite_bike_id = $(this).val(); // Get bike id (button value).
		var icon = $(this).find('.fa-star');
		var dataPrefix = icon.attr('data-prefix'); // To update the icon, change svg data-prefix propety.
		var $this = $(this);
		$.ajax({
			method: 'POST',
			dataType: 'json',
			url: '/ajax/favourite.php',
			data: {favourite_bike_id},
			success: function(data) {
				if(data.success === true) {
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