<div class = "row mb-4">
	<div class = "col-3">
		<div class = "text-center panel panel-box">
			<?php if($unvalidated): ?>
				<div class = "d-inline-block fa-round-bg bg-danger"><i class="fas fa-check fa-3x align-middle"></i></div>
				<div class = "d-inline-block text-left align-middle ml-2">
					<a href = "/admin/article/unvalidated"><h5 class = "mb-0"><?php echo $unvalidated.' '.multi_int($unvalidated, 'article', 'articles'); ?></h5></a><small class = "text-muted">needs validation</small></div>
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
			<div class = "d-inline-block fa-round-bg bg-blueish"><i class="fas fa-motorcycle fa-3x align-middle"></i></div>
			<div class = "d-inline-block text-left align-middle ml-2">
				<h5 class = "mb-0"><?php echo (isset($bikes)) ? $bikes->num_rows : '0'; ?> bikes</h5><small class = "text-muted">in the database</small>
			</div>
		</div>
	</div>
	<div class = "col-3">
		<div class = "text-center panel panel-box">
			<?php if($new_users): ?>
				<div class = "d-inline-block fa-round-bg"><i class="fas fa-users fa-3x align-middle"></i></div>
				<div class = "d-inline-block text-left align-middle ml-2">
					<a href = "/admin/user/recent"><h5 class = "mb-0"><?php echo $new_users.' new '.multi_int($new_users, 'user', 'users'); ?></h5></a><small class = "text-muted">registered in the last week</small>
				</div>
			<?php else: ?>
				<div class = "d-inline-block fa-round-bg"><i class="fas fa-users fa-3x align-middle"></i></div>
				<div class = "d-inline-block text-left align-middle ml-2">
					<h5 class = "mb-0">No users</h5><small class = "text-muted">registered in the last week</small>
				</div>				
			<?php endif; ?>
		</div>
	</div>
		<div class = "col-3">
		<div class = "text-center panel panel-box">
			<?php if($new_users): ?>
			<div class = "d-inline-block fa-round-bg bg-secondary"><i class="fas fa-comment fa-3x align-middle"></i></div>
			<div class = "d-inline-block text-left align-middle ml-2">
				<a href = "/admin/review/recent"><h5 class = "mb-0"><?php echo $reviews.' new '.multi_int($reviews, 'review', 'reviews'); ?></h5></a><small class = "text-muted">added in the last week</small>
			</div>
			<?php else: ?>
				<div class = "d-inline-block fa-round-bg bg-secondary"><i class="fas fa-comment fa-3x align-middle"></i></div>
				<div class = "d-inline-block text-left align-middle ml-2">
					<h5 class = "mb-0">No reviews</h5><small class = "text-muted">added in the last week</small>
				</div>					
			<?php endif; ?>	
		</div>
	</div>
</div>
<div class = "row">
	<div class = "col-6">
		<?php if(isset($bikes)): ?>
			<p class = "h4 mb-3">Recent bikes</p>
			<table class = "table panel table-sm">
				<thead>
					<tr>
						<th scope = "col">#</th>
						<th scope = "col"><?php echo sortLink('name', 'desc'); ?></th>
						<th scope = "col"><?php echo sortLink('brand'); ?></th>
						<th scope = "col"><?php echo sortLink('year'); ?></th>
						<th scope = "col"><?php echo sortLink('category'); ?></th>
					</tr>		
				</thead>
				<tbody>
					<?php $i = itemNumeration(); ?>
					<?php foreach($bikes->fetch_data() as $bike): ?>
						<tr>
							<th scope = "row"><?php echo $i; ?></th>
							<td><a href = "<?php echo ADMIN_URL; ?>/bike/edit/<?php echo $bike->bike_id; ?>"><?php echo $bike->bike_name; ?></a></td>
							<td><a href = "<?php echo ADMIN_URL; ?>/brand/edit/<?php echo $bike->brand_->brand_id; ?>"><?php echo $bike->brand_->brand_name; ?></a></td>
							<td><?php echo $bike->getYear(); ?></td>
							<td><a href = "<?php echo ADMIN_URL; ?>/category/edit/<?php echo $bike->category_->category_id; ?>"><?php echo $bike->category_->category_name; ?></a></td>
						</tr>
						<?php $i++; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
	<div class = "col-6">
		<?php if(isset($articles)): ?>
			<p class = "h4 mb-3">New articles</p>
			<table class = "table table-sm panel">
				<thead>
					<tr>
						<th>#</th>
						<th><?php sortLink('name'); ?></th>
						<th><?php sortLink('category'); ?></th>
						<th><?php sortLink('author'); ?></th>
						<th><?php sortLink('date', 'asc'); ?></th>
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
						</tr>
						<?php $i++; ?>
					<?php endforeach; ?>
				</tbody>
			</table>			
		<?php endif; ?>
	</div>
</div>