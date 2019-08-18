/**
 * Add a new bike in one form. 
 */

$(document).ready(function() {
	$('form[name="quick-add"]').on('submit', function(e) {
		e.preventDefault();
		var success = false;
		var $this = $(this);
		var alert = $('.alert-success', $this);
		$.ajax({
			method: 'POST',
			url: '/ajax/quickAdd.php',
			dataType: 'JSON',
			data: {quickAdd_data: $this.serializeArray()},
			success: function(data) {
				var bike_id = data.bike_id;
				if(data.bike_added == true) {
					if(data.bikez || data.motorcyclespecs) {
						var all = {};
						if(data.bikez) {
							var arr = {};
							var source = $(data.bikez);
							var table = $('a[name="GENERAL"]', source).closest('table');
							$('tr', table).each(function() {
								var tds = $(this).find('td');
								if($(tds[1]).text().trim() !== '') {
									arr[$(tds[0]).text().trim().replace(':', '').toLowerCase()] = $(tds[1]).text().trim();
								}
							});
							all['bikez'] = arr;
						}
						if(data.motorcyclespecs) {
							var arr2 = {};
							var source = $(data.motorcyclespecs);
							var table = $('font:contains(Make)', source).closest('table');
							$('tr', table).each(function() {
								var tds = $(this).find('td');
								if($(tds[1]).text().trim() !== '') {
									arr2[$(tds[0]).text().trim().toLowerCase()] = $(tds[1]).text().trim().replace(/(\r\n\t|\n|\r\t)/gm, '');
								}
							});
							all['motorcyclespecs'] = arr2;
						}
						data = JSON.stringify(all);
						$.ajax({
							method: 'POST',
							url: '/ajax/copy.php',
							data: {data, bike_id},
							dataType: 'json',
							success: function(success) {
								if(success == true) {
									location.reload();
								}
								else {
									$this.append('<div class = "alert alert-warning mt-3 mb-0 text-center">Bike added but there was a problem with updating specs.<button type = "button" class = "btn btn-bulk mb-1 ml-2 btn-reload">OK</button></div>');
								}
							}
						});
					}
				}
				if(data.success == true) {
					alert.removeClass('d-none').text(data.bike_year + ' ' + data.bike_name + ' added');
				}
				else {
					$this.append('<div class = "alert alert-warning mt-3 mb-0 text-center">' + data.message + '<button type = "button" class = "btn btn-bulk mb-1 ml-2 btn-reload">OK</button></div>');
				}
			},
			error: function() {
				console.log('test');
				alert('Ajax error.');
			}
		})
	});
});