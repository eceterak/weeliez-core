<?php if(isset($error)): ?>
	<div class = "alert alert-warning" role = "alert"><?php echo $error; ?></div>
<?php else: ?>
	<div class = "side-panel mb-3">
		<h3>Search Results for <i><?php echo $phrase; ?></i></h3>
		<h5>Found <?php echo $amount; ?>  <?php echo ($amount == 1) ? 'result' : 'results' ?></h5>
	</div>
	<?php if(isset($bikes)): ?>
		<h3>Bikes</h3>
		<table class = "table table-sm panel">	
			<thead>
				<tr>
					<th>Name</th><th>Year</th><th>Brand</th><th>Category</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($bikes->fetch_data() as $bike): ?>
					<tr>
						<td><a href = "/bike/display/<?php echo $bike->bike_id; ?>"><?php echo $bike->bike_name; ?></a></td>
						<td><?php echo ($bike->bike_year_start == $bike->bike_year_end) ? $bike->bike_year_start: $bike->bike_year_start.' - '.$bike->bike_year_end; ?></td>
						<td><a href = "/brand/display/<?php echo $bike->brand_->brand_id; ?>"><?php echo $bike->brand_->brand_name; ?></a></td>
						<td><a href = "/category/display<?php echo $bike->category_->category_id; ?>"><?php echo $bike->category_->category_name; ?></a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>	
	<?php if(isset($brands)): ?>
		<h3>Brands</h3>
		<table class = "table table-sm table-striped">	
			<thead>
				<tr>
					<th>Name</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($brands->fetch_data() as $brand): ?>
					<tr>
						<td><a href = "/brand/display/<?php echo $brand->brand_id; ?>"><?php echo $brand->brand_name; ?></a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>		
	<?php endif; ?>	
	<?php if(isset($categories)): ?>
		<h3>Categories</h3>
		<table class = "table table-sm table-striped">	
			<thead>
				<tr>
					<th>Name</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($categories->fetch_data() as $category): ?>
					<tr>
						<td><a href = "/category/display<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>		
	<?php endif; ?>
	<?php if(isset($articles)): ?>
		<h3>Articles</h3>
		<table class = "table table-sm table-striped">	
			<thead>
				<tr>
					<th>Name</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($articles->fetch_data() as $article): ?>
					<tr>
						<td><a href = "/article/display/<?php echo $article->article_id; ?>"><?php echo $article->article_title; ?></a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>		
	<?php endif; ?>	
<?php endif; ?>