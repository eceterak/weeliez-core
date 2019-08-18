<p class = "h4 mb-3">New users</p>
<table class = "table table-sm panel">
	<thead>
		<tr>
			<th scope = "col">#</th>
			<th scope = "col"><?php sortLink('name'); ?></th>
			<th scope = "col"><?php sortLink('email'); ?></th>
			<th scope = "col"><?php sortLink('level', 'asc'); ?></th>
			<th scope = "col">Action</th>
		</tr>		
	</thead>
	<tbody>
		<?php $i = itemNumeration(); ?>
		<?php foreach($users->fetch_data() as $user): ?>
			<tr>
				<th scope = "row"><?php echo $i; ?></th>
				<td><a href = "<?php echo ADMIN_URL.'/user/edit/'.$user->user_id; ?>"><?php echo $user->user_name; ?></a></td>
				<td><a href = "<?php echo ADMIN_URL.'/user/edit/'.$user->user_id; ?>"><?php echo $user->user_email; ?></a></td>
				<td><?php echo $user->access_->access_name; ?></td>
				<td><a href = "<?php echo ADMIN_URL.'/user/delete/'.$user->user_id; ?>"><i class="fas fa-trash-alt"></i></a></td>
			</tr>
			<?php $i++; ?>
		<?php endforeach; ?>
	</tbody>
</table>