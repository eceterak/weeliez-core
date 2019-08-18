<header class = "mb-3 section-header panel panel-content-md panel-bg-bike">
	<div class = "row align-items-center">
	<div class = "col-7">
		<h4 class = "m-0">Bikes</h4>
		<span><small class = "text-muted">There is <?php echo $count; ?> bikes in database</small></span>
	</div>
	<form action = "/search/results" method = "get" class = "col-5">
		<input type = "text" name = "phrase" class = "form-control" placeholder = "Search for bikes, brands, categories & more..." />
	</form>
	</div>
</header>
<div class = "row">
	<aside class = "col-3 pr-0" id = "bikes-filters">
		<p class = "panel-header-sm panel-header-oswald small filters-header mb-3">Filters</p>
		<form method = "GET" name = "bulk">
			<div class = "panel panel-content-md">
				<div class = "form-group">
					<label for = "brand_id" class = "d-block">Brand</label>
					<?php $brands = \brand_::getAll(); ?>
					<select multiple = "multiple" name = "brand[]" id = "brand" class = "form-control multi">
						<?php foreach($brands as $brand): ?>
							<?php if(isset($_GET['brand'])): ?>
								<?php if(in_array($brand->brand_id, $_GET['brand'])): ?>
									<option selected value = "<?php echo $brand->brand_id; ?>"><?php echo $brand->brand_name; ?></option>
								<?php else: ?>
									<option value = "<?php echo $brand->brand_id; ?>"><?php echo $brand->brand_name; ?></option>
								<?php endif; ?>
							<?php else: ?>
								<option value = "<?php echo $brand->brand_id; ?>"><?php echo $brand->brand_name; ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
				<div class = "form-group">
					<label for = "category_id" class = "d-block">Category</label>
					<?php $categories = \category_::getAll(); ?>
					<select multiple = "multiple" name = "category[]" id = "category" class = "form-control multi">
						<?php foreach($categories as $category): ?>
							<?php if(isset($_GET['category'])): ?>
								<?php if(in_array($category->category_id, $_GET['category'])): ?>
									<option selected value = "<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></option>
								<?php else: ?>
									<option value = "<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></option>
								<?php endif; ?>
							<?php else: ?>
								<option value = "<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></option>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
				<div class = "form-group slider">
					<label for = "year" class = "d-block">Year</label>
					<div class = "mb-2">
						<span class = "val-left">min</span>
						<span class = "val-right float-right">max</span>
					</div>
					<div class = "slider-range" style = "margin: 0px 9px;"></div>
					<input type = "hidden" name = "bike_year_start" class = "amount" readonly />
				</div>
				<input type = "hidden" name = "filters" value = "1" />
				<button class = "btn btn-block btn-bulk mt-3" type = "submit">Apply</button>
			</div>
		</form>
		<p class = "panel-header-sm panel-header-oswald small filters-header mb-3 mt-3">Advanced search</p>
		<div class = "panel panel-content-sm panel-fx-height d-flex">
			<a href = "/search/advanced" class = "btn btn-primary btn-oswald btn-finder w-100 d-block">
				<p>BIKE FINDER</p>
				<span><small>Find your next bike</small></span>
			</a>
		</div>
	</aside>
	<div class = "col-9">
		<table class = "table table-sm table-striped panel">
			<thead>
				<tr>
					<th scope = "col"><?php sortLink('name', 'desc'); ?></th>
					<th scope = "col"><?php sortLink('brand'); ?></th>
					<th scope = "col"><?php sortLink('year'); ?></th>
					<th scope = "col"><?php sortLink('category'); ?></th>
				</tr>		
			</thead>
			<tbody>
				<?php $i = itemNumeration(); ?>
				<?php foreach($bikes->fetch_data() as $bike): ?>
					<tr>
						<td><a href = "/bike/specs/<?php echo $bike->bike_path; ?>"><?php echo $bike->bike_name; ?></a></td>
						<td><a href = "/brand/display/<?php echo $bike->brand_->brand_id; ?>"><?php echo $bike->brand_->brand_name; ?></a></td>
						<td><?php echo $bike->getYear(); ?></td>
						<td><a href = "/category/display/<?php echo $bike->category_->category_id; ?>"><?php echo $bike->category_->category_name; ?></a></td>
					</tr>
					<?php $i++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo $bikes->navigation; ?>
	</div>
</div>