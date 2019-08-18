<p class = "h4 mb-3">Edit user</p>
<form action = "<?php echo ADMIN_URL.'/user/update/'.$user->user_id; ?>" method = "POST" name = "edit" class = "panel panel-content">
	<div class = "form-group">
		<label>Name</label>
		<input type = "text" class = "form-control" value = "<?php echo $user->user_name; ?>" name = "user_name" required />
	</div>
	<div class = "form-group">
		<label>Email</label>
		<input type = "text" class = "form-control" value = "<?php echo $user->user_email; ?>" name = "user_email" required />
	</div>
	<div class = "form-group">
		<label>Access level</label>
		<select name = "access_id" class = "form-control">
			<option value = "0">---</option>
			<?php foreach(access_::getAll() as $access): ?>
				<option value = "<?php echo $access->access_id; ?>" <?php echo ($access->access_id == $user->access_->access_id) ? "selected" : ""; ?>><?php echo $access->access_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<input type = "hidden" name = "redirect" value = "<?php echo $_SERVER['HTTP_REFERER']; ?>" />
	<button type = "submit" class = "btn btn-primary">Save</button>
</form>