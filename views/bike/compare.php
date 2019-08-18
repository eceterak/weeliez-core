<div class = "ui-widget">
	<label for = "bike-compare">Select bike to compare</label>
	<input type = "text" id = "bike-compare" class = "autocomplete-compare" />
</div>
<?php if(!isset($bike_compare)): ?>
	<table class = "panel table table-striped table-sm">
		<thead>
			<tr>
				<th>Name</th>
				<td><?php echo $bike->bike_name; ?></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>Brand</th>
				<td><?php echo $bike->brand_->brand_name; ?></td>
			</tr>
			<tr>
				<th>Year</th>
				<td><?php echo $bike->getYear(); ?></td>
			</tr>
			<tr>
				<th>Category</th>
				<td><?php echo $bike->category_->category_name; ?></td>
			</tr>
			<?php if(isset($attributes)): ?>
				<?php foreach($attributes as $key => $value): ?>
					<tr>
						<th colspan = "3"><?php echo $key; ?></th>
					</tr>
					<?php foreach($value as $attribute): ?>
						<tr>
							<td><?php echo $attribute->attribute_name; ?></td>
							<td><?php echo (isset($bike->specs_[$key][$attribute->attribute_name])) ? $bike->specs_[$key][$attribute->attribute_name]->spec_value : ''; ?></td>
						</tr>			
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
<?php else: ?>
	<table class = "panel table table-striped table-sm">
		<thead>
			<tr>
				<th>Name</th>
				<td><?php echo $bike->bike_name; ?></td>
				<td><?php echo $bike_compare->bike_name; ?></td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>Brand</th>
				<td><?php echo $bike->brand_->brand_name; ?></td>
				<td><?php echo $bike_compare->brand_->brand_name; ?></td>
			</tr>
			<tr>
				<th>Year</th>
				<td><?php echo $bike->getYear(); ?></td>
				<td><?php echo $bike_compare->getYear(); ?></td>
			</tr>
			<tr>
				<th>Category</th>
				<td><?php echo $bike->category_->category_name; ?></td>
				<td><?php echo $bike_compare->category_->category_name; ?></td>
			</tr>
			<?php if(isset($attributes)): ?>
				<?php foreach($attributes as $key => $value): ?>
					<tr>
						<th colspan = "3"><?php echo $key; ?></th>
					</tr>
					<?php foreach($value as $attribute): ?>
						<tr>
							<td><?php echo $attribute->attribute_name; ?></td>
							<td><?php echo (isset($bike->specs_[$key][$attribute->attribute_name])) ? $bike->specs_[$key][$attribute->attribute_name]->getDetails() : ''; ?></td>
							<td><?php echo (isset($bike_compare->specs_[$key][$attribute->attribute_name])) ? $bike_compare->specs_[$key][$attribute->attribute_name]->getDetails() : ''; ?></td>
						</tr>			
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
<?php endif; ?>