/**
 * Delete spec. Ajax is very helpfull here because it prevents reloading a whole page when deleting one spec.
 */

$(document).on('click', 'button.spec-delete', function() {
	var id = $(this).val();
	var row = $(this).closest('tr');
	$.ajax({
		method: 'POST',
		url: '/ajax/deleteSpec.php',
		dataType: 'json',
		context: this,
		data: {
			spec_delete_id: id,
			ajax: true
		},
		success: function(json) {
			if(json.success !== false) {
				row.remove();
			}
			else {
				alert('Database error');
			}
		},
		statusCode: {
			404: function() {
				alert('Page not found');
			}
		}
	})
});

/**
 * Add a new attribute input. Use ajax to get all atriubute data like id, name etc.
 */

$(document).on('click', 'span.add', function () {
	var $addRow = $(this).closest('tr.addRow');
	var $select = $(this).closest('tr').find('select.attribute');
	var value = $select.val();
	var $options = $select.children();
	$.ajax({
		method: 'POST',
		url: '/ajax/addAttributeInput.php',
		dataType: 'json', // This will alow to fetch object.
		context: this, // Use this from $add context not ajax.
		data: {
			add_attribute_id: value
		},
		success: function(json) {
			if(json.success !== false) {
				$select.find('[value = "' + json.attribute_id + '"]').remove(); // Remove option.
				var input = '<tr><td class = "attribute_name">' + json.attribute_name + '</td><td><input class = "form-control" type = "text" name = "spec_' + json.attribute_id + '"></td>';
				if(json.attribute_sub == 1) input += '<td class = "text-center">@</td><td><input class = "form-control" type = "text" name = "spec_' + json.attribute_id + '_sub" /></td>';
				input += '<td><button type = "button" class = "btn btn-link p-0 del"><i class = "fa fa-times" aria-hidden = "true"></i></button></td></tr>';
				$(this).closest('.addRow').before(input);
				if($options.length < 2) {
					$addRow.html('<td colspan = "2" class = "addRow">No more attributes.</td>');
				}
			}
			else {
				alert('Something went wrong.');
			}
		},
		statusCode: {
			404: function() {
				alert('Page not found');
			}
		}
	}); // End of ajax call.
});

/**
 * Delete attribute input then add it back to the select.
 * If select input does not exist because all elements were used, create it and add to dom.
 */

$(document).on('click', 'button.del', function() {
	var $addRow = $(this).closest('tbody').find('tr.addRow'); // Find the closest select.
	var $select = $addRow.find('select.attribute');
	var $row = $(this).closest('tr'); // Tr containing a new input's.
	if($select.length < 1) {
		$select = '<td></td><td><select  class = "attribute form-control"></select></td><td><span class = "icon-click add ml-1"><i class="fas fa-plus"></i></span></td>';
		$addRow.html($select); // html() will replace everything.
		var $select = $addRow.find('select.attribute');
	}
	var $input = $row.find('input').slice(0, 1); // Limit results to 1.
	var option = document.createElement('option');
	var txt = $row.find('.attribute_name').text();
	var name = $input.attr('name');
	if(name.indexOf('spec_') !== -1) {
		name = name.substring('spec_'.length); // Option value shouldn't contain spec_.
	}
	option.text = $row.find('.attribute_name').text();
	option.value = name;
	$row.remove();
	$select.append(option); // Add option to select.
});

$(document).ready(function(){

	/**
	 * Drag and drop sort function. Designed to work with attributes. It uses ajax call to update priority
	 * of a attributes in a Database. Thanks to ajax it does it real time, and also reloads page to refresh list.
	 */
	 
	if($('.sortable').length > 0) {
		$('.sortable').sortable({
			// Everytime when any change occurs. 
			update: function(e, ui) {
				var data = $(this).sortable('serialize'); // Get id's of all elements (item_$id);
				console.log(data);
				$.ajax({
					method: 'POST',
					url: '/ajax/setAttributePriority.php',
					data: data,
					success: function(data) {
						if(data.success) {
							location.reload(); // Reload page to update list.
						}
						else {
							alert('Sorry, cannot update right now.');
						}
					},
					error: function() {
						alert('Sorry, cannot update right now.');
					},
					statusCode: {
						404: function() {
							alert('Page not found');
						}
					}
				}); // End of ajax call.
			}
		});
	}
});