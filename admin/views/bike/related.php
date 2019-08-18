<?php bike_::menu($bike->bike_id); ?>
<?php if(!isset($related)): ?>
	<div class = "alert alert-warning" role = "alert">No related bikes</div>
<?php else: ?>
	<table class = "table">
		<thead>
			<tr>
				<th scope = "col">#</th>
				<th scope = "col">Name</th>
				<th scope = "col">Brand</th>
				<th scope = "col">Type</th>
				<th scope = "col">Action</th>
				<th><input type = "checkbox" id = "checkAll" /></th>
			</tr>		
		</thead>
		<tbody>
			<?php $i = itemNumeration(); ?>
			<?php foreach($related->fetch_data() as $rel): ?>
				<tr>
					<th scope = "row"><?php echo $i; ?></th>
					<td><a href = "<?php echo ADMIN_URL; ?>/bike/edit/<?php echo $rel->bike_->bike_id; ?>"><?php echo $rel->bike_->bike_name; ?></a></td>
					<td><a href = "<?php echo ADMIN_URL; ?>/bike/deleteRelated/<?php echo $rel->bike_related_id; ?>">DELETE</a></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
<?php if(isset($bikes)): ?>
	<form action = "<?php echo ADMIN_URL.'/bike/addRelated/'.$bike->bike_id; ?>" method = "POST" name = "related">
		<div class = "form-group">
			<label for = "category_id">Bike</label>
			<select name = "related_id" class = "form-control">
				<?php foreach($bikes->fetch_data() as $bk): ?>
					<option value = "<?php echo $bk->bike_id; ?>"><?php echo $bk->bike_name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<button role = "submit" class = "btn btn-primary">Add</button>
	</form>
<?php else: ?>
	<div class = "alert alert-warning" role = "alert">No bikes to add</div>	
<?php endif; ?>
<br/>
<h5>Similar based on <?php echo $attribute; ?></h5>
<?php if(!isset($autoRelated)): ?>
	<div class = "alert alert-warning" role = "alert">No related bikes</div>
<?php else: ?>
	<table class = "table">
		<thead>
			<tr>
				<th scope = "col">#</th>
				<th scope = "col">Name</th>
				<th scope = "col">Brand</th>
				<th scope = "col">Type</th>
				<th scope = "col">Action</th>
				<th><input type = "checkbox" id = "checkAll" /></th>
			</tr>		
		</thead>
		<tbody>
			<?php $i = itemNumeration(); ?>
			<?php foreach($autoRelated->fetch_data() as $aRel): ?>
				<tr>
					<th scope = "row"><?php echo $i; ?></th>
					<td><a href = "<?php echo ADMIN_URL; ?>/bike/edit/<?php echo $aRel->bike_id; ?>"><?php echo $aRel->bike_name; ?></a></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
