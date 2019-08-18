$(document).ready(function() {
	$('form[name="bikez-copy"]').on('submit', function(e) {
		e.preventDefault();
		var $this = $(this);
		var copy_bikez = $('input[name="copy-bikez"]').val();
		var copy_brand_id = $('input[name="brand_id"]', this).val();
		var al = $('.alert-success', $this);
		al.addClass('d-none');
		if(bikez) {
			$.ajax({
				method: 'POST',
				url: '/ajax/copy_bikez.php',
				data: {copy_bikez},
				success: function(data) {
					if(data) {
						var source = $(data);
						var table = $('.zebra', source);
						var arr = [];
						$('td', table).each(function() {
							if($(this).attr('rowspan')) {
								$(this).remove();
							}
						});
						var trs = $('tr:not(:first):not(:last)', table);
						for(i = 0; i < trs.length; i++) {
							var tds = $(trs[i]).find('td');
							if(tds.length < 3) {
								arr[i] = {
									'name': $(tds[0]).text(),
									'year': $(trs[i + 1]).find('td:first').text(),
									'url': $(tds[0]).find('a').attr('href').slice(2)
								};
								trs.splice(i + 1, 1);
							}
							else {
								arr[i] = {
									'name': $(tds[0]).text(),
									'year': $(tds[1]).text(),
									'url': $(tds[0]).find('a').attr('href').slice(2)
								}
							}
						}
						if(arr.length > 0) {
							copy_bikez_arr = JSON.stringify(arr);
							$.ajax({
								method: 'POST',
								url: '/ajax/copy_bikez.php',
								data: {copy_bikez_arr, copy_brand_id},
								success: function(response) {
									if(response == true) {
										al.addClass('alert-success').removeClass('d-none').text('Data added');
									}
									else {
										al.addClass('alert-warning').removeClass('d-none').text('Database error');
									}
								},
								error: function() {
									alert('Ajax error.');
								}
							});
						}
					}
					else {
						al.addClass('alert-warning').removeClass('d-none').text("Couldn't fetch source.");
					}
				},
				error: function() {
					alert('Ajax error.');
				}
			});
		}
	})
});