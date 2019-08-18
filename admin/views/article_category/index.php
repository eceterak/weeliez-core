<p class = "h4 mb-3">Article categories<a href = "/admin/article_category/add" class = "btn btn-small btn-light ml-2" role = "button">Add new <i class="fas fa-plus-circle fa-sm"></i></a></p>
<table class = "table table panel">
	<thead>
		<tr>
			<th scope = "col">#</th>
			<th scope = "col"><?php sortLink('name', 'desc'); ?></th>
			<th scope = "col">Action</th>
			<th><input type = "checkbox" id = "checkAll" /></th>
		</tr>		
	</thead>
	<tbody>
		<?php $i = itemNumeration(); ?>
		<?php foreach($categories->fetch_data() as $category): ?>
			<tr>
				<th scope = "row"><?php echo $i; ?></th>
				<td><a href = "<?php echo ADMIN_URL; ?>/article_category/edit/<?php echo $category->article_category_id; ?>"><?php echo $category->article_category_name; ?></a></td>
				<td><a href = "<?php echo ADMIN_URL; ?>/article_category/delete/<?php echo $category->article_category_id; ?>"><i class="fas fa-trash-alt"></i></a></td>
				<td><input type = "checkbox" value = "<?php echo $category->article_category_id; ?>" class = "check" /></td>
			</tr>
			<?php $i++; ?>
		<?php endforeach; ?>
	</tbody>
</table>