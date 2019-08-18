<?php if(isset($_SESSION['error'])): ?>
	<?php echo $_SESSION['error']; ?>
	<?php unset($_SESSION['error']); ?>
<?php endif; ?>
<?php if(isset($reviews)): ?>
	<?php foreach($reviews->fetch_data() as $review): ?>
		<section class = "news mb-3">
			<header class = "news-header">
				<span class = "r-title"><?php echo $review->review_title; ?></span><small class = "text-muted"> BY <a href = "/user/display/<?php echo $review->user_->user_id; ?>"><?php echo $review->user_->user_name.'</a> '.$review->review_date; ?></small>
				<?php if($user_ && $user_->getId() == $review->user_->getId()): ?>
					<div class = "float-right review-actions">
						<small class = "text-muted">
							<button class = "btn btn-link r-edit" value = "<?php echo $review->getID(); ?>">edit</button> / <a href = "/user/reviewd/<?php echo $review->getId(); ?>">delete</a></small>
					</div>
				<?php endif; ?>
			</header>
			<article>
				<div class = "news-content">
					<p class = "r-content"><?php echo $review->review_content; ?></p>
				</div>
			</article>
		</section>
	<?php endforeach; ?>
<?php else: ?>
	<div>No reviews yet.</div>
<?php endif; ?>
<?php if(isset($user_) && $user_): ?>
	<?php if($user_->checkReview($bike)): ?>
		<div class = "review-message">We're sorry, but you can post only one review per bike.</div>
	<?php else: ?>
		<form method = "POST" action = "/user/review/<?php echo $bike->getId(); ?>">
			<div class = "form-group">
				<input type = "text" name = "review_title" class = "form-control" />
			</div>
			<div class = "form-group">
				<textarea name = "review_content"></textarea>
			</div>
			<input type = "hidden" name = "bike_id" value = "<?php echo $bike->getId(); ?>">
			<button type = "submit" class = "btn btn-primary mt-2">Submit</button>
		</form>
	<?php endif; ?>
<?php else: ?>
	You need to log in to be able to post review.
<?php endif; ?>
<div class = "dialog-wide" id = "dialog-review-edit">
	<button type="button" class="close point dialog-close" aria-label="Close">
  		<span aria-hidden="true" style = "font-size: 20px;">&times;</span>
	</button>
	<div class = "review-edit pt-4 pb-4 form-container" id = "tab-1">
		<div class = "alert alert-danger d-none dialog-message"></div>
		<form method = "POST" name = "review-edit-form" novalidate>
			<div class = "form-group">
				<input type = "text" name = "review_title" class = "form-control" required autofocus />
			</div>
			<div class = "form-group">
				<textarea name = "review_content" required></textarea>
			</div>
			<input type = "hidden" name = "bike_id" value = "<?php echo $bike->getId(); ?>">
			<button type = "submit" class = "btn btn-primary mt-2">Submit</button>
		</form>
	</div>
</div>