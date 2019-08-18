<p class = "h4 mb-1">Edit article</p>
<p><small>Created on <?php echo $article->formatDate(); ?> by <?php echo $article->user_->user_name; ?></small></p>
<?php if($article->article_valid !== 1): ?>
	<div class = "alert alert-danger" role = "alert">Article is not validated for it's content. Please check it and press <a href = "<?php echo ADMIN_URL.'/article/validate/'.$article->article_id; ?>">HERE</a> to validate.</div>
<?php endif; ?>
<div class = "tabs">
	<ul>
		<li><a href = "#tab-1">Content</a></li>
		<li><a href = "#tab-2">Gallery</a></li>
	</ul>
	<div class = "panel panel-content">
		<div id = "tab-1">
			<form action = "<?php echo ADMIN_URL.'/article/update/'.$article->article_id; ?>" method = "POST" name = "edit">
				<div class = "form-group">
					<label>Title</label>
					<input type = "text" name = "article_title" value = "<?php echo $article->article_title; ?>" class = "form-control" required />
				</div>
				<div class = "form-group">
					<label>Content</label>
					<textarea name = "article_content" class = "form-control"><?php echo $article->article_content; ?></textarea>
				</div>
				<div class = "form-group">
					<label>Category</label>
					<select name = "article_category_id" class = "form-control col-md-4">
						<?php foreach(article_category_::getAll() as $category): ?>
							<option value = "<?php echo $category->article_category_id; ?>" <?php echo ($category->article_category_id == $article->article_category_->article_category_id) ? "selected" : ""; ?>><?php echo $category->article_category_name; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<input type = "hidden" name = "redirect" value = "<?php echo $_SERVER['HTTP_REFERER']; ?>" />
				<button role = "submit" class = "btn btn-primary">Save</button>
				<a href = "/article/display/<?php echo $article->article_path; ?>" class = "btn btn-secondary ml-1">Preview</a>
			</form>
		</div>
		<div id = "tab-2" class = "hide">
			<div class = "alert alert-img" role = "alert"></div>
				<span class = "ui-helper-hidden-accessible"><input type = "text" /></span>
				<div class = "row img-base">
					<div class = "upload-box col-2 d-flex justify-content-center align-items-center">
						<div id = "file-upload" class = "upload-new align-text-bottom"><i class="fas fa-plus-circle fa-3x point"></i></div>
					</div>
					<?php if(isset($article->images_)): ?>
						<?php foreach($article->images_->images as $image): ?>
							<div class = "col-2 img-crop mb-3">
								<div class = "img-h">
									<a href = "/upload/images/<?php echo $image->image_url; ?>">
										<img src = "/upload/images/<?php echo $image->image_url; ?>" <?php echo ($image->image_default == 1) ? 'class = "img-fluid img-default"' : 'class = "img-fluid"'; ?> />
									</a>
									<div class = "img-overlay">
										<div class = "icon-h">
											<button class = "img-delete btn btn-link" value = "<?php echo $image->image_id; ?>" type = "button"><i class="fas fa-trash-alt"></i></button>
											<button class = "img-def btn btn-link" value = "<?php echo $image->image_id; ?>" type = "button"><i class="fas fa-star"></i></button>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<form method = "POST" enctype = "multipart/form-data" class = "form out-of-screen" name = "image_upload">
	<input type = "file" name = "image[]" id = "image" class = "file-input" multiple />		
	<input type = "hidden" name = "object_id" value = "<?php echo $article->article_id; ?>" />
	<input type = "hidden" name = "object" value = "article" />
<form>