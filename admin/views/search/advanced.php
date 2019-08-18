<form action = "<?php echo ADMIN_URL; ?>/search/advresults" method = "GET">	
	
	<div class = "form-group">
		<label for = "capacity">Capacity</label>
		<div id = "slider"></div>
		<input type = "hidden" name = "attribute_id" id = "capacity" readonly />
	</div>
	<div class = "form-group">
		<label for = "brand_id">Brand</label>
		<?php $brands = \brand_::getAll(); ?>
		<select multiple = "multiple" name = "brand_id[]" id = "brand" class = "form-control">
			<?php foreach($brands->fetch_data() as $brand): ?>
				<option value = "<?php echo $brand->brand_id; ?>"><?php echo $brand->brand_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class = "form-group">
		<label for = "category_id">Category</label>
		<?php $categories = \category_::getAll(); ?>
		<select multiple = "multiple" name = "category_id[]" id = "category" class = "form-control">
			<?php foreach($categories->fetch_data() as $category): ?>
				<option value = "<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<button role = "submit" class = "btn btn-primary">Save</button>
</form>