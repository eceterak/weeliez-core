<h5>My account</h5>
<div>
	<ul class = "list-group">
		<li class = "list-group-item"><a href = "#" id = "change-password">Change password</a></li>
		<li class = "list-group-item"><a href = "#" id = "change-email">Change email</a></li>
		<li class = "list-group-item"><a href = "#" id = "delete-account">Delete account</a></li>
	</ul>
</div>
<div class = "dialog dialog-password">
	<span class = "ui-helper-hidden-accessible"><input type = "text" /></span>
	<button type="button" class="close point dialog-close" aria-label="Close">
  		<span aria-hidden="true" style = "font-size: 20px;">&times;</span>
	</button>
	<span>Password change</span>
	<div class = "change-password-form pt-4 pb-4 form-container">
		<div class = "alert alert-danger d-none dialog-message"></div>
		<form name = "change-password-form" novalidate>
			<div class = "form-group">
				<label>Old password</label>
				<input type = "password" name = "user_password_old" class = "form-control" required />
			</div>
			<div class = "form-group">
				<label>New password</label>
				<input type = "password" name = "user_password" class = "form-control" required />
			</div>
			<div class = "form-group">
				<label>Confirm password</label>
				<input type = "password" class = "form-control" />
			</div>
			<div class = "form-group mb-0">
				<input type = "hidden" name = "user_id" value = "<?php echo $user->getId(); ?>" />
				<button type = "submit" class = "btn btn-primary btn-block">Submit</button>
			</div>
		</form>
	</div>
</div>
<div class = "dialog dialog-email">
	<span class = "ui-helper-hidden-accessible"><input type = "text" /></span>
	<button type="button" class="close point dialog-close" aria-label="Close">
  		<span aria-hidden="true" style = "font-size: 20px;">&times;</span>
	</button>
	<span>Password change</span>
	<div class = "change-email-form pt-4 pb-4 form-container">
		<div class = "alert alert-danger d-none dialog-message"></div>
		<form name = "change-email-form" novalidate>
			<div class = "form-group">
				<label>New email address</label>
				<input type = "email" name = "user_email" class = "form-control" required />
			</div>
			<div class = "form-group">
				<label>Confirm email address</label>
				<input type = "email" class = "form-control" />
			</div>
			<div class = "form-group">
				<label>Enter current password</label>
				<input type = "password" name = "user_password" class = "form-control" required />
			</div>
			<div class = "form-group mb-0">
				<input type = "hidden" name = "user_id" value = "<?php echo $user->getId(); ?>" />
				<button type = "submit" class = "btn btn-primary btn-block">Submit</button>
			</div>
		</form>
	</div>
</div>