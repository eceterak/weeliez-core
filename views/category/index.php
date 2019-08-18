<header class = "mb-3 section-header panel panel-content-md panel-bg-category">
	<div class = "row align-items-center">
	<div class = "col-7">
		<h4 class = "m-0">Categories</h4>
		<span><small class = "text-muted">There is <?php echo (isset($categories)) ? $categories->num_rows : '0'; ?> Categories in database</small></span>
	</div>
	<form class = "col-5">
		<input type = "text" class = "form-control" placeholder = "Search for bikes, brand, categories & more..." />
	</form>
	</div>
</header>
<table class = "panel table table-sm table-striped">
	<thead>
		<tr>
			<th scope = "col">Name</th>
			<th scope = "col">Bikes</th>
		</tr>		
	</thead>
	<tbody>
		<?php foreach($categories->fetch_data() as $category): ?>
			<tr>
				<td><a href = "/category/display/<?php echo $category->category_path; ?>"><?php echo $category->category_name; ?></a></td>
				<td><?php echo '['.$category->bikesAmount.']'; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>	
</table>