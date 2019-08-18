<div class = "row mb-3">
	<div class = "col-3">
		<img title = "<?php echo $brand->brand_name; ?>" src = "/upload/images/<?php echo $brand->defaultImage(); ?>" class = "img-fluid" />
	</div>
	<div class = "col-9">
		<table class = "panel table table-sm table-striped-even">
			<tbody>
				<tr>
					<td><strong>Name</strong></td><td><?php echo $brand->brand_name; ?></td>
				</tr>
				<tr>
					<td><strong>Year founded</strong></td><td><?php echo $brand->brand_year; ?></td>
				</tr>
				<tr>
					<td><strong>Founder</strong></td><td><?php echo $brand->brand_founder; ?></td>
				</tr>
				<tr>
					<td><strong>Headquarters</strong></td><td><?php echo $brand->brand_headquarters; ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<article class = "mb-3" id = "article-full">
	<h5 class = "oswald">History of <?php echo $brand->brand_name; ?></h5>
	<div class = "article-content"><?php echo $brand->brand_description; ?></div>
</article>
<?php if(isset($bikes)): ?>
	<h5 class = "oswald mb-3"><?php echo $brand->brand_name; ?> bikes <small>&lsqb;<?php echo $bikes_count; ?>&rsqb;</small></h5>
	<table class = "panel table table-sm table-striped">
		<thead>
			<tr>
				<th scope = "col"><?php sortLink('name', 'desc'); ?></th>
				<th scope = "col"><?php sortLink('year'); ?></th>
				<th scope = "col"><?php sortLink('category'); ?></th>
			</tr>		
		</thead>
		<tbody>
			<?php $i = itemNumeration(); ?>
			<?php foreach($bikes->fetch_data() as $bike): ?>
				<tr>
					<td><a href = "/bike/display/<?php echo $bike->bike_id; ?>"><?php echo $bike->bike_name; ?></a></td>
					<td><?php echo $bike->getYear(); ?></td>
					<td><a href = "/category/display/<?php echo $bike->category_->category_id; ?>"><?php echo $bike->category_->category_name; ?></a></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $bikes->navigation; ?>
<?php endif; ?>