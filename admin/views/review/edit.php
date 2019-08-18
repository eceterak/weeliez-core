<p class = "h4 mb-1">Edit review</p>
<p><small>Created on <?php echo $review->formatDate(); ?> by <?php echo $review->user_->user_name; ?></small></p>
<form action = "<?php echo ADMIN_URL.'/review/update/'.$review->review_id; ?>" method = "POST" name = "review" class = "panel panel-content">
	<div class = "form-group">
		<label>Title</label>
		<input type = "text" name = "review_title" value = "<?php echo $review->review_title; ?>" class = "form-control" required />
	</div>
	<div class = "form-group">
		<label>Content</label>
		<textarea name = "review_content" class = "form-control"><?php echo $review->review_content; ?></textarea>
	</div>
	<input type = "hidden" name = "redirect" value = "<?php echo $_SERVER['HTTP_REFERER']; ?>" />
	<input type = "hidden" name = "user_id" value = "<?php echo $user_->getId(); ?>" />
	<button role = "submit" class = "btn btn-primary">Save</button>
</form>