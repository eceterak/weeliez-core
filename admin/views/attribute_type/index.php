<p class = "h4 mb-3">Attributes<a href = "/admin/attribute_type/add" class = "btn btn-small btn-light ml-2" role = "button">Add new <i class="fas fa-plus-circle fa-sm"></i></a></p>
<table class = "table panel">
	<thead>
		<tr>
			<th scope = "col">#</th>
			<th scope = "col"><?php sortLink('name', 'desc'); ?></th>
			<th scope = "col">Priority</th>
			<th scope = "col">Action</th>
		</tr>		
	</thead>
	<tbody>
		<?php $i = itemNumeration(); ?>
		<?php foreach($attribute_types->fetch_data() as $attribute): ?>
			<tr>
				<th scope = "row"><?php echo $i; ?></th>
				<td>
					<a href = "<?php echo ADMIN_URL.'/attribute_type/edit/'.$attribute->attribute_type_id; ?>">
						<?php echo $attribute->attribute_type_name; ?>
					</a>
				</td>
				<td>
					<a href = "<?php echo ADMIN_URL.'/attribute_type/priority/'.$attribute->attribute_type_id.'?direction=up'; ?>">
						<i class="fa fa-arrow-up point" aria-hidden="true"></i>
					</a>
					<a href = "<?php echo ADMIN_URL.'/attribute_type/priority/'.$attribute->attribute_type_id.'?direction=down'; ?>">
						<i class="fa fa-arrow-down point" aria-hidden="true"></i>
					</a>
				</td>
				<td><a href = "<?php echo ADMIN_URL.'/attribute_type/delete/'.$attribute->attribute_type_id; ?>"><i class="fas fa-trash-alt"></i></a></td>
			</tr>
			<?php $i++; ?>
		<?php endforeach; ?>
	</tbody>	
</table>