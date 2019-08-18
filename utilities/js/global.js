$(document).ready(function() {

	/**
	 * On ajax success, use jQuery autocomplete response event to populate options. Label is what user sees on front - end.
	 * As bikes of the same name are often produced in different years, display years of production to help user decide with one to choose.
	 * Results looks like this: Suzuki GS 500 2003-2008.
	 */

	$('.autocomplete-compare').autocomplete({
		source: function(request, response) {
			$.ajax({
				method: 'POST',
				url: '/ajax/bikeAutocompleteSearch.php',
				dataType: "json",
				data: {
					bike_name_autocomplete: request.term
				},
				success: function(data) {
					response($.map(data, function(bike) {
						var bike_year = (bike.bike_year_start != bike.bike_year_end) ? bike.bike_year_start + ' - ' + bike.bike_year_end : bike.bike_year_start;
						return {
							label: bike.bike_name + ' ' + bike_year,
							id: bike.bike_id
						}
					}));
				},
			});
		},
		select: function(event, ui) {
			var location = window.location.href; // Get current location.
			var compare = location.indexOf('?');
			location = location.substring(0, compare != -1 ? compare : location.length); // Remove any GET's if set.
			location = location + '?compare=' + ui.item.id;
			window.location.href = location; // Redirect to a new href with added ?compare=id to compare the bikes.
		} 
	});


	$('.gallery').gallery();

	$('.gallery-open').on('click', function() {
		$('.gallery').gallery('open');
	});

	/**
	 *
	 */

	$('[data-toggle = "tooltip"]').tooltip();

	/**
	 *
	 */

	$("#tabs").tabs();

	/**
	 *
	 */

	$('.multi').multiselect({});

	/**
	 *
	 */

	$('select[name=perpage]').change(function() {
		$value = $('select[name=perpage]').val();
		document.cookie = 'perpage = ' + $value;
		location.reload();
	});

	/**
	 * Initialize trubowyg text editor.
	 * Enable resizing images and uploading them.
	 * Build whole buttons pane.
	 */

	$('textarea').trumbowyg({
		btns: [
		    ['viewHTML'],
	        ['undo', 'redo'], // Only supported in Blink browsers
	        ['formatting'],
	        ['strong', 'em', 'del'],
	        ['superscript', 'subscript'],
	        ['link'],
	        ['upload', 'insertImage'],
	        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
	        ['unorderedList', 'orderedList'],
	        ['horizontalRule'],
	        ['removeformat'],
	        ['fullscreen']
		],
		imageWidthModalEdit: true,
		plugins: {
			upload: {
				serverPath: '/ajax/imageUpload.php',
				fileFieldName: 'ajaxImage'
			}
		}
	});

	/**
	 *
	 */

	$('.slider').each(function() {
		var $this = $(this);
		var attribute = $('.amount', $this).attr('name');
		var $_get = getUrlAttributes();
		if($_get[attribute]) values = $_get[attribute].split('-');
		$.ajax({
			method: 'POST',
			url: '/ajax/sliderValues.php',
			dataType: 'json', // This will alow to fetch object.
			data: {
				slider_attribute: attribute
			},
			success: function(json) {
				var min = (typeof values !== 'undefined') ? values[0] : json[0].min;
				var max = (typeof values !== 'undefined') ? values[1] : json[0].max;
				$('.val-left', $this).text(min);
				$('.val-right', $this).text(max);
				$('.slider-range', $this).slider({
					range: true,
					min: json[0].min,
					max: json[0].max,
					values: [min, max],
					slide: function(event, ui) {
						$('.val-left', $this).text(ui.values[0]);
						$('.val-right', $this).text(ui.values[1]);
						//console.log(json[0].max);
						//console.log(ui.values[1]);
						if(ui.values[0] == json[0].min && ui.values[1] == json[0].max) {
							$('.amount', $this).val(''); // Set value to zero.
						}
						else {
							$('.amount', $this).val(ui.values[0] + "-" + ui.values[1]);
						}
					}
				});
			},
			error: function() {
				alert('Sorry, cannot update right now.');
			}
		}); // End of ajax call.
	});

	/**
	 *
	 */

	$(".dialog").dialog({
		resizable: false,
		draggable: false,
		autoOpen: false,
		position: {my: 'top', at: 'top', of: window},
		height: "auto",
		width: "400",
		modal: true,
		close: function() {
			$(this).dialog("close");
		}
	});

	/**
	 *
	 */

	$(".dialog-wide").dialog({
		resizable: false,
		draggable: false,
		autoOpen: false,
		position: {my: 'top', at: 'top', of: window},
		height: "auto",
		width: "auto",
		modal: true,
		close: function() {
			$(this).dialog("close");
		}
	});

	/**
	 * Close the dialog or wide dialog.
	 */

	$(".dialog-close").on("click", function() {
		$(".dialog").dialog("close");
		$(".dialog-wide").dialog("close");
	});
});

/**
 * Load $_GET values.
 * @return object
 */

function getUrlAttributes() {
	var parts = window.location.search.substr(1); // Start from second character. It will ignore ?.
	parts = parts.split('&');
	var $_GET = {};
	for(i = 0; i < parts.length; i++) {
		var temp = parts[i].split('=');
		$_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
	}
	return $_GET;
}