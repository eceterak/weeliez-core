<p class = "h4 mb-3">New category</p>
<form action = "<?php echo ADMIN_URL.'/article_category/create/'; ?>" method = "POST" class = "panel panel-content">
	<div class = "form-group">
		<label for = "article_category_name">Name</label>
		<input type = "text" class = "form-control" name = "article_category_name" required />
	</div>
	<button role = "submit" class = "btn btn-primary">Save</button>
</form>