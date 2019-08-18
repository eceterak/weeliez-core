<p class = "h4 mb-3">Unvalidated articles</p>
<table class = "table panel">
	<thead>
		<tr>
			<th>#</th>
			<th><?php sortLink('name'); ?></th>
			<th><?php sortLink('category'); ?></th>
			<th><?php sortLink('author'); ?></th>
			<th><?php sortLink('date', 'asc'); ?></th>
			<th>Action</th>
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
			</tr>
			<?php $i++; ?>
		<?php endforeach; ?>
	</tbody>
</table>