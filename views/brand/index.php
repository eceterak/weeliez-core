<header class = "mb-3 section-header panel panel-content-md panel-bg-brand">
	<div class = "row align-items-center">
		<div class = "col-7">
			<h4 class = "m-0">Brands</h4>
			<span><small class = "text-muted">There is <?php echo (isset($brands)) ? $brands->num_rows : '0'; ?> brands in database</small></span>
		</div>
		<form action = "/search/results" method = "get" class = "col-5">
			<input type = "text" name = "phrase" class = "form-control" placeholder = "Search for bikes, brands, categories & more..." />
		</form>
	</div>
</header>
<?php if(isset($brands)): ?>
	<?php foreach($brands->fetch_data() as $key => $value): ?>
		<div class = "panel panel-content mb-3" id = "brands-index">
			<div class = "row d-flex align-items-center">
				<div class = "col-1 mr-2 text-center brand-key ">
					<h1 class = "d-inline-block oswald"><?php echo $key; ?></h1>
				</div>
				<?php foreach($value as $brand): ?>
					<div class = "col-2">
						<div class = "row d-flex align-items-center">
							<div class = "col-6">
								<a href = "/brand/display/<?php echo $brand->brand_path; ?>">
									<img src = "/upload/images/<?php echo $brand->defaultImage(); ?>" class = "img-fluid" />
								</a>
							</div>
							<div class = "col-6">
								<a href = "/brand/display/<?php echo $brand->brand_path; ?>">
									<p class = "mt-0 brand-name oswald"><?php echo $brand->brand_name; ?></p> <p class = "oswald mb-0"><small class = "xsmall">[<?php echo $brand->bikesAmount; ?>]</small></p>
								</a>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<h4>Nothing to display.</h4>
<?php endif; ?>