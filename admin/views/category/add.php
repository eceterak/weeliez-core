<p class = "h4 mb-3">New category</p>
<form action = "/admin/category/create/" method = "POST" class = "panel panel-content">
	<div class = "form-group">
		<label>Name</label>
		<input type = "text" class = "form-control" name = "category_name" />
	</div>
	<div class = "form-group">
		<label>Description</label>
		<textarea name = "category_description"></textarea>
	</div>
	<button type = "submit" class = "btn btn-primary">Save</button>
</form>