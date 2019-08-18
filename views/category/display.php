<?php if(isset($error)): echo $error; ?>
<?php else: ?>
	<section class = "side p-2">Category information</section>
	<table class = "table tabel-sm table-striped">
		<tbody>
			<tr>
				<td>Name</th><td><?php echo $category->category_name; ?></td>
			</tr>
			<tr>
				<td>Description</th><td><?php echo $category->category_description; ?></td>
			</tr>
		</tbody>
	</table>
	<?php if(isset($notice)): echo $notice; ?>
	<?php else: ?>
		<section class = "side mb-3">Bikes associated with <?php echo $category->category_name; ?></section>
		<table class = "table table-sm table-striped">
			<thead>
				<tr>
					<th scope = "col"><a href = "<?php echo sortLink('name', 'desc'); ?>">Name <i class="fas fa-sort" style = "font-size: 14px; padding-bottom: 1px;"></i></a></th>
					<th scope = "col"><a href = "<?php echo sortLink('year'); ?>">Year</a></th>
					<th scope = "col"><a href = "<?php echo sortLink('category'); ?>">Category</a></th>
				</tr>		
			</thead>
			<tbody>
				<?php $i = itemNumeration(); ?>
				<?php foreach($bikes->fetch_data() as $bike): ?>
					<tr>
						<td><a href = "/bike/display/<?php echo $bike->bike_id; ?>"><?php echo $bike->bike_name; ?></a></td>
						<td><?php echo $bike->getYear(); ?></td>
						<td><a href = "/category/display/<?php echo $bike->category_->category_id; ?>"><?php echo $bike->brand_->brand_name; ?></a></td>
					</tr>
					<?php $i++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php $pagination->navigation(); ?>
	<?php endif; ?>
<?php endif; ?>


