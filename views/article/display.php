<?php if(isset($article)): ?>
	<article id = "article-full">
		<header>
			<h2 class = "text-center"><?php echo $article->article_title; ?></h2>
			<p class = "text-center"><small class = "text-muted"> BY <?php echo $article->user_->user_name.' '.$article->formatDate(false); ?></small></p>
		</header>
		<img title = "<?php echo $article->article_path; ?>" src = "/upload/images/<?php echo $article->defaultImage(); ?>" class = "img-fluid mb-3 panel" />
		<div class = "article-content"><?php echo $article->article_content; ?></div>
	</article>
<?php endif; ?>