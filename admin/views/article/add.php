<p class = "h4 mb-3">New article</p>
<form action = "<?php echo ADMIN_URL.'/article/create/'; ?>" method = "POST" name = "article" class = "panel panel-content">
	<div class = "form-group">
		<label>Title</label>
		<input type = "text" name = "article_title" class = "form-control" required />
	</div>
	<div class = "form-group">
		<label>Content</label>
		<textarea name = "article_content" class = "form-control"></textarea>
	</div>
	<div class = "form-group">
		<label>Category</label>
		<select name = "article_category_id" class = "form-control col-md-4">
			<?php foreach(article_category_::getAll() as $category): ?>
				<option value = "<?php echo $category->article_category_id; ?>"><?php echo $category->article_category_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<input type = "hidden" name = "redirect" value = "<?php echo $_SERVER['HTTP_REFERER']; ?>" />
	<input type = "hidden" name = "user_id" value = "<?php echo $user_->getId(); ?>" />
	<button role = "submit" class = "btn btn-primary">Save</button>
</form>