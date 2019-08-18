$(document).ready(function() {
	
	/**
	 * Manage images trough a dialog window.
	 */

	var dialog = $(".img-dialog").dialog({
		resizable: false,
		autoOpen: false,
		height: $(window).height() - 50,
		width: $(window).width() - 50,
		modal: true,
		close: function() {
			dialog.dialog("close");
		}
	});

	/**
 	 * Display an image management window.
 	 */

	$(".img-manage").on("click", function() {
		dialog.dialog("open");
	});
});


/**
 * Setup all alert window's proporties in one go.
 * @param item [object]
 * @param message [string]
 * @param className [string]
 * @param time [int]
 */

function alertSetup(item, message, className, time = 1200) {
	item.text(message);
	item.addClass(className);
	item.fadeIn(time).fadeOut(time);
}

/**
 * To send files trouhg ajax use FormData object where form is it's property. Thanks to FormData, php will have access to whole form and all inputs.
 * Set timeout to stop the script if it's taking too much time.
 */

$(function() {
	var form = $('form[name="image_upload"]');
	form.on("submit", function(event) {
		event.preventDefault();
		var files = form.find('input[name="image[]"]');
		var length = files[0].files.length;
		// Run only when any files choosen.
		if(length > 0) {
			var formData = new FormData(form[0]);
			$.ajax({
				type: 'POST',
				url: '/ajax/imageUpload.php',
				data: formData,
				async: true,
				processData: false,
				contentType: false,
				cache: false,
				timeout: 60000,
				/*xhr: function() {
					var myXhr = $.ajaxSettings.xhr();
					if(myXhr.upload) {
						myXhr.upload.addEventListener('progress', this.progressHandling, false);
					}
					return myXhr;
				},*/
				success: function(data) {
					var $alert = $('.alert-img');
					if(data.length > 0) {
						for(var i = 0; i < data.length; i++) {
							if(data[i].success == true) {
								var img = '<div class = "col-2 img-crop mb-3"><div class = "img-h"><a href = "' + data[i].url + '"><img src = "' + data[i].url + '" class = "img-fluid"; /></a>';
									img += '<div class = "img-overlay"><div class = "icon-h"><button class = "img-delete btn btn-link" value = "' + data[i].image_id +'" type = "button"><i class="fas fa-trash-alt"></i></button>';
									img += '<button class = "img-def btn btn-link" value = "' + data[i].image_id +'" type = "button"><i class="fas fa-star"></i></button>';								
									img += '</div></div></div></div>';
									$(".img-base").append(img);
									$alert.text('Image uploaded.'); // Error message comes from backend function.
									alertSetup($alert, 'Image uploaded!', 'alert-success');
							}
							else {
								alertSetup($alert, data[i].message, 'alert-danger');
							}
						}
					}
				},
				error: function() {
					alert('Ajax error.');
				}
			});
		}
	});
});

/**
 * Delete a image. To obtain a image_id, look for hidden input of image_id name.
 * As imageManagement.php operates many functions, data provides to ajax must contain a action method.
 * After operation, show a alert box with a message to the user.
 */

$(document).on("click", 'button.img-delete', function() {
	var $this = $(this);
	var container = $this.closest('div.col-2');
	var image_id = $this.val();
	$.ajax({
		method: 'POST',
		url: '/ajax/imageManagement.php',
		dataType: 'json',
		data: {
			manage_image_id: image_id,
			image_action: 'delete'
		},
		success: function(data) {
			var $alert = $('.alert-img');
			if(data.status == true) {
				alertSetup($alert, 'Image deleted!', 'alert-success');
				container.remove(); // Remove image.
			}
			else {
				alertSetup($alert, data.message, 'alert-danger');
			}
		},
		error: function() {
			alert('Ajax error.');
		}
	});
});

/**
 * Same as above but sets a default image.
 * If there is another image with img-default class, remove this class from this item and add img-default to current image.
 */

$(document).on("click", 'button.img-def', function() {
	var $this = $(this);
	var container = $this.closest('div.col-2');
	var image_id = $this.val();
	var image = container.find($('img'));
	$.ajax({
		method: 'POST',
		url: '/ajax/imageManagement.php',	
		dataType: 'json',
		data: {
			manage_image_id: image_id,
			image_action: 'default'
		},
		success: function(data) {
			var $alert = $('.alert-img');
			if(data.status == true) {
				alertSetup($alert, 'Featured image changed!', 'alert-success');
				$('img').removeClass('img-default'); // Remove img-default.
				image.addClass('img-default'); // Add img-default to current image.
			}
			else {
				alertSetup($alert, data.message, 'alert-success');			
			}
		},
		error: function() {
			alert('Ajax error.');
		}
	});
});

/**
 *
 */

$('#file-upload').on('click', function() {
	$('#image').click();
});

$('#image').on('change', function() {
	$('form[name="image_upload"]').submit();
})

