$(document).ready(function() {

	/**
	 * Add 'active' class to the current menu item using location href and collapse all items from collapse-menu if item is a part of it.
	 */

	var loc = location.href;
	loc = loc.substr(loc.indexOf('.local') + '.local'.length); // It will return something like /admin/controller/action/id.
	locArr = loc.split('/');
	loc = '/' + locArr[1] + '/' + locArr[2]; // /admin/controller

	$('li .nav-link').each(function() {
		var $this = $(this);
		if($this.attr('href') == loc) {
			$this.addClass('active'); // Add active class to current item.
			if($this.parents('.collapse-menu').length) {
				menu = $this.parents('.collapse-menu');
				subMenu = menu.find('.sub-menu');
				if(subMenu !== typeof 'undefined' && subMenu !== false) {
					subMenu.removeClass('d-none'); // Make sure subMenu exists before using 
				}
			}
		}
	});

	/**
	 * Apply novalidate attribute and validation function for each form on the page.
	 * This must be on the top of this file as some forms are having a dedicated validation. 
	 */

	/*$('form').each(function() {
		$(this).attr('novalidate', 'novalidate');
	});

	$("form").each(function() {
		$(this).on('submit', function(event) {
			var valid = $(this).validation(event, {
				prevent: false
			});
			if(!valid) {
				event.preventDefault();
			}
		});
	});

	*/

	/**
	 * Reload page on click on the button.
	 * This button can be dinamically added to the dom so work on document.
	 */

	$(document).on('click', '.btn-reload', function() {
		location.reload();
	});

	/**
	 * Disable the 'Apply' button in bulk action form to prevent submiting.
	 */

	$('select[name="action"]').on('change', function() {
		var form = $(this).closest('form');
		var button = form.find('.btn-bulk-apply');
		if($(this).val() !== 'bulk') {
			button.attr('disabled', false);		
		}
		else {
			button.attr('disabled', true);
		}
	});

	/**
	 * Display table filters.
	 */

	$('.display-filters').on('click', function() {
		var filters = $('.filters');
		if(filters.hasClass('d-none')) {
			filters.removeClass('d-none');
		}
		else {
			filters.addClass('d-none');
		}
	});

	/**
	 * BIKE 
	 */

	/**
	 * Validate new bike form and quick add form.
	 */

	$('form[name="bike_add_form"]').on('submit', function(event) {
		var valid = $(this).validation(event, {
			prevent: false,
			inputs: {
				bike_name: {
					min: 3,
					max: 60,
					message: 'Too long or too short'
				},
				bike_year_start: {
					min: 4,
					max: 4,
					message: 'Year must be 4 digits long.'
				}
			}
		});
		if(!valid) {
			event.preventDefault();
		}
	});

	$('form[name="quick-add"]').on('submit', function(event) {
		var valid = $(this).validation(event, {
			prevent: false,
			inputs: {
				bike_name: {
					min: 2,
					max: 60,
					message: 'Too long or too short'
				},
				bike_year_start: {
					min: 4,
					max: 4,
					message: 'Year must be 4 digits long.'
				}
			}
		});
		if(!valid) {
			event.preventDefault();
		}
	});

	/**
	 * Open copy dialog.
	 */

	$('.copy-specs').on('click', function() {
		$('.copy-specs-dialog').dialog('open');
	});

	$('.bikez-copy').on('click', function() {
		$('.bikez-copy-dialog').dialog('open');
	});

	/**
	 * Open quick add dialog.
	 */

	$('.quick-add').on('click', function() {
		$('.quick-add-dialog').dialog('open');
	});

	/**
	 * Validate edit bike form.
	 */

	$('form[name="bike_edit_form"]').on('submit', function(event) {
		var valid = $(this).validation(event, {
			prevent: false,
			inputs: {
				bike_name: {
					min: 2,
					max: 50,
					message: 'Too long/short.'
				},
				bike_year_start: {
					min: 4,
					max: 4,
					message: 'Year must be 4 digits long.'
				}
			}
		});
		if(!valid) {
			event.preventDefault();
		}
	});

	/**
	 * Disable attribute search method select if attribute search is not checked (attribute not to be shown in advanced search form).
	 */

	$('input[name="attribute_search"]').on('change', function() {
		var select = $('select[name="attribute_search_method"]');
		if($(this).is(':checked')) {
			select.prop('disabled', false);
		}
		else {
			select.prop('disabled', true);
		}
	});


	/**
	 * If bike is still for sale, disable bike_year_end input and zero it's value (if any set).
	 */

	$('select[name="bike_sale"]').on('change', function() {
		var status = $(this).val();
		var end = $('input[name="bike_year_end"]');
		var form = end.closest('form');
		if(status == 1) {
			end.val('');
			if(!end.is(':disabled')) {
				end.prop('disabled', true);
			}
		}
		else {
			if(end.is(':disabled')) {
				end.prop('disabled', false);
			}
		}
	});

	/**
	 * ADDONS initialization.
	 */

	$('.accordion').accordion({
		collapsible: true,
		active: false
	});

	$('.tabs').tabs();

	$(".dialog").dialog({
		resizable: false,
		draggable: false,
		autoOpen: false,
		position: {my: 'centre', at: 'top', of: window},
		height: "auto",
		width: "auto",
		modal: true,
		close: function() {
			$(this).dialog("close");
		}
	});

	/**
	 * Close the dialog box.
	 */

	$(".dialog-close").on("click", function() {
		$(".dialog").dialog("close");
		$(".dialog-wide").dialog("close");
	});

	/**
	 * Initialize select2 (search inside a select dropdown).
	 */

	$('.select2').select2({
		width: '100%', // Match width of the parent.
		theme: 'bootstrap'
	});

	/**
	 * Initialize trubowyg text editor.
	 * Enable resizing images and uploading them.
	 * Build whole buttons pane.
	 */

	$('textarea').trumbowyg({
		removeformatPasted: true,
		autogrowOnEnter: true,
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
				serverPath: '/ajax/trumbowygImageUpload.php',
				fileFieldName: 'ajaxImage'
			}
		}
	});

	/**
	 * MISC
	 */

	/**
	 * Close alert window.
	 */

	$('.close').on('click', function() {
		$(this).parents('.alert').fadeOut();
	});
});

/**
 * Select perpage cookie to change amount of displayed items on the page.
 */

function perPage() {
	$('select[name=perPage]').change(function() {
		$value = $('select[name=perPage]').val();
		document.cookie = 'perpage = ' + $value;
		location.reload();
	});	
}

perPage();

/**
 * Check all inputs. 
 */

function clickCheckAll() {
	$(this).on('change', function() {
		$('input:checkbox').prop('checked', this.checked);
	});
}

// Instead of adding onclick event to each file, add it globally.

$('#checkAll').on('click', clickCheckAll);

/**
 * Change bike category on the go.
 */

$(document).ready(function() {
	$('select[name="qe_category_id"]').on('change', function() {
		var row = $(this).closest('tr.bike');
		var bike_id = row.find('input[name="qe_bike_id"]').val();
		var category_id = $(this).val();
		$.ajax({
			method: 'POST',
			url: '/ajax/category_change.php',
			data: {
				qe_category_id: category_id,
				qe_bike_id: bike_id
			},
			success: function() {
				console.log('trtrtrtrt');
			}

		});
	});
});