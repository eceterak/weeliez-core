<p class = "h4 mb-3">New blog</p>
<form action = "/admin/blog/create" method = "POST" name = "create" class = "panel panel-content">
	<div class = "form-group">
		<label for = "user_name">Title</label>
		<input type = "text" name = "blog_title" class = "form-control" required />
	</div>
	<div class = "form-group">
		<label for = "user_password">Content</label>
		<textarea name = "blog_content" class = "form-control"></textarea>
	</div>
	<button role = "submit" class = "btn btn-primary">Save</button>
</form>		