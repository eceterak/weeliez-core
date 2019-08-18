<p class = "h4 mb-3">New user</p>
<form action = "/admin/user/create" method = "POST" name = "create" class = "panel panel-content">
	<div class = "form-group">
		<label for = "user_name">Login</label>
		<input type = "text" name = "user_name" placeholder = "Login" class = "form-control" required />
	</div>
	<div class = "row">
		<div class = "form-group col-6">
			<label for = "user_password">Password</label>
			<input type = "password" name = "user_password" placeholder = "Password" class = "form-control" required />
		</div>
		<div class = "form-group col-6">
			<label for = "confirm_password">Confirm password</label>
			<input type = "password" name = "confirm_password" placeholder = "Confirm password" class = "form-control" class = "confirm" required />
		</div>
	</div>
	<div class = "row">
		<div class = "form-group col-6">
			<label for = "user_email">Email</label>
			<input type = "email" name = "user_email" placeholder = "Email" class = "form-control" required />
		</div>
		<div class = "form-group col-6">
			<label for = "confirm_email">Confirm email</label>
			<input type = "email" name = "confirm_email" placeholder = "Confirm email" class = "form-control" class = "confirm" required />
		</div>
	</div>
	<div class = "form-group">
		<label for = "access_id">Level</label>
		<select name = "access_id" class = "form-control">
			<?php foreach(access_::getAll() as $access): ?>
				<option value = "<?php echo $access->access_id; ?>" <?php echo ($access->access_name == 'moderator') ? "selected" : ""; ?>><?php echo $access->access_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<button role = "submit" class = "btn btn-primary">Save</button>
</form>		