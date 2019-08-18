<p class = "h4 mb-3">New brand</p>
<form action = "/admin/brand/create/" method = "POST" class = "panel panel-content">
	<div class = "form-group">
		<label>Name</label>
		<input type = "text" class = "form-control" name = "brand_name" required />
	</div>
	<div class = "form-group">
		<label>Year</label>
		<input type = "text" class = "form-control" name = "brand_year" />
	</div>
	<div class = "form-group">
		<label>Founder</label>
		<input type = "text" class = "form-control" name = "brand_founder" />
	</div>
	<div class = "form-group">
		<label>Headquarters</label>
		<input type = "text" class = "form-control" name = "brand_headquarters" />
	</div>
	<div class = "form-group">
		<label>History</label>
		<textarea name = "brand_description"></textarea>
	</div>
	<button type = "submit" class = "btn btn-primary">Save</button>
</form>