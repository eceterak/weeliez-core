<p class = "h4 mb-3">Edit blog</p>
<form action = "<?php echo ADMIN_URL.'/blog/update/'.$blog->blog_id; ?>" method = "POST" name = "create" class = "panel panel-content">
	<div class = "form-group">
		<label for = "user_name">Title</label>
		<input type = "text" name = "blog_title" value = "<?php echo $blog->blog_title; ?>" class = "form-control" required />
	</div>
	<div class = "form-group">
		<label for = "user_password">Content</label>
		<textarea name = "blog_content" class = "form-control"><?php echo $blog->blog_content; ?></textarea>
	</div>
	<input type = "hidden" name = "redirect" value = "<?php echo $_SERVER['HTTP_REFERER']; ?>" />
	<button role = "submit" class = "btn btn-primary">Save</button>
	<a href = "/blog/display/<?php echo $blog->blog_path; ?>" class = "btn btn-secondary ml-1">Preview</a>
</form>