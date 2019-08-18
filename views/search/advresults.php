<div class = "side-panel mb-3">
	<h3>Advanced search results</h3>
	<h5 class = "mb-0">Found <?php echo $amount; ?>  <?php echo ($amount == 1) ? 'result' : 'results' ?></h5>
</div>
<?php if(isset($bikes)): ?>
	<table class = "table table-sm table-striped">
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
			<?php foreach($bikes as $bike): ?>
				<tr>
					<td><a href = "/bike/display/<?php echo $bike->bike_id; ?>"><?php echo $bike->bike_name; ?></a></td>
					<td><a href = "/brand/display/<?php echo $bike->brand_->brand_id; ?>"><?php echo $bike->brand_->brand_name; ?></a></td>
					<td><?php echo $bike->getYear(); ?></td>
					<td><a href = "/category/display/<?php echo $bike->category_->category_id; ?>"><?php echo $bike->category_->category_name; ?></a></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>