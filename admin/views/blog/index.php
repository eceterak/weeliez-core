<p class = "h4 mb-3">Blog<a href = "/admin/blog/add" class = "btn btn-small btn-light ml-2" role = "button">Add new <i class="fas fa-plus-circle fa-sm"></i></a></p>
<?php if(isset($blogs)): ?>
	<table class = "table panel">
		<thead>
			<tr>
				<th scope = "col">#</th>
				<th scope = "col"><?php echo sortLink('title', 'desc'); ?></th>
				<th scope = "col">Front end link</th>
				<th scope = "col">Action</th>
				<th><input type = "checkbox" id = "checkAll" /></th>
			</tr>		
		</thead>
		<tbody>
			<?php $i = itemNumeration(); ?>
			<?php foreach($blogs->fetch_data() as $blog): ?>
				<tr>
					<th scope = "row"><?php echo $i; ?></th>
					<td><a href = "<?php echo ADMIN_URL; ?>/blog/edit/<?php echo $blog->blog_id; ?>"><?php echo $blog->blog_title; ?></a></td>
					<td><a href = "/blog/display/<?php echo $blog->blog_id; ?>"><?php echo SITE_URL; ?>/blog/display/<?php echo $blog->blog_id; ?></a></td>
					<td><a href = "<?php echo ADMIN_URL; ?>/blog/delete/<?php echo $blog->blog_id; ?>"><i class="fas fa-trash-alt"></i></a></td>
					<td><input type = "checkbox" value = "<?php echo $blog->blog_id; ?>" class = "check" /></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $blogs->navigation; ?>
<?php endif; ?>