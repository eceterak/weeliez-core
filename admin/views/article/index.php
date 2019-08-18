<div class = "row mb-4">
	<div class = "col-3">
		<div class = "text-center panel panel-box">
			<?php if($unvalidated): ?>
				<div class = "d-inline-block fa-round-bg bg-danger"><i class="fas fa-check fa-3x align-middle"></i></div>
				<div class = "d-inline-block text-left align-middle ml-2">
					<h5 class = "mb-0"><a href = "/admin/article/unvalidated"><?php echo $unvalidated.' '.multi_int($unvalidated, 'article', 'articles'); ?></h5></a><small class = "text-muted">needs validation</small></div>
			<?php else: ?>
				<div class = "d-inline-block fa-round-bg bg-success"><i class="fas fa-check fa-3x align-middle"></i></div>
				<div class = "d-inline-block text-left align-middle ml-2">
					<h5 class = "mb-0">All articles</h5><small class = "text-muted">are validated</small>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class = "col-3">
		<div class = "text-center panel panel-box">
			<div class = "d-inline-block fa-round-bg bg-warning"><i class="fas fa-database fa-3x align-middle"></i></div>
			<div class = "d-inline-block text-left align-middle ml-2"><h5 class = "mb-0"><?php echo (isset($articles)) ? $articles->num_rows : '0'; ?> articles</h5><small class = "text-muted">in database</small></div>
		</div>
	</div>
	<div class = "col-3">
		<div class = "text-center panel panel-box">
			<div class = "d-inline-block fa-round-bg bg-success"><i class="fas fa-check fa-3x align-middle"></i></div>
			<div class = "d-inline-block text-left align-middle ml-2"><h5 class = "mb-0"><?php echo $unvalidated; ?> articles</h5><small class = "text-muted">needs validation</small></div>
		</div>
	</div>
	<div class = "col-3">
		<div class = "text-center panel panel-box">
			<div class = "d-inline-block fa-round-bg bg-success"><i class="fas fa-check fa-3x align-middle"></i></div>
			<div class = "d-inline-block text-left align-middle ml-2"><h5 class = "mb-0"><?php echo $unvalidated; ?> articles</h5><small class = "text-muted">needs validation</small></div>
		</div>
	</div>
</div>
<form action = "/admin/article/bulk" method = "POST" name = "bulk">
	<div class = "mb-3">
		<span class = "h4">Articles<a href = "/admin/article/add" class = "btn btn-small btn-light ml-2">Add new <i class="fas fa-plus-circle fa-sm"></i></a></span>
		<div class = "float-right form-group form-inline mb-0 form-bulk">
			<select name = "action" class = "form-control">
				<option value = "bulk">Bulk actions</option>
				<option value = "delete">Delete</option>
			</select>
			<button class = "btn btn-bulk btn-bulk-apply ml-2" type = "submit" disabled>Apply</button>
		</div>
	</div>
	<table class = "table panel">
		<thead>
			<tr>
				<th>#</th>
				<th><?php sortLink('name'); ?></th>
				<th><?php sortLink('category'); ?></th>
				<th><?php sortLink('author'); ?></th>
				<th><?php sortLink('date', 'asc'); ?></th>
				<th>Action</th>
				<th><input type = "checkbox" id = "checkAll" /></th>
			</tr>
		</thead>
		<?php $i = itemNumeration(); ?>
		<tbody>
			<?php foreach($articles->fetch_data() as $article): ?>
				<tr>
					<th scope = "row"><?php echo $i; ?></th>
					<td>
						<a href = "<?php echo ADMIN_URL.'/article/edit/'.$article->article_id; ?>"><?php echo $article->article_title; ?><?php echo ($article->article_valid !== 1) ? ' <i class="fa fa-exclamation-circle" aria-hidden="true"></i>' : '' ; ?></a>
					</td>
					<td><?php echo $article->article_category_->article_category_name; ?></td>
					<td><?php echo $article->user_->user_name; ?></td>
					<td><?php echo $article->formatDate(); ?></td>
					<td><a href = "<?php echo ADMIN_URL; ?>/article/delete/<?php echo $article->article_id; ?>"><i class="fas fa-trash-alt"></i></a></td>
					<td><input type = "checkbox" name = "item_id[]" value = "<?php echo $article->article_id; ?>" class = "check" /></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</form>