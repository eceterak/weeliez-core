<p class = "h4 mb-3">Brands<a href = "/admin/brand/add" class = "btn btn-small btn-light ml-2" role = "button">Add new <i class="fas fa-plus-circle fa-sm"></i></a></p>
<table class = "table panel">
	<thead>
		<tr>
			<th scope = "col">#</th>
			<th scope = "col"><?php sortLink('name', 'desc'); ?></th>
			<th scope = "col"><?php sortLink('year'); ?></th>
			<th scope = "col"><?php sortLink('bikes'); ?></th>
			<th scope = "col">Action</th>
		</tr>		
	</thead>
	<tbody>
		<?php $i = itemNumeration(); ?>
		<?php foreach($brands->fetch_data() as $brand): ?>
			<tr>
				<th scope = "row"><?php echo $i; ?></th>
				<td><a href = "<?php echo ADMIN_URL; ?>/brand/edit/<?php echo $brand->brand_id; ?>"><?php echo $brand->brand_name; ?></a></td>
				<td><?php echo $brand->brand_year; ?></td>
				<td><?php echo '['.$brand->bikesAmount.']'; ?></td>
				<td><a href = "<?php echo ADMIN_URL; ?>/brand/delete/<?php echo $brand->brand_id; ?>"><i class="fas fa-trash-alt"></i></a></td>
			</tr>
			<?php $i++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo $brands->navigation; ?>