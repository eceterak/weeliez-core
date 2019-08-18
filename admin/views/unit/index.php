<p class = "h4 mb-3">Units<a href = "/admin/unit/add" class = "btn btn-small btn-light ml-2" role = "button">Add new <i class="fas fa-plus-circle fa-sm"></i></a></p>
<table class = "table panel">
	<thead>
		<tr>
			<th scope = "col">#</th>
			<th scope = "col"><?php sortLink('name', 'desc'); ?></th>
			<th scope = "col">Action</th>
		</tr>		
	</thead>
	<tbody>
		<?php $i = itemNumeration(); ?>
		<?php foreach($units->fetch_data() as $unit): ?>
			<tr>
				<th scope = "row"><?php echo $i; ?></th>
				<td><a href = "<?php echo ADMIN_URL.'/unit/edit/'.$unit->unit_id; ?>"><?php echo $unit->unit_name; ?></a></td>
				<td><a href = "<?php echo ADMIN_URL.'/unit/delete/'.$unit->unit_id; ?>"><i class="fas fa-trash-alt"></i></a></td>
			</tr>
			<?php $i++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php $units->navigation; ?>