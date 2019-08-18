<p class = "h4 mb-3">Roles<a href = "/admin/access/add" class = "btn btn-small btn-light ml-2" role = "button">Add new <i class="fas fa-plus-circle fa-sm"></i></a></p>
<?php if($access): ?>
	<table class = "table panel">
		<thead>
			<tr>
				<th scope = "col">#</th>
				<th scope = "col"><?php sortLink('name'); ?></th>
				<th scope = "col"><?php sortLink('level', 'asc'); ?></th>
				<th scope = "col">Action</th>
			</tr>		
		</thead>
		<tbody>
			<?php $i = itemNumeration(); ?>
			<?php foreach($access->fetch_data() as $acc): ?>
				<tr>
					<th scope = "row"><?php echo $i; ?></th>
					<td><a href = "<?php echo ADMIN_URL.'/access/edit/'.$acc->access_id; ?>"><?php echo $acc->access_name; ?></a></td>
					<td><?php echo $acc->access_level; ?></td>
					<td><a href = "<?php echo ADMIN_URL.'/access/delete/'.$acc->access_id; ?>"><i class="fas fa-trash-alt"></i></a></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>	
<?php else: ?>
<?php endif; ?>