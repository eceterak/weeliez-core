<?php if(isset($attributes)): ?>
	<h3>Advanced search</h3>
	<p>Find your next bike with our advanced search feature.</p>
	<form action = "/search/advresults" method = "GET" id = "advanced-search">
		<div class = "row">
			<div class = "col-6">
				<h5>General</h5>
				<div class = "mb-3 panel panel-content-md">
					<div class = "form-group">
						<label for = "brand_id">Brand</label>
						<?php $brands = \brand_::getAll(); ?>
						<select multiple = "multiple" name = "brand_id[]" id = "brand" class = "form-control multi">
							<?php foreach($brands as $brand): ?>
								<option value = "<?php echo $brand->brand_id; ?>"><?php echo $brand->brand_name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class = "form-group">
						<label for = "category_id">Category</label>
						<?php $categories = \category_::getAll(); ?>
							<select multiple = "multiple" name = "category_id[]" id = "category" class = "form-control multi">
								<?php foreach($categories as $category): ?>
									<option value = "<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></option>
								<?php endforeach; ?>
							</select>
					</div>
					<div class = "form-group slider">
						<label for = "year">Year</label>
						<div class = "mb-2">
							<span class = "val-left">min</span>
							<span class = "val-right float-right">max</span>
						</div>
						<div class = "slider-range" style = "margin: 0px 9px;"></div>
						<input type = "hidden" name = "bike_year_start" class = "amount" readonly />
					</div>
				</div>
			</div>
			<?php foreach($attributes as $key => $value): ?>
				<div class = "col-6">
					<h5><?php echo $key; ?></h5>
					<div class = "mb-3 panel panel-content-md">
						<?php foreach($value as $attribute): ?>
							<?php switch($attribute->attribute_search_method): ?><?php case 'slider': ?>
								<div class = "form-group slider">
									<label for = "<?php echo $attribute->attribute_name; ?>"><?php echo $attribute->attribute_name; ?></label>
									<div class = "mb-2">
										<span class = "val-left">min</span>
										<span class = "val-right float-right">max</span>
									</div>
									<div class = "slider-range" style = "margin: 0px 9px;"></div>
									<input type = "hidden" name = "<?php echo $attribute->attribute_id; ?>" class = "amount" readonly />	
								</div>
								<?php break; ?>
								<?php case 'select': ?>
									<div class = "form-group">
										<label for = "<?php echo $attribute->attribute_name; ?><"><?php echo $attribute->attribute_name; ?></label>
										<?php $values = spec::getValues($attribute->attribute_id); ?>
										<select multiple = "multiple" name = "<?php echo $attribute->attribute_id.'[]'; ?><" id = "<?php echo $attribute->attribute_id; ?><" class = "form-control multi">
											<?php foreach($values as $value): ?>
												<option value = "<?php echo $value->spec_value; ?>"><?php echo $value->spec_value; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								<?php break; ?>
							<?php endswitch; ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<button role = "submit" class = "btn btn-primary">Search</button>
	</form>
<?php endif; ?>