<p class = "h4 mb-3">Categories<a href = "/admin/category/add" class = "btn btn-small btn-light ml-2" role = "button">Add new <i class="fas fa-plus-circle fa-sm"></i></a></p>
<table class = "table panel">
	<thead>
		<tr>
			<th scope = "col">#</th>
			<th scope = "col"><?php sortLink('name', 'desc'); ?></th>
			<th scope = "col"><?php sortLink('bikes'); ?></th>
			<th scope = "col">Action</th>
		</tr>		
	</thead>
	<tbody>
		<?php $i = itemNumeration(); ?>
		<?php foreach($categories->fetch_data() as $category): ?>
			<tr>
				<th scope = "row"><?php echo $i; ?></th>
				<td><a href = "<?php echo ADMIN_URL; ?>/category/edit/<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></a></td>
				<td><?php echo '['.$category->bikesAmount.']'; ?></td>
				<td><a href = "<?php echo ADMIN_URL; ?>/category/delete/<?php echo $category->category_id; ?>"><i class="fas fa-trash-alt"></i></a></td>
			</tr>
			<?php $i++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php echo $categories->navigation; ?>