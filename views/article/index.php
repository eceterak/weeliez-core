<?php if(isset($articles)): ?>
	<?php foreach($articles as $category => $art): ?>
		<section class = "blog-articles">
			<h4 class = "mb-3 mt-3"><a href = "/article/category/<?php //echo $?>"><?php echo $category; ?><span class = "text-muted xxsmall ml-2">more <i class="fas fa-plus-square fa-xs"></i></span></h4>
			<?php if($art->num_rows > 1): ?>
				<div class = "row h-100">
					<article class = "col-8 d-flex pr-0 blog-featured-article">
						<div class = "panel panel-content-sm">
							<h6 class = "panel-header-sm panel-header-oswald">
								<a href = "/article/display/<?php echo $art->data[0]->article_path; ?>"><?php echo strtoupper($art->data[0]->article_title); ?></a>
							</h6>
							<a href = "/article/display/<?php echo $art->data[0]->article_path; ?>" class = "d-block">
								<img title = "<?php echo $art->data[0]->article_title; ?>" alt = "<?php echo $art->data[0]->article_title; ?>" src = "/upload/images/<?php echo $art->data[0]->defaultImage(); ?>" class = "img-fluid" />
							</a>
						</div>
					</article>
					<div class = "col-4">
						<div class = "blog-articles-small row h-100">
							<?php for($i = 1; $i < $art->num_rows; $i++): ?>
								<article class = "col-12">
									<div class = "panel panel-content-sm w-100 text-center">
										<h6 class = "panel-header-sm panel-header-oswald">
											<a href = "/article/display/<?php echo $art->data[$i]->article_path; ?>"><?php echo strtoupper($art->data[$i]->article_title); ?></a>
										</h6>
										<a href = "/article/display/<?php echo $art->data[$i]->article_path; ?>" class = "d-block">
											<img title = "<?php echo $art->data[$i]->article_title; ?>" alt = "<?php echo $art->data[$i]->article_title; ?>" src = "/upload/images/<?php echo $art->data[$i]->defaultImage(); ?>" class = "img-fluid" />
										</a>
									</div>
								</article>
							<?php endfor; ?>
						</div>
					</div>
				</div>
			<?php else: ?>
				<div class = "row h-100">
					<article class = "col-12 d-flex pr-0 blog-featured-article">
						<div class = "panel panel-content-sm">
							<h6 class = "panel-header-sm panel-header-oswald">
								<a href = "/article/display/<?php echo $art->data[0]->article_path; ?>"><?php echo strtoupper($art->data[0]->article_title); ?></a>
							</h6>
							<a href = "/article/display/<?php echo $art->data[0]->article_path; ?>" class = "d-block">
								<img title = "<?php echo $art->data[0]->article_title; ?>" alt = "<?php echo $art->data[0]->article_title; ?>" src = "/upload/images/<?php echo $art->data[0]->defaultImage(); ?>" class = "img-fluid" />
							</a>
						</div>
					</article>
				</div>	
			<?php endif; ?>
		</section>
	<?php endforeach; ?>
<?php else: ?>
	<section class = "home-article">
		<h4>Nothing to display</h4>
	</section>
<?php endif; ?>