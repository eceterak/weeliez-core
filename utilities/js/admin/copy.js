$(document).ready(function() {
	$('form[name="copy-specs"]').on('submit', function(e) {
		e.preventDefault();
		var bikez = $('input[name="bikez"]').val();
		var motorcyclespecs = $('input[name="motorcyclespecs"]').val();
		var bike_id = $('input[name="bike_id"]').val();
		var $this = $(this);
		if(bikez || motorcyclespecs) {
			$.ajax({
				method: 'POST',
				url: '/ajax/copy.php',
				dataType: 'json',
				data: {bikez, motorcyclespecs},
				success: function(data) {
					if(data.success == true) {
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
					}
					data = JSON.stringify(all);
					$.ajax({
						method: 'POST',
						url: '/ajax/copy.php',
						data: {data, bike_id},
						dataType: 'json',
						success: function(success) {
							if(success == true) {
								$('.copy-specs-dialog').dialog('close');
							}
							else {
								$this.append('<div class = "alert alert-warning mt-2 mb-0 text-center">Done</div>');
							}
						}
					});
				},
				error: function() {
					alert('Ajax error.');
				}
			});
		}
	})
});