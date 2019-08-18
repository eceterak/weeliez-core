<form action = "<?php echo ADMIN_URL.'/bike/create/'; ?>" method = "POST" name = "bike" novalidate>
	<div class = "form-group">
		<label for = "bike_name">Name</label>
		<input type = "text" name = "bike_name" class = "form-control" placeholder = "Name" required />
	</div>
	<div class = "form-group">
		<label for = "bike_year_start">Year start</label>
		<input type = "number" name = "bike_year_start" class = "form-control" placeholder = "Year start" required />
	</div>
	<div class = "form-group">
		<label for = "bike_year_end">Year end</label>
		<input type = "number" name = "bike_year_end" class = "form-control" placeholder = "Year end" />
	</div>
	<div class = "form-group">
		<label for = "bike_description">Description</label>
		<textarea name = "bike_description" class = "form-control"></textarea>
	</div>
	<div class = "form-group">
		<label for = "brand_id">brand</label>
		<select name = "brand_id" class = "form-control">
			<?php foreach(brand_::getAll() as $brand): ?>
				<option value = "<?php echo $brand->brand_id; ?>" <?php echo (!is_null($parent) && $parent == $brand->brand_id) ? 'selected' : ''; ?>><?php echo $brand->brand_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class = "form-group">
		<label for = "category_id">Type</label>
		<select name = "category_id" class = "form-control">
			<?php foreach(category_::getAll() as $category): ?>
				<option value = "<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<button role = "submit" class = "btn btn-primary">Save</button>
</form>