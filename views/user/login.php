<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo NAME; ?></title>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<!-- Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato|Oswald:300,400">
		<!-- JQuery -->
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
		<!-- Plugins -->
		<link rel="stylesheet" href="<?php echo JS_PATH.'/trumbowyg/dist/ui/trumbowyg.min.css'; ?>">
		<link rel="stylesheet" href="<?php echo CSS_PATH.'/bootstrap-multiselect.css'; ?>" type="text/css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" />
		<link rel="stylesheet" href="<?php echo CSS_PATH.'/bootstrap-multiselect.css'; ?>" type="text/css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" />
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/solid.css" integrity="sha384-TbilV5Lbhlwdyc4RuIV/JhD8NR+BfMrvz4BL5QFa2we1hQu6wvREr3v6XSRfCTRp" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/fontawesome.css" integrity="sha384-ozJwkrqb90Oa3ZNb+yKFW2lToAWYdTiF1vt8JiH5ptTGHTGcN7qdoR1F95e0kYyG" crossorigin="anonymous">
		<!-- Custom styles. Place after bootstrap -->
		<link rel="stylesheet" href="<?php echo CSS_PATH.'/global.css'; ?>">
	</head>
	<body>
		<div class = "container login-form form-container" id = "user-form">
			<form action = "/user/log" class = "panel panel-uneven panel-content-lg" name = "login" method = "POST" novalidate>
				<h4 class = "mb-3 action-header"><a href = "/">weeliez.com</a> / login</h4>
				<div class = "form-group">
					<label>Username</label>
					<input type = "text" name = "user_name" class = "form-control" />
				</div>
				<div class = "form-group">
					<label>Password</label>
					<input type = "password" name = "user_password" class = "form-control" />
				</div>
				<?php if(isset($_SESSION['error'])): ?>
					<div class = "alert alert-danger dialog-message mt-2"><?php echo $_SESSION['error']; ?></div>
				<?php endif; ?>
				<p class = "mb-2 small">Don't have an account? <a href = "/user/register" class = "underline">Register Here!</a></p>
				<!--<p class = "small"><button class = "btn btn-link forgot-click underline" type = "button">Forgot your password?</button></p>-->
				<p class = "small"><a href = "/user/forgot" class = "underline">Forgot your password?</a></p>
				<button type = "submit" class = "btn btn-primary btn-block">Login</button>
			</form>
		</div>
		<!-- Footer END -->
		<!-- JQuery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<!-- Bootstrap -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
		<!-- Trumbowyg -->
		<script src="<?php echo JS_PATH.'/trumbowyg/dist/trumbowyg.min.js'; ?>"></script>
		<script src="<?php echo JS_PATH.'/trumbowyg/dist/plugins/upload/trumbowyg.upload.js'; ?>"></script>
		<!-- Bootstrap multiselect -->
		<script type="text/javascript" src="<?php echo JS_PATH.'/bootstrap-multiselect.js'; ?>"></script>
		<!-- Custom -->
		<script src = "<?php echo JS_PATH.'/attributes.js'; ?>"></script>
		<script src = "<?php echo JS_PATH.'/validation.js'; ?>"></script>
		<script src = "<?php echo JS_PATH.'/login.js'; ?>"></script>
		<script src = "<?php echo JS_PATH.'/love.js'; ?>"></script>
		<script src = "<?php echo JS_PATH.'/favourite.js'; ?>"></script>
		<script src = "<?php echo JS_PATH.'/review.js'; ?>"></script>
		<script src = "<?php echo JS_PATH.'/global.js'; ?>"></script>
	</body>
</html>