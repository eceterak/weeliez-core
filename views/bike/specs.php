<?php if(isset($_SESSION['error'])): ?>
	<div id = "dialog"><?php echo $_SESSION['error']; ?></div>
<?php endif; ?>
<div class = "row mb-3">
	<div class = "col-5">
		<div class = "pr-0">
			<img src = "/upload/images/<?php echo $bike->defaultImage(); ?>" class = "img-fluid panel gallery-open point" />
		</div>
		<div class = "gallery">
			<?php foreach($bike->images_->images as $image): ?>
				<img src = "/upload/images/<?php echo $image->image_url; ?>" class = "img-fluid point gallery-img" />
			<?php endforeach; ?>
		</div>
	</div>
	<div class = "col-7 pl-0">
		<table class = "panel table table-sm table-striped-even">
			<tbody>
				<tr><th colspan = "2" class = "oswald">General</th></tr>
				<tr><td>Model</td><td><?php echo $bike->bike_name; ?></td></tr>
				<tr><td>Brand</td><td><a href = "/brand/display/<?php echo $bike->brand_->brand_path; ?>"><?php echo $bike->brand_->brand_name; ?></td></tr>
				<tr><td><?php echo ($bike->getYear(true)) ? 'Years' : 'Year' ?></td><td><?php echo $bike->getYear(); ?></td></tr>
				<tr><td>Category</td><td><a href = "/category/display/<?php echo $bike->category_->category_path; ?>"><?php echo $bike->category_->category_name; ?></a></td></tr>
			</tbody>
		</table>
		<!--
		<button id = "love" class = "btn btn-link cl-heart p-0" value = "<?php echo $bike->bike_id; ?>">
			<?php if($bike->checkLove($user_)): ?>
				<i class = "fas fa-heart point"></i>
			<?php else: ?>
				<i class="fas fa-heart point"></i>
			<?php endif; ?>
		</button>
			<small class = "pl-1 loves"><?php echo $bike->bike_loves; ?></small>
		<div>
			<button id = "favourite" class = "btn btn-link p-0 m-0" value = "<?php echo $bike->bike_id; ?>">
				<?php if($bike->checkFavourite($user_)): ?>
					<i class = "fas fa-star point"></i>
				<?php else: ?>
					<i class="far fa-star point"></i><span class = "ml-2 fav-text">Add to favourites</span>
				<?php endif; ?>
			</button>
		</div>
		-->
		<div class = "row">
			<div class = "col-6">
				<a href = "/bike/compare/<?php echo $bike->bike_id; ?>" class = "btn btn-primary mt-1">Compare</a>
			</div>
			<div class = "col-6">
				<a href = "/bike/reviews/<?php echo $bike->bike_path; ?>" class = "btn btn-primary mt-1">User reviews</a>
			</div>
		</div>
		<!--
		<div class = "dialog dialog-sign">
			<span class = "ui-helper-hidden-accessible"><input type = "text" /></span>
			<button type="button" class="close point dialog-close" aria-label="Close">
  				<span aria-hidden="true" style = "font-size: 20px;">&times;</span>
			</button>
			<p id = "love-sign">Please sign in</p>
		</div>-->
	</div>
</div>
<?php if(!empty($bike->specs_)): ?>
	<table class = "panel table table-sm table-striped-even">
		<tbody>
			<?php foreach(attribute_type_::getAll() as $attribute_type): ?>
				<?php if(in_array($attribute_type->attribute_type_name, array_keys($bike->specs_))): ?>
					<tr><th colspan = "2" class = "oswald"><?php echo $attribute_type->attribute_type_name; ?></th></tr>
					<?php foreach($bike->specs_[$attribute_type->attribute_type_name] as $spec): ?>
						<tr>
							<td><?php echo $spec->attribute_->attribute_name; ?><?php if($spec->attribute_->attribute_description !== ''): ?>&nbsp<a href = "#" data-toggle = "tooltip" title = "<?php echo $spec->attribute_->attribute_description; ?>"><i class="fa fa-question-circle" aria-hidden="true"></i></a><?php endif; ?>
							</td>
							<td>
								<?php echo $spec->spec_value; ?>
								<?php if(!is_null($spec->attribute_->unit_->unit_name)) echo ' '.$spec->attribute_->unit_->unit_name.' <small>'.unit_::convert($spec->attribute_->unit_->unit_name, $spec->spec_value).'</small>'; ?>
								<?php if($spec->attribute_->attribute_postscript != ''): ?><?php echo ' <small>'.$spec->attribute_->attribute_postscript.'</small>'; ?>
								<?php endif; ?>
								<?php if($spec->attribute_->attribute_sub == 1 && $spec->spec_sub): ?>&#64&nbsp<?php echo number_format($spec->spec_sub); ?> RPM<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
<?php if(isset($relatedBikes)): ?>
	<h5 class = "mb-2">Similar bikes</h5>
	<div class = "row">
		<?php foreach($relatedBikes->fetch_data() as $related): ?>
			<div class = "col-3">
				<a href = "/upload/images/<?php echo $related->defaultImage(); ?>">
					<img src = "/upload/images/<?php echo $related->defaultImage(); ?>" class = "img-fluid" />
				</a>
				<?php echo $related->bike_name; ?>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>