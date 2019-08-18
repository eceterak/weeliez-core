<?php bike_::menu($bike->bike_id); ?>
<?php if(isset($notice)): ?>
	<div class = "alert alert-warning" role = "alert"><?php echo $notice; ?></div>
<?php else: ?>
	<?php foreach($images as $image): ?>
		<a href = "/upload/images/<?php echo $image->image_url; ?>">
			<img src = "/upload/images/<?php echo $image->image_url; ?>" style = "width: 200px; height: auto;" <?php echo ($image->image_default !== 0) ? 'class = "image_default"' : '' ; ?> />
		</a>
	<?php endforeach;?>
	<?php foreach($images as $image): ?>
		<table>
			<thead>
				<tr>
					<th>Number</th>
					<th>Url</th>
					<th>Default</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $image->image_number; ?></td>
					<td><?php echo $image->image_url; ?></td>
					<td><?php echo $image->image_default; ?></td>
					<td>
						<a href = "<?php echo ADMIN_URL.'/image/delete/'.$image->image_id; ?>">Delete</a> | 
						<a href = "<?php echo ADMIN_URL.'/image/def/'.$image->image_id; ?>">Default</a>
					</td>
				</tr>
			</tbody>
		</table>
	<?php endforeach; ?>
	<?php $pagination->navigation(); ?>
<?php endif; ?>
<form action = "<?php echo ADMIN_URL.'/image/upload/'; ?>" method = "POST" enctype = "multipart/form-data" class = "form">
	<input type = "file" name = "image[]" class = "file_input" multiple /><br />
	<input type = "hidden" name = "id" value="<?php echo $id; ?>" />
	<input type = "hidden" name = "object" value="<?php echo $object; ?>" />
	<input type = "submit" />
</form>