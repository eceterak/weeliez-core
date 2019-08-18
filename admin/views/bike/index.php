<!--<form action = "/admin/bike/bulk" method = "POST" name = "bulk">-->
	<div class = "mb-3">
		<span class = "h4">Bikes<a href = "/admin/bike/add" class = "btn btn-small btn-light ml-2">Add new <i class="fas fa-plus-circle fa-sm"></i></a>
		<button class = "btn btn-small btn-light ml-2 quick-add">Quick add <i class="fas fa-plus-circle fa-sm"></i></button></span>
		<div class = "float-right form-group form-inline mb-0 form-bulk">
			<select name = "perPage" class = "form-control mr-2"><option value = "10">10</option><option value = "25">25</option><option value = "50">50</option></select>
			<button class = "btn btn-bulk mr-2 display-filters" type = "button">Filters</button>
			<select name = "action" class = "form-control">
				<option value = "bulk">Bulk actions</option>
				<option value = "delete">Delete</option>
			</select>
			<button class = "btn btn-bulk btn-bulk-apply ml-2" type = "submit" disabled>Apply</button>
		</div>
	</div>
	<table class = "table panel table-sm">
		<thead>
			<tr>
				<th scope = "col">#</th>
				<th scope = "col"><?php echo sortLink('name', 'desc'); ?></th>
				<th scope = "col"><?php echo sortLink('brand'); ?></th>
				<th scope = "col"><?php echo sortLink('year'); ?></th>
				<th scope = "col"><?php echo sortLink('category'); ?></th>
				<th scope = "col">Action</th>
				<th><input type = "checkbox" id = "checkAll" /></th>
			</tr>
			<tr class = "filters form-bulk <?php echo (isset($_GET['filters']) && $_GET['filters'] == 1) ? '' : 'd-none'; ?>">
				<form method = "GET" name = "bulk">
					<th>							
						<button type="button" class="close point float-left display-filters" aria-label="Close">
	  						<span aria-hidden="true">&times;</span>
						</button>
					</th>
					<th>
						<input class = "form-control" placeholder = "Name" name = "bike_name" value = "<?php echo (isset($_GET['bike_name']) && !empty($_GET['bike_name'])) ? $_GET['bike_name'] : ''; ?>" />
					</th>
					<th>
						<select class = "form-control" name = "brand">
							<option value = "0">Select brand</option>
							<?php foreach(brand_::getAll() as $brand): ?>
								<option value = "<?php echo $brand->brand_id; ?>" <?php echo (isset($_GET['brand']) && $_GET['brand'] == $brand->brand_id) ? 'selected' : ''; ?>><?php echo $brand->brand_name; ?></option>
							<?php endforeach; ?>
						</select>
					</th>
					<th class = "shrink">
						<input class = "form-control d-inline" style = "width: 70px;" placeholder = "From" name = "year_start" />
						<input class = "form-control d-inline" style = "width: 70px;" placeholder = "To" name = "year_end" />
					</th>
					<th>
						<select class = "form-control" name = "category">
							<option value = "0">Select category</option>
							<?php foreach(category_::getAll() as $category): ?>
								<option value = "<?php echo $category->category_id; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $category->category_id) ? 'selected' : ''; ?>><?php echo $category->category_name; ?></option>
							<?php endforeach; ?>
						</select>
					</th>
					<th colspan = 2>
						<button class = "btn btn-block btn-bulk" type = "submit">Apply</button>
					</th>
					<input type = "hidden" name = "filters" value = "1" />
				</form>			
			</tr>	
		</thead>
		<tbody>
			<?php $i = itemNumeration(); ?>
			<?php foreach($bikes->fetch_data() as $bike): ?>
				<tr>
					<th scope = "row"><?php echo $i; ?></th>
					<td><a href = "<?php echo ADMIN_URL; ?>/bike/edit/<?php echo $bike->bike_id; ?>"><?php echo $bike->bike_name; ?></a></td>
					<td><a href = "<?php echo ADMIN_URL; ?>/brand/edit/<?php echo $bike->brand_->brand_id; ?>"><?php echo $bike->brand_->brand_name; ?></a></td>
					<td><?php echo $bike->getYear(); ?></td>
					<td><a href = "<?php echo ADMIN_URL; ?>/category/edit/<?php echo $bike->category_->category_id; ?>"><?php echo $bike->category_->category_name; ?></a></td>
					<td>
						<a href = "<?php echo ADMIN_URL; ?>/bike/delete/<?php echo $bike->bike_id; ?>"><i class="fas fa-trash-alt"></i></a> <a href = "<?php echo ADMIN_URL; ?>/bike/duplicate/<?php echo $bike->bike_id; ?>"><i class="fas fa-copy"></i></a>
					</td>
					<td><input type = "checkbox" name = "item_id[]" value = "<?php echo $bike->bike_id; ?>" class = "check" /></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
<!--</form>-->
<?php echo $bikes->navigation; ?>
<div class = "dialog quick-add-dialog">
	<form name = "quick-add">
		<button type="button" class="close point dialog-close h6" aria-label="Close">
  			<span aria-hidden="true">&times;</span>
		</button>
		<h5 class = "mb-3">Quick add</h5>
		<div class = "row">
			<div class = "form-group col-8">
				<input type = "text" name = "bike_name" id = "bike_name" class = "form-control form-control-lg" placeholder = "Name" required />
			</div>
			<div class = "form-group col-4">
				<select name = "bike_sale" class = "form-control">
					<option value = "std">Standard</option>
					<option value = "1">On sale</option>
					<option value = "archive">Archive</option>
				</select>
			</div>
		</div>
		<div class = "row">
			<div class = "form-group col-6">
				<label for = "bike_year_start">Year start</label>
				<input type = "number" name = "bike_year_start" class = "form-control" placeholder = "Year start" required />
			</div>
			<div class = "form-group col-6">
				<label for = "bike_year_end">Year end</label>
				<input type = "number" name = "bike_year_end" class = "form-control" placeholder = "Year end" />
			</div>
			<div class = "form-group col-6">
				<label for = "brand_id">Brand</label>
				<select name = "brand_id" class = "form-control select2">
					<?php foreach(brand_::getAll() as $brand): ?>
						<option value = "<?php echo $brand->brand_id; ?>"><?php echo $brand->brand_name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class = "form-group col-6">
				<label for = "category_id">Category</label>
				<select name = "category_id" class = "form-control select2">
					<?php foreach(category_::getAll() as $category): ?>
						<option value = "<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class = "form-group">
			<label for = "image_url">Image</label>
			<input type = "text" name = "image_url" id = "image_url" class = "form-control form-control-lg" placeholder = "Image url" />
		</div>
		<div class = "row">
			<div class = "form-group col-6">
				<label for = "bikez">bikez</label>
				<input type = "text" name = "bikez" id = "bikez" class = "form-control form-control-lg" placeholder = "bikez" />
			</div>
			<div class = "form-group col-6">
				<label for = "motorcyclespecs">motorcyclespecs</label>
				<input type = "text" name = "motorcyclespecs" id = "motorcyclespecs" class = "form-control form-control-lg" placeholder = "motorcyclespecs" />
			</div>
		</div>
		<button role = "submit" class = "btn btn-primary">Save</button>
	</form>
</div>