<h5>My favourite bikes</h5>
<?php if(isset($favourites)): ?>
	<table class = "table table-striped table-sm">
		<thead>
			<tr>
				<th>Bike</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($favourites->fetch_data() as $bike): ?>
				<tr>
					<td><?php echo $bike->bike_name; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>