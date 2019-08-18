<p class = "h4 mb-3">Edit category</p>
<form action = "<?php echo ADMIN_URL.'/article_category/update/'.$category->article_category_id; ?>" method = "POST" class = "panel panel-content">
	<div class = "form-group">
		<label for = "article_category_name">Name</label>
		<input type = "text" class = "form-control" value = "<?php echo $category->article_category_name; ?>" name = "article_category_name" required />
	</div>
	<div class = "form-check">
		<label for = "article_category_active" class = "form-check-label">
			<input type = "hidden" name = "article_category_active" value = "0" />
			<input type = "checkbox" name = "article_category_active" value = "1" <?php echo ($category->article_category_active == 1) ? 'checked' : ''; ?> class = "form-check-input" />
			Active
		</label>
	</div>
	<p class = "text-muted"><small>Articles from inavtice category won't be displayed.</small></p>
	<input type = "hidden" name = "redirect" value = "<?php echo $_SERVER['HTTP_REFERER']; ?>" />
	<button role = "submit" class = "btn btn-primary">Save</button>
</form>