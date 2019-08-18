<p class = "h4 mb-3">Edit attribute</p>
<form action = "<?php echo ADMIN_URL.'/attribute_type/update/'.$attribute_type->attribute_type_id; ?>" method = "POST" class = "panel panel-content">
	<div class = "form-group">
		<label for = "attribute_type_name">Name</label>
		<input type = "text" class = "form-control" value = "<?php echo $attribute_type->attribute_type_name; ?>" name = "attribute_type_name" /></div>
		<button type = "submit" class = "btn btn-primary">Save</button>
</form>
<?php if(isset($notice)): echo $notice; ?>
<?php else: ?>
	<p class = "h4 mt-4 mb-3">Attributes<a href = "/admin/attribute/add/<?php echo $attribute_type->attribute_type_id; ?>" class = "btn btn-small btn-light ml-2" role = "button">Add new <i class="fas fa-plus-circle fa-sm"></i></a></p>	
	<table class = "table table-sm panel">
		<thead>
			<tr>
				<th scope = "col">#</th>
				<th scope = "col">Name</th>
				<th scope = "col">Priority</th>
				<th scope = "col">Action</th>
				<th scope = "col">Move</th>
			</tr>		
		</thead>
		<tbody class = "sortable">
			<?php $i = 1; ?>
			<?php foreach($attributes->fetch_data() as $attribute): ?>
				<tr id = "item_<?php echo $attribute->attribute_id; ?>">
					<th scope = "row"><?php echo $i; ?></th>
					<td>
						<a href = "<?php echo ADMIN_URL.'/attribute/edit/'.$attribute->attribute_id; ?>"><?php echo $attribute->attribute_name; ?></a>
					</td>
					<td>
						<a href = "<?php echo ADMIN_URL.'/attribute/priority/'.$attribute->attribute_id.'?direction=up'; ?>">
							<i class="fa fa-arrow-up point" aria-hidden="true"></i>
						</a>
						<a href = "<?php echo ADMIN_URL.'/attribute/priority/'.$attribute->attribute_id.'?direction=down'; ?>">
							<i class="fa fa-arrow-down point" aria-hidden="true"></i>
						</a>
					</td>
					<td><a href = "<?php echo ADMIN_URL; ?>/attribute/delete/<?php echo $attribute->attribute_id; ?>"><i class="fas fa-trash-alt"></i></a></td>
					<td><i class = "fas fa-arrows-alt-v grab" aria-hidden = "true"></i></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
