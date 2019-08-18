<?php if(isset($articles)): ?>
	<div class = "row">
		<?php foreach($articles->fetch_data() as $article): ?>
			<article class = "mt-3 col-6">
				<h6 class = "panel-header-sm panel-header-oswald">
					<a href = "/article/display/<?php echo $article->article_path; ?>"><?php echo strtoupper($article->article_title); ?></a>
					<small class = "text-muted sub-header-sm"> BY  <a href = "/user/display/<?php echo $article->user_->user_path; ?>"><?php echo $article->user_->user_name.'</a> '.$article->formatDate(false); ?></small>
				</h6>
				<div class = "panel panel-content-sm">
					<div class = "row">
						<div class = "col-4 pr-0">
							<a href = "/article/display/<?php echo $article->article_path; ?>" class = "d-block">
								<img title = "<?php echo $article->article_title; ?>" alt = "<?php echo $article->article_title; ?>" src = "/upload/images/<?php echo $article->defaultImage(); ?>" class = "img-fluid" />
							</a>
						</div>
						<div class = "col-8">
							<p><?php echo $article->contentShort(200); ?></p>
						</div>
					</div>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
	<?php //$pagination->navigation(); ?>
<?php endif; ?>