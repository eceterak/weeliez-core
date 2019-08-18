<p class = "h4 mb-3">New role</p>
<form action = "/admin/access/create/" method = "POST" name = "access" class = "panel panel-content">
	<div class = "form-group">
		<label for = "access_name">Name</label>
		<input type = "text" name = "access_name" class = "form-control" placeholder = "Name" required />
	</div>
	<div class = "form-group">
		<label for = "access_name">Level</label>
		<input type = "number" name = "access_level" class = "form-control" placeholder = "Level" required />
	</div>
	<button role = "submit" class = "btn btn-primary">Save</button>
</form>