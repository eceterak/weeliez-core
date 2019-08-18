<p class = "h4 mb-3">Edit category</p>
<div class = "tabs">
	<ul>
		<li><a href = "#tab-1">General</a></li>
		<li><a href = "#tab-2">Images</a></li>
		<li><a href = "#tab-3">Bikes</a></li>
	</ul>
	<div class = "panel panel-content">
		<div id = "tab-1">
			<form action = "<?php echo ADMIN_URL.'/category/update/'.$category->category_id; ?>" method = "POST">
				<div class = "form-group">
					<label>Name</label>
					<input type = "text" class = "form-control" value = "<?php echo $category->category_name; ?>" name = "category_name" />
				</div>
				<div class = "form-group">
					<label>Description</label>
					<textarea name = "category_description"><?php echo $category->category_description; ?></textarea>
				</div>
				<button type = "submit" class = "btn btn-primary">Save</button>
			</form>
		</div>
		<div id = "tab-2" class = "hide">
			<div class = "alert alert-img" role = "alert"></div>
				<span class = "ui-helper-hidden-accessible"><input type = "text" /></span>
				<div class = "row img-base">
					<div class = "upload-box col-2 d-flex justify-content-center align-items-center">
						<div id = "file-upload" class = "upload-new align-text-bottom"><i class="fas fa-plus-circle fa-3x point"></i></div>
					</div>
					<?php if(isset($category->images_)): ?>
						<?php foreach($category->images_->images as $image): ?>
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
		<div id = "tab-3" class = "hide">
			<a href = "<?php echo ADMIN_URL; ?>/bike/add/<?php echo $category->category_id; ?>" class = "btn btn-small btn-light mb-2">Add new</a>
			<?php if(isset($bikes)): ?>
				<table class = "table table-sm">
					<thead>
						<tr>
							<th scope = "col">#</th>
							<th scope = "col"><?php echo sortLink('name', 'desc'); ?></th>
							<th scope = "col"><?php echo sortLink('category'); ?></th>
							<th scope = "col">Action</th>
							<th><input type = "checkbox" id = "checkAll" /></th>
						</tr>		
					</thead>
					<tbody>
						<?php $i = itemNumeration(); ?>
						<?php foreach($bikes->fetch_data() as $bike): ?>
							<tr>
								<th scope = "row"><?php echo $i; ?></th>
								<td><a href = "<?php echo ADMIN_URL; ?>/bike/edit/<?php echo $bike->bike_id; ?>"><?php echo $bike->bike_name; ?></a></td>
								<td><a href = "<?php echo ADMIN_URL; ?>/category/edit/<?php echo $bike->category_->category_id; ?>"><?php echo $bike->category_->category_name; ?></a></td>
								<td><a href = "<?php echo ADMIN_URL; ?>/bike/delete/<?php echo $bike->bike_id; ?>"><i class="fas fa-trash-alt"></i></a> <a href = "<?php echo ADMIN_URL; ?>/bike/duplicate/<?php echo $bike->bike_id; ?>"><i class="fas fa-copy"></i></a></td>
								<td><input type = "checkbox" value = "<?php echo $bike->bike_id; ?>" class = "check" /></td>
							</tr>
							<?php $i++; ?>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php echo $bikes->navigation; ?>
			<?php endif; ?>
		</div>
	</div>
</div>
<form method = "POST" enctype = "multipart/form-data" class = "form out-of-screen" name = "image_upload">
	<input type = "file" name = "image[]" id = "image" class = "file-input" multiple />		
	<input type = "hidden" name = "object_id" value = "<?php echo $category->category_id; ?>" />
	<input type = "hidden" name = "object" value = "category" />
<form>