<h5>My reviews</h5>
<?php if(isset($reviews)): ?>
	<table class = "table table-striped table-sm">
		<thead>
			<tr>
				<th>Title</th><th>Bike</th><th>Date</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($reviews->fetch_data() as $review): ?>
				<tr>
					<td><?php echo $review->review_title; ?></td>
					<td><?php echo $review->bike_->bike_name; ?></td>	
					<td><?php echo $review->formatDate(); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>