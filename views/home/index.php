<div class = "row" id = "col-search">
	<div class = "col-8">
		<form action = "/search/results" method = "get" class = "panel panel-content">
			<div class = "row">
				<div class = "col-10">
					<input type = "text" class = "form-control" name = "phrase" placeholder = "Search for bikes, brands, categories & more..." />
				</div>
				<div class = "col-2 pl-0">
					<button class = "btn btn-primary btn-block"><i class="fas fa-search mr-1 fa-xs"></i>Search</button>
				</div>
			</div>
		</form>
	</div>
	<div class = "col-4 pl-0">
		<div class = "panel panel-content-sm panel-fx-height d-flex">
			<a href = "/search/advanced" class = "btn btn-primary btn-oswald btn-finder w-100 d-block">
				<p>BIKE FINDER</p>
				<span><small>Find your next bike</small></span>
			</a>
		</div>
	</div>
</div>
<div class = "row" id = "col-content">
	<div class = "col-8">
		<?php if(isset($articles)): ?>
			<?php if(!isset($_GET['page'])): ?>
				<section class = "home-articles">
					<?php for($i = 0; $i < $articles->num_rows; $i++): ?>
						<?php if($i < 2): ?>
							<article class = "mt-3">
								<h6 class = "panel-header-sm panel-header-oswald">
									<a href = "/article/display/<?php echo $articles->data[$i]->article_path; ?>"><?php echo strtoupper($articles->data[$i]->article_title); ?></a>
									<small class = "text-muted sub-header-sm"> BY <a href = "/user/display/<?php echo $articles->data[$i]->user_->user_path; ?>"><?php echo $articles->data[$i]->user_->user_name.'</a> '.$articles->data[$i]->formatDate(false); ?></small>
								</h6>
								<div class = "panel panel-content-sm">
									<a href = "/article/display/<?php echo $articles->data[$i]->article_path; ?>" class = "d-block">
										<img title = "<?php echo $articles->data[$i]->article_title; ?>" alt = "<?php echo $articles->data[$i]->article_title; ?>" src = "/upload/images/<?php echo $articles->data[$i]->defaultImage(); ?>" class = "img-fluid" />
									</a>
									<p><?php echo $articles->data[$i]->contentShort(250); ?></p>
								</div>
							</article>
						<?php else: ?>
							<article class = "mt-3">
								<h6 class = "panel-header-sm panel-header-oswald">
									<a href = "/article/display/<?php echo $articles->data[$i]->article_path; ?>"><?php echo strtoupper($articles->data[$i]->article_title); ?></a>
									<small class = "text-muted sub-header-sm"> BY  <a href = "/user/display/<?php echo $articles->data[$i]->user_->user_path; ?>"><?php echo $articles->data[$i]->user_->user_name.'</a> '.$articles->data[$i]->formatDate(false); ?></small>
								</h6>
								<div class = "panel panel-content-sm">
									<div class = "row">
										<div class = "col-4 pr-0">
											<a href = "/article/display/<?php echo $articles->data[$i]->article_path; ?>" class = "d-block">
												<img title = "<?php echo $articles->data[$i]->article_title; ?>" alt = "<?php echo $articles->data[$i]->article_title; ?>" src = "/upload/images/<?php echo $articles->data[$i]->defaultImage(); ?>" class = "img-fluid" />
											</a>
										</div>
										<div class = "col-8">
											<p><?php echo $articles->data[$i]->contentShort(300); ?></p>
										</div>
									</div>
								</div>
							</article>
						<?php endif; ?>
					<?php endfor; ?>
				</section>
			<?php else: ?>
				<?php foreach($articles->fetch_data() as $article): ?>
					<article class = "mt-3">
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
									<p><?php echo $article->contentShort(300); ?></p>
								</div>
							</div>
						</div>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php echo $articles->navigation; ?>
		<?php else: ?>
			<section class = "home-articles">
				<h4>Nothing to display</h4>
			</section>
		<?php endif; ?>
	</div>
	<div class = "col-4 pl-0">
		<aside class = "mt-3">
			<header class = "panel-header-sm panel-header-oswald">MOST RECENT BIKES</header>
			<?php if(isset($bikes)): ?>
				<table class = "table table-sm panel table-striped-even">
					<tbody>
						<?php foreach($bikes->fetch_data() as $bike): ?>
							<tr>
								<td><a href = "/bike/specs/<?php echo $bike->bike_path; ?>"><?php echo $bike->bike_name; ?></a></td>
								<td class = "shrink"><a href = "/brand/display/<?php echo $bike->brand_->brand_path; ?>"><?php echo $bike->brand_->brand_name; ?></a></td>
								<td class = "shrink">
									<a href = "/brand/display/<?php echo $bike->brand_->brand_path; ?>"><img title = "<?php echo $bike->brand_->brand_name; ?>" alt = "<?php echo $bike->brand_->brand_name; ?>" src = "/upload/images/<?php echo $bike->brand_->defaultImage(); ?>" class = "img-thumb" /></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else: ?>
				<h4>Nothing to display</h4>
			<?php endif; ?>
		</aside>
		<aside class = "mt-3">
			<header class = "panel-header-sm panel-header-oswald">POPULAR BRANDS</header>
			<?php if(isset($brands)): ?>
				<table class = "table table-sm panel table-striped-even">
					<tbody>
						<?php foreach($brands->fetch_data() as $brand): ?>
							<tr>
								<td class = "shrink">
									<a href = "/brand/display/<?php echo $brand->brand_path; ?>"><img src = "/upload/images/<?php echo $brand->defaultImage(); ?>" title = "<?php echo $brand->brand_name; ?>" alt = "<?php echo $brand->brand_name; ?>" class = "img-thumb" /></a>
								</td>
								<td><a href = "/brand/display/<?php echo $brand->brand_path; ?>"><?php echo $brand->brand_name; ?></a></td>
								<td><?php echo $brand->bikesAmount; ?> <small>bikes</small></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else: ?>
				<h4>Nothing to display</h4>
			<?php endif; ?>
		</aside>
		<aside class = "mt-3">
			<header class = "panel-header-sm panel-header-oswald">MOST LOVED</header>
			<?php if(isset($loves)): ?>
				<table class = "table table-sm panel table-striped-even">
					<tbody>
						<?php foreach($loves->fetch_data() as $love): ?>
							<tr>
								<td><a href = "/bike/specs/<?php echo $love->bike_path; ?>"><?php echo $love->bike_name; ?>&nbsp;<small><?php echo $love->getYear(); ?></small></a></td>
								<td>
									<a href = "/brand/display/<?php echo $love->brand_->brand_path; ?>"><?php echo $love->brand_->brand_name; ?></a>
								</td>
								<td class = "shrink">
									<a href = "/brand/display/<?php echo $love->brand_->brand_path; ?>"><img src = "/upload/images/<?php echo $love->brand_->defaultImage(); ?>" title = "<?php echo $love->brand_->brand_name; ?>" alt = "<?php echo $love->brand_->brand_name; ?>" class = "img-thumb" /></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else: ?>
				<h4>Nothing to display</h4>
			<?php endif; ?>
		</aside>
		<aside class = "mt-3">
			<header class = "panel-header-sm panel-header-oswald">FRESH REVIEWS</header>
			<?php if(isset($reviews)): ?>
				<table class = "table table-sm panel table-striped-even">
					<tbody>
						<?php foreach($reviews->fetch_data() as $review): ?>
							<tr>
								<td><a href = "/bike/specs/<?php echo $review->bike_->bike_path; ?>"><?php echo crop($review->bike_->bike_name, 10); ?></a></td>
								<td><a href = "/user/display/<?php echo $review->user_->user_id; ?>"><small>by <a href = "/user/display/<?php echo $review->user_->user_path; ?>"><?php echo crop($review->user_->user_name, 5); ?></a></small></a></td>
								<td><a href = "/brand/display/<?php echo $review->brand_->brand_path; ?>"><?php echo $review->brand_->brand_name; ?></a></td>
								<td class = "shrink">
									<a href = "/brand/display/<?php echo $review->brand_->brand_path; ?>"><img src = "/upload/images/<?php echo $review->brand_->defaultImage(); ?>" title = "<?php echo $review->brand_->brand_name; ?>" alt = "<?php echo $review->brand_->brand_name; ?>" class = "img-thumb" /></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else: ?>
				<h4>Nothing to display</h4>
			<?php endif; ?>
		</aside>
	</div>
</div>