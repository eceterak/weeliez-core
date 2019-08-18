<p class = "h4 mb-3">Edit role</p>
<form action = "<?php echo ADMIN_URL.'/access/update/'.$access->access_id; ?>" method = "POST" name = "access" class = "panel panel-content">
	<div class = "form-group">
		<label for = "access_name">Name</label>
		<input type = "text" name = "access_name" class = "form-control" value = "<?php echo $access->access_name; ?>" required />
	</div>
	<div class = "form-group">
		<label for = "access_level">Level</label>
		<input type = "number" name = "access_level" class = "form-control" value = "<?php echo $access->access_level; ?>" required />
	</div>
	<input type = "hidden" name = "redirect" value = "<?php echo $_SERVER['HTTP_REFERER']; ?>" />
	<button role = "submit" class = "btn btn-primary">Save</button>
</form>