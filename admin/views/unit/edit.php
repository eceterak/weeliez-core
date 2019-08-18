<p class = "h4 mb-3">Edit unit</p>
<form action = "<?php echo ADMIN_URL.'/unit/update/'.$unit->unit_id; ?>" method = "POST" class = "panel panel-content">
	<div class = "form-group">
		<label>Name</label>
		<input type = "text" class = "form-control"  value = "<?php echo $unit->unit_name; ?>" name = "unit_name" />
	</div>
	<input type = "hidden" name = "redirect" value = "<?php echo $_SERVER['HTTP_REFERER']; ?>" />
	<button type = "submit" class = "btn btn-primary">Save</button>
</form>