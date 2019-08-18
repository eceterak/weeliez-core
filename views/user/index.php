<h4>Hello <?php echo $user_->user_name; ?></h4>
<ul class = "list-group">
	<li class = "list-group-item"><a href = "/user/favourites/<?php echo $user_->user_id; ?>">Favourites</a></li>
	<li class = "list-group-item"><a href = "/user/bikes/<?php echo $user_->user_id; ?>">My bikes</a></li>
	<li class = "list-group-item"><a href = "/user/reviews/<?php echo $user_->user_id; ?>">My reviews</a></li>
	<li class = "list-group-item"><a href = "/user/account">My account</a></li>
</ul>