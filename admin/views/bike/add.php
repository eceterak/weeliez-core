<p class = "h4 mb-3">New bike</p>
<form action = "/admin/bike/create/" name = "bike_add_form" method = "POST" novalidate>
	<div class = "row">
		<div class = "form-group col-6">
			<input type = "text" name = "bike_name" id = "bike_name" class = "form-control form-control-lg" placeholder = "Name" required />
		</div>
		<div class = "form-group col-2">
			<select name = "bike_sale" class = "form-control mt-1">
				<option value = "std">Standard</option>
				<option value = "1">On sale</option>
				<option value = "archive">Archive</option>
			</select>
		</div>
	</div>
	<div class = "panel panel-content">
		<div class = "row">
			<div class = "form-group col-6">
				<label for = "bike_year_start">Year start</label>
				<input type = "number" name = "bike_year_start" class = "form-control" placeholder = "Year start" required />
			</div>
			<div class = "form-group col-6">
				<label for = "bike_year_end">Year end</label>
				<input type = "number" name = "bike_year_end" class = "form-control" placeholder = "Year end" />
			</div>
		</div>
		<div class = "row">
			<div class = "form-group col-6">
				<label for = "brand_id">Brand</label>
				<select name = "brand_id" class = "form-control">
					<?php foreach(brand_::getAll() as $brand): ?>
						<option value = "<?php echo $brand->brand_id; ?>" <?php echo ($parent == $brand->brand_id) ? 'selected' : '' ;?>><?php echo $brand->brand_name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class = "form-group col-6">
				<label for = "category_id">Category</label>
				<select name = "category_id" class = "form-control">
					<?php foreach(category_::getAll() as $category): ?>
						<option value = "<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class = "form-group">
			<label for = "bike_description">Description</label>
			<textarea name = "bike_description" class = "form-control"></textarea>
		</div>
		<button role = "submit" class = "btn btn-primary">Save</button>
	</div>
</form>