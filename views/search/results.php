<div class = "row">
	<div class = "col-12">
		<form action = "/search/results" method = "get" class = "panel panel-content mb-3">
			<div class = "row">
				<div class = "col-10">
					<input type = "text" class = "form-control" name = "phrase" <?php echo (isset($phrase)) ? 'value = "'.$phrase.'"' : ''; ?> placeholder = "Search for bikes, brands, categories & more..." />
				</div>
				<div class = "col-2 pl-0">
					<button class = "btn btn-primary btn-block"><i class="fas fa-search mr-1 fa-xs"></i>Search</button>
				</div>
			</div>
		</form>
	</div>

</div>
<?php if(isset($error)): ?>
	<div class = "alert alert-warning" role = "alert"><?php echo $error; ?></div>
<?php else: ?>
	<div class = "side-panel mb-3">
		<h5>Search Results for <i><u><?php echo $phrase; ?></i></u></h5>
		<small class = "oswald medium">Found <?php echo $amount; ?>  <?php echo ($amount == 1) ? 'result' : 'results' ?></small>
	</div>
	<?php if(isset($bikes)): ?>
		<h5>Bikes</h5>
		<table class = "panel table table-sm table-striped">	
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
		<h5>Brands</h5>
		<table class = "panel table table-sm table-striped">	
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
		<h5>Categories</h5>
		<table class = "panel table table-sm table-striped">	
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
		<h5>Articles</h5>
		<table class = "panel table table-sm table-striped">	
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