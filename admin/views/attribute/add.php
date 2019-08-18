<form action = "/admin/attribute/create/" method = "POST" name = "attribute" class = "panel panel-content">
	<div class = "form-group">
		<label for = "attribute_name">Name</label>
		<input type = "text" class = "form-control" name = "attribute_name" />
	</div>
	<div class = "form-group">
		<label for = "attribute_postscript">Postscript</label>
		<input type = "text" class = "form-control" />
		<small class = "text-muted">Lifestyle of rich and famous</small>
	</div>
	<div class = "form-group">
		<label for = "attribute_description">Description</label>
		<textarea class = "form-control" name = "attribute_description"></textarea>
	</div>	
	<div class = "form-group">
		<label for = "attribute_type_id">Attribute type</label>
		<select name = "attribute_type_id" class = "form-control">
			<?php foreach(attribute_type_::getAll() as $attribute_type): ?>
				<option value = "<?php echo $attribute_type->attribute_type_id; ?>" <?php echo (isset($parent) && $parent == $attribute_type->attribute_type_id) ? 'selected' : ''; ?>><?php echo $attribute_type->attribute_type_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class = "form-group">
		<label for = "unit_id">Unit</label>
		<select name = "unit_id" class = "form-control">
			<option value = "0">None</option>
			<?php foreach(unit_::getAll() as $unit): ?>
				<option value = "<?php echo $unit->unit_id; ?>"><?php echo $unit->unit_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class = "form-check">
		<label for = "attribute_sub" class = "form-check-label">
			<input type = "hidden" name = "attribute_sub" value = "0" />
			<input type = "checkbox" name = "attribute_sub" value = "1" class = "form-check-input" />
			Sub attribute
		</label>
	</div>		
	<div class = "form-check">		
		<label or = "attribute_search" class = "form-check-label">
			<input type = "hidden" name = "attribute_search" value = "0" />	
			<input type = "checkbox" name = "attribute_search" value = "1" class = "form-check-input" />
			Display on advanced search form
		</label>
	</div>
	<div class = "form-group">
		<label for = "attribute_search_method"></label>
		<select name = "attribute_search_method" class = "form-control" disabled>
			<option value = "slider">Slider</option>
			<option value = "select">Select</option>
			<option value = "checkbox">Checkbox</option>
		</select>
	</div>
	<input type = "hidden" name = "attribute_type_id" value = "<?php echo $parent; ?>" />
	<input type = "hidden" name = "redirect" value = "<?php echo $_SERVER['HTTP_REFERER']; ?>" />
	<button type = "submit" class = "btn btn-primary">Add</button>
</form>