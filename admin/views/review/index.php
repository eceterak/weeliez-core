<p class = "h4 mb-3">User reviews</p>
<table class = "table panel">
	<thead>
		<tr>
			<th>#</th>
			<th><?php sortLink('bike'); ?></th>
			<th><?php sortLink('author'); ?></th>
			<th><?php sortLink('date', 'asc'); ?></th>
			<th>Action</th>
		</tr>
	</thead>
	<?php $i = itemNumeration(); ?>
	<tbody>
		<?php foreach($reviews->fetch_data() as $review): ?>
			<tr>
				<th scope = "row"><?php echo $i; ?></th>
				<td>
					<a href = "<?php echo ADMIN_URL.'/review/edit/'.$review->review_id; ?>"><?php echo $review->bike_->bike_name; ?></a>
				</td>
				<td><?php echo $review->user_->user_name; ?></td>
				<td><?php echo $review->formatDate(); ?></td>
				<td><a href = "<?php echo ADMIN_URL; ?>/review/delete/<?php echo $review->review_id; ?>"><i class="fas fa-trash-alt"></i></a></td>
			</tr>
			<?php $i++; ?>
		<?php endforeach; ?>
	</tbody>
</table>