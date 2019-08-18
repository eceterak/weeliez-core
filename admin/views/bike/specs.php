<?php bike_::menu($bike->bike_id); ?>
<h3><?php echo $bike->bike_name; ?></h3>
<form action = "<?php echo ADMIN_URL.'/bike/updateSpecs/'.$bike->bike_id; ?>" method = "POST" id = "form" name = "bikeSpecs" novalidate>
	<table id = "table" class = "table table-sm">
		<?php foreach(attribute_type_::getAll() as $attribute_type): ?>
		<tbody class = "group">
			<tr>
				<th colspan = "100"><?php echo $attribute_type->attribute_type_name; ?></th>
			</tr>
			<?php if(isset($bike->specs_[$attribute_type->attribute_type_name])): ?>
				<?php foreach($bike->specs_[$attribute_type->attribute_type_name] as $spec): ?>
					<tr>
						<td><?php echo $spec->attribute_->attribute_name; ?></td>
						<td>
							<input type = "text" class = "form-control" value = "<?php echo $spec->spec_value; ?>" name = "<?php echo $spec->attribute_->attribute_id; ?>" />
						</td>
						<?php if($spec->attribute_->attribute_sub == 1): ?>
							<td class = "text-center">@</td>
							<td>
								<input type = "text" class = "form-control" value = "<?php echo $spec->spec_sub; ?>" name = "<?php echo $spec->attribute_->attribute_id.'_sub'; ?>" />
							</td>							
						<?php endif; ?>
						<td>
							<button type = "button" class = "btn btn-link spec-delete" value = "<?php echo $spec->spec_id; ?>"><i class = "fa fa-times" aria-hidden = "true"></i></button>
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
							<?php foreach($attributes->fetch_data() as $attribute): ?>
								<option value = "<?php echo $attribute->attribute_id; ?>"><?php echo $attribute->attribute_name; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td><span class = "icon-click add ml-1"><i class="fas fa-plus"></i></span></td>
				</tr>
			<?php else: ?>
				<tr><td>All <?php echo $key; ?> attributes used</td></tr>
			<?php endif; ?>
		</tbody>			
		<?php endforeach; ?>
		<tr><td><button role = "submit" class = "btn btn-primary">Save</button></td></tr>
	</table>
</form>