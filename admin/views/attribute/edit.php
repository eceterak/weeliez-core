<form action = "<?php echo ADMIN_URL.'/attribute/update/'.$attribute->attribute_id; ?>" method = "POST" name = "attribute" class = "panel panel-content">
	<div class = "form-group">
		<label for = "attribute_name">Name</label>
		<input type = "text" class = "form-control" value = "<?php echo $attribute->attribute_name; ?>" name = "attribute_name" />
	</div>
	<div class = "form-group">
		<label for = "attribute_postscript">Postscript</label>
		<input type = "text" class = "form-control" value = "<?php echo $attribute->attribute_postscript; ?>" name = "attribute_postscript" />
		<small class = "text-muted">Lifestyle of rich and famous</small>
	</div>
	<div class = "form-group">
		<label for = "attribute_description">Description</label>
		<textarea class = "form-control" name = "attribute_description"><?php echo $attribute->attribute_description; ?></textarea>
	</div>	
	<div class = "form-group">
		<label for = "attribute_type_id">Attribute type</label>
		<select name = "attribute_type_id" class = "form-control">
			<?php foreach(attribute_type_::getAll() as $attribute_type): ?>
				<option value = "<?php echo $attribute_type->attribute_type_id; ?>" <?php echo ($attribute_type->attribute_type_id == $attribute->attribute_type_->attribute_type_id) ? "selected" : ""; ?>><?php echo $attribute_type->attribute_type_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class = "form-group">
		<label for = "unit_id">Unit</label>
		<select name = "unit_id" class = "form-control">
			<option value = "">None</option>
			<?php foreach(unit_::getAll() as $unit): ?>
				<option value = "<?php echo $unit->unit_id; ?>" <?php echo ($unit->unit_id == $attribute->unit_->unit_id) ? "selected" : ""; ?>><?php echo $unit->unit_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class = "form-check">
		<label for = "attribute_sub" class = "form-check-label">
			<input type = "hidden" name = "attribute_sub" value = "0" />
			<input type = "checkbox" name = "attribute_sub" value = "1" <?php echo ($attribute->attribute_sub == 1) ? 'checked' : ''; ?> class = "form-check-input" />
			Sub attribute
		</label>
	</div>		
	<div class = "form-check">		
		<label or = "attribute_search" class = "form-check-label">
			<input type = "hidden" name = "attribute_search" value = "0" />	
			<input type = "checkbox" name = "attribute_search" value = "1" <?php echo ($attribute->attribute_search == 1) ? 'checked' : ''; ?> class = "form-check-input" />
			Display on advanced search form
		</label>
	</div>
	<div class = "form-group">
		<label for = "attribute_search_method"></label>
		<select name = "attribute_search_method" class = "form-control" <?php echo ($attribute->attribute_search == 0) ? 'disabled' : ''; ?>>
			<option value = "slider" <?php echo ($attribute->attribute_search_method == 'slider') ? 'selected' : ''; ?>>Slider</option>
			<option value = "select" <?php echo ($attribute->attribute_search_method == 'select') ? 'selected' : ''; ?>>Select</option>
			<option value = "checkbox" <?php echo ($attribute->attribute_search_method == 'checkbox') ? 'selected' : ''; ?>>Checkbox</option>
		</select>
	</div>
	<input type = "hidden" name = "redirect" value = "<?php echo $_SERVER['HTTP_REFERER']; ?>" />
	<button type = "submit" class = "btn btn-primary">Save</button>
</form>