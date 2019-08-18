<table class = "table table-striped">
	<?php foreach($attributes->fetch_data() as $attribute): ?>
		<tr>
			<td>
				<a href = "<?php echo ADMIN_URL.'/attribute/edit/'.$attribute->attribute_id; ?>">
					<?php echo $attribute->attribute_name; ?>
				</a>
			</td>
			<td><a href = "<?php echo ADMIN_URL.'/attribute/delete/'.$attribute->attribute_id; ?>">DELETE</td>
		</tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>