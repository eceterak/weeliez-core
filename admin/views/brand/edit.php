<p class = "h4 mb-3">Edit brand</p>
<div class = "tabs">
	<ul>
		<li><a href = "#tab-1">General</a></li>
		<li><a href = "#tab-2">Images</a></li>
		<li><a href = "#tab-3">Bikes</a></li>
	</ul>
	<div class = "panel panel-content">
		<div id = "tab-1">
			<form action = "<?php echo ADMIN_URL.'/brand/update/'.$brand->brand_id; ?>" method = "POST">
				<div class = "form-group">
					<label>Name</label>
					<input type = "text" class = "form-control" value = "<?php echo $brand->brand_name; ?>" name = "brand_name" required />
				</div>
				<div class = "form-group">
					<label>Year</label>
					<input type = "text" class = "form-control" value = "<?php echo $brand->brand_year; ?>" name = "brand_year" />
				</div>
				<div class = "form-group">
					<label>Founder</label>
					<input type = "text" class = "form-control" value = "<?php echo $brand->brand_founder; ?>" name = "brand_founder" />
				</div>
				<div class = "form-group">
					<label>Headquarters</label>
					<input type = "text" class = "form-control" value = "<?php echo $brand->brand_headquarters; ?>" name = "brand_headquarters" />
				</div>
				<div class = "form-group">
					<label>History</label>
					<textarea name = "brand_description"><?php echo $brand->brand_description; ?></textarea>
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
					<?php if(isset($brand->images_)): ?>
						<?php foreach($brand->images_->images as $image): ?>
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
		<div id = "tab-3" class = "hide">
			<div class = "mb-3">
				<a href = "/admin/bike/add/<?php echo $brand->brand_id; ?>" class = "btn btn-small btn-light">Add new <i class="fas fa-plus-circle fa-sm"></i></a>
				<button class = "btn btn-small btn-light ml-2 quick-add">Quick add <i class="fas fa-plus-circle fa-sm"></i></button>
				<button class = "btn btn-small btn-light ml-2 bikez-copy">Copy <i class="fas fa-plus-circle fa-sm"></i></button>
			</div>
			<?php if(isset($bikes)): ?>
				<table class = "table table-sm mb-0">
					<thead>
						<tr>
							<th scope = "col">#</th>
							<th scope = "col"><?php echo sortLink('name', 'desc'); ?></th>
							<th scope = "col"><?php echo sortLink('year'); ?></th>
							<th scope = "col"><?php echo sortLink('category'); ?></th>
							<th scope = "col">Action</th>
							<th><input type = "checkbox" id = "checkAll" /></th>
						</tr>		
					</thead>
					<tbody>
						<?php $i = itemNumeration(); ?>
						<?php foreach($bikes->fetch_data() as $bike): ?>
							<tr class = "bike">
								<th scope = "row"><?php echo $i; ?></th>
								<td><a href = "<?php echo ADMIN_URL; ?>/bike/edit/<?php echo $bike->bike_id; ?>"><?php echo $bike->bike_name; ?></a></td>
								<td><?php echo $bike->getYear(); ?></td>
								<td>
									<select name = "qe_category_id" id = "qe_category_id" class = "form-control auto-height">
										<?php foreach(category_::getAll() as $category): ?>
										<option value = "<?php echo $category->category_id; ?>" <?php echo ($category->category_id == $bike->category_->category_id) ? "selected" : ""; ?>><?php echo $category->category_name; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
								<td><a href = "<?php echo ADMIN_URL; ?>/bike/delete/<?php echo $bike->bike_id; ?>"><i class="fas fa-trash-alt"></i></a> <a href = "<?php echo ADMIN_URL; ?>/bike/duplicate/<?php echo $bike->bike_id; ?>"><i class="fas fa-copy"></i></a></td>
								<td><input type = "checkbox" value = "<?php echo $bike->bike_id; ?>" class = "check" /></td>
								<input type = "hidden" name = "qe_bike_id" value = "<?php echo $bike->bike_id; ?>" />
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
	<input type = "hidden" name = "object_id" value = "<?php echo $brand->brand_id; ?>" />
	<input type = "hidden" name = "object" value = "brand" />
</form>
<div class = "dialog quick-add-dialog">
	<form name = "quick-add">
		<button type="button" class="close point dialog-close h6" aria-label="Close">
  			<span aria-hidden="true">&times;</span>
		</button>
		<h5 class = "mb-3">Quick add</h5>
		<div class = "row">
			<div class = "form-group col-8">
				<input type = "text" name = "bike_name" id = "bike_name" class = "form-control form-control-lg" placeholder = "Name" required />
			</div>
			<div class = "form-group col-4">
				<select name = "bike_sale" class = "form-control">
					<option value = "std">Standard</option>
					<option value = "1">On sale</option>
					<option value = "archive">Archive</option>
				</select>
			</div>
		</div>
		<div class = "row">
			<div class = "form-group col-6">
				<label for = "bike_year_start">Year start</label>
				<input type = "number" name = "bike_year_start" class = "form-control" placeholder = "Year start" required />
			</div>
			<div class = "form-group col-6">
				<label for = "bike_year_end">Year end</label>
				<input type = "number" name = "bike_year_end" class = "form-control" placeholder = "Year end" />
			</div>
			<div class = "form-group col-6">
				<label for = "brand_id">Brand</label>
				<select name = "brand_id" class = "form-control select2">
						<option value = "<?php echo $brand->brand_id; ?>"><?php echo $brand->brand_name; ?></option>
				</select>
			</div>
			<div class = "form-group col-6">
				<label for = "category_id">Category</label>
				<select name = "category_id" class = "form-control select2">
					<?php foreach(category_::getAll() as $category): ?>
						<option value = "<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class = "form-group">
			<label for = "image_url">Image</label>
			<input type = "text" name = "image_url" id = "image_url" class = "form-control form-control-lg" placeholder = "Image url" />
		</div>
		<div class = "row">
			<div class = "form-group col-6">
				<label for = "bikez">bikez</label>
				<input type = "text" name = "bikez" id = "bikez" class = "form-control form-control-lg" placeholder = "bikez" />
			</div>
			<div class = "form-group col-6">
				<label for = "motorcyclespecs">motorcyclespecs</label>
				<input type = "text" name = "motorcyclespecs" id = "motorcyclespecs" class = "form-control form-control-lg" placeholder = "motorcyclespecs" />
			</div>
		</div>
		<button role = "submit" class = "btn btn-primary">Save</button>
		<div class = "d-none alert alert-success mt-3 mb-0 text-center"><button type = "button" class = "btn btn-link mb-1 ml-2 btn-reload"><i class="fas fa-sync-alt"></i></button></div>
	</form>
</div>
<div class = "dialog bikez-copy-dialog">
	<form name = "bikez-copy">
		<button type="button" class="close point dialog-close h6" aria-label="Close">
  			<span aria-hidden="true">&times;</span>
		</button>
		<div class = "form-group">
			<label for = "bikez">bikez</label>
			<input type = "text" name = "copy-bikez" id = "copy-bikez" class = "form-control form-control-lg" placeholder = "bikez" />
		</div>
		<input type = "hidden" name = "brand_id" value = "<?php echo $brand->brand_id; ?>">
		<button role = "submit" class = "btn btn-primary">Save</button>
		<div class = "d-none alert alert-success mt-3 mb-0 text-center"><button type = "button" class = "btn btn-link mb-1 ml-2 btn-reload"><i class="fas fa-sync-alt"></i></button></div>
	</form>
</div>