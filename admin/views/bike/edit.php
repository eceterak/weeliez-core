<p class = "h4 mb-3">Edit bike</p>
<form action = "<?php echo ADMIN_URL.'/bike/update/'.$bike->bike_id; ?>" name = "bike_edit_form" method = "POST" name = "edit" novalidate>
	<div class = "row item-header mb-3">
		<div class = "form-group col-6">
			<input type = "text" name = "bike_name" id = "bike_name" value = "<?php echo $bike->bike_name; ?>" class = "form-control form-control-lg" placeholder = "Name" required />
		</div>
		<div class = "form-group row col-2">
			<select name = "bike_sale" class = "form-control mt-1">
				<option value = "std" <?php echo ($bike->bike_sale == 'std') ? 'selected' : ''; ?>>Standard</option>
				<option value = "1" <?php echo ($bike->bike_sale == 1) ? 'selected' : ''; ?>>On sale</option>
				<option value = "archive" <?php echo ($bike->bike_sale == 'archive') ? 'selected' : ''; ?>>Archive</option>
			</select>
		</div>
		<div class = "col-3">
			<span><i class="fas fa-heart mr-2 cl-heart"></i><?php echo $bike->bike_loves; ?></span>
			<span class = "ml-2"><i class="fas fa-star mr-2 cl-gold"></i><?php echo $bike->bike_favourites; ?></span>
		</div>
	</div>
	<div class = "tabs fixed-bottom-separator">
		<ul>
			<li><a href = "#tab-1">General</a></li>
			<li><a href = "#tab-2">Images</a></li>
			<li><a href = "#tab-3">Specs</a></li>
			<li><a href = "#tab-1">Related</a></li>
		</ul>
		<div class = "panel panel-content">
			<div id = "tab-1">
				<div class = "row">
					<div class = "form-group col-6">
						<label for = "bike_year_start">Year start</label>
						<input type = "number" value = "<?php echo $bike->bike_year_start; ?>" name = "bike_year_start" class = "form-control" placeholder = "Year start" required />
					</div>
					<div class = "form-group col-6">
						<label for = "bike_year_end">Year end</label>
						<input type = "number" value = "<?php echo $bike->bike_year_end; ?>" name = "bike_year_end" class = "form-control" placeholder = "Year end" <?php echo ($bike->bike_sale == 1) ? 'disabled' : ''; ?> />
					</div>
				</div>
				<div class = "row">
					<div class = "form-group col-6">
						<label for = "brand_id">Brand</label>
						<select name = "brand_id" class = "form-control">
							<?php foreach(brand_::getAll() as $brand): ?>
								<option value = "<?php echo $brand->brand_id; ?>" <?php echo ($brand->brand_id == $bike->brand_->brand_id) ? "selected" : ""; ?>><?php echo $brand->brand_name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class = "form-group col-6">
						<label for = "category_id">Category</label>
						<select name = "category_id" class = "form-control">
							<?php foreach(category_::getAll() as $category): ?>
								<option value = "<?php echo $category->category_id; ?>" <?php echo ($category->category_id == $bike->category_->category_id) ? "selected" : ""; ?>><?php echo $category->category_name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class = "form-group">
					<label for = "bike_description">Description</label>
					<textarea name = "bike_description" class = "form-control"><?php echo $bike->bike_description; ?></textarea>
				</div>
				<input type = "hidden" name = "redirect" value = "<?php echo $_SERVER['HTTP_REFERER']; ?>" />
			</div>
			<div id = "tab-2" class = "hide">
				<div class = "alert alert-img" role = "alert"></div>
				<span class = "ui-helper-hidden-accessible"><input type = "text" /></span>
				<div class = "row img-base">
					<div class = "upload-box col-2 d-flex justify-content-center align-items-center">
						<div id = "file-upload" class = "upload-new align-text-bottom"><i class="fas fa-plus-circle fa-3x point"></i></div>
					</div>
					<?php if(!empty($bike->images_)): ?>
						<?php foreach($bike->images_->images as $image): ?>
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
				<table id = "table" class = "table table-sm no-border">
					<?php foreach(attribute_type_::getAll() as $attribute_type): ?>
						<tbody class = "group">
							<tr>
								<th colspan = "100"><?php echo $attribute_type->attribute_type_name; ?></th>
							</tr>
							<?php if(isset($bike->specs_[$attribute_type->attribute_type_name])): ?>
								<?php foreach($bike->specs_[$attribute_type->attribute_type_name] as $spec): ?>
									<tr>
										<td class = "attribute_name"><?php echo $spec->attribute_->attribute_name; ?></td>
										<td>
											<input type = "text" class = "form-control" value = "<?php echo $spec->spec_value; ?>" name = "<?php echo 'spec_'.$spec->attribute_->attribute_id; ?>" />
										</td>
										<?php if($spec->attribute_->attribute_sub == 1): ?>
											<td class = "text-center">@</td>
											<td>
												<input type = "text" class = "form-control" value = "<?php echo $spec->spec_sub; ?>" name = "<?php echo 'spec_'.$spec->attribute_->attribute_id.'_sub'; ?>" />
											</td>							
										<?php endif; ?>
										<td>
											<button type = "button" class = "btn btn-link spec-delete del p-0" value = "<?php echo $spec->spec_id; ?>"><i class = "fa fa-times" aria-hidden = "true"></i></button>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
							<?php $attributes = (!empty($bike->specs_) && isset($bike->specs_[$attribute_type->attribute_type_name])) ? attribute_::getAll($attribute_type->attribute_type_id, $bike->specs_[$attribute_type->attribute_type_name]) : attribute_::getAll($attribute_type->attribute_type_id) ; ?>
							<?php if(!empty($attributes)): ?>
								<tr class = "addRow">
									<td></td>
									<td>
										<select class = "attribute form-control select2">
											<?php foreach($attributes as $attribute): ?>
												<option value = "<?php echo $attribute->attribute_id; ?>"><?php echo $attribute->attribute_name; ?></option>
											<?php endforeach; ?>
										</select>
									</td>
									<td><span class = "icon-click add"><i class="fas fa-plus"></i></span></td>
								</tr>
							<?php else: ?>
								<tr><td>All <?php echo $key; ?> attributes used</td></tr>
							<?php endif; ?>
						</tbody>			
					<?php endforeach; ?>
				</table>
			</div>
		</div>
	</div>
	<div class = "fixed-bottom fixed-offset panel panel-bottom">
		<a href = "<?php echo ADMIN_URL; ?>/bike/delete/<?php echo $bike->bike_id; ?>"><i class="fas fa-trash-alt fa-lg"></i></a>
		<a href = "<?php echo ADMIN_URL; ?>/bike/duplicate/<?php echo $bike->bike_id; ?>"><i class="fas fa-copy fa-lg ml-2"></i></a>
		<a href = "/bike/specs/<?php echo $bike->bike_path; ?>" class = "btn btn-secondary ml-3">Preview</a>
		<button class = "btn btn-secondary ml-3 copy-specs" type = "button">Copy</button>
		<div class = "float-right">
			<a href = "/admin/bike/add" class = "btn btn-light mr-2"">Add new</a>
			<button type = "submit" class = "btn btn-primary">Save</button>
		</div>
	</div>
</form>
<div class = "dialog copy-specs-dialog">
	<form name = "copy-specs">
		<button type="button" class="close point dialog-close" aria-label="Close">
  			<span aria-hidden="true">&times;</span>
		</button>
		<div class = "form-group">
			<label class = "mt-2 mb-2">bikez.com</label>
			<input type = "text" name = "bikez" class = "form-control" autofocus />
		</div>
		<div class = "form-group">
			<label>motorcyclespecs.co.za</label>
			<input type = "text" name = "motorcyclespecs" class = "form-control" />
		</div>
		<input type = "hidden" name = "bike_id" value = "<?php echo $bike->getId(); ?>" />
		<button type = "submit" class = "btn btn-primary">Save</button>
	</form>
</div>
<form method = "POST" enctype = "multipart/form-data" class = "form out-of-screen" name = "image_upload">
	<input type = "file" name = "image[]" id = "image" class = "file-input" multiple />		
	<input type = "hidden" name = "object_id" value = "<?php echo $bike->bike_id; ?>" />
	<input type = "hidden" name = "object" value = "bike" />
<form>