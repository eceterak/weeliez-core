<!doctype html>
<html lang="en">
	<head>
		<title><?php echo $title; ?></title>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="<?php echo CSS_PATH.'/admin.css'; ?>">
	</head>
	<body class = "main-bg">
		<div class = "container-fluid">
			<div class = "row align-items-center">
				<main role = "main" class = "col-12 mt-5">
					<div class = "row">
						<div class = "col-2 mx-auto login-form p-4">
							<form action = "<?php echo ADMIN_URL.'/user/login'; ?>" method = "POST" name = "login" novalidate>
								<div class = "form-group">
									<input type = "text" name = "user_name" placeholder = "Login" class = "form-control" required />
								</div>
								<div class = "form-group mb-0">
									<input type = "password" name = "user_password" placeholder = "Password" class = "form-control" required />
								</div>
								<div class = "mt-2 mb-2"><small>Forgot your password? <a href = "#">Click here</a></small></div>
								<div class = "form-group mb-0">
									<button type = "submit" class = "btn btn-primary btn-block">Login</button>
								</div>
							</form>
						</div>
					</div>
				</main>
			</div>
		</div>
		<!-- Content END -->
		<!-- JavaScript placed at the end of the document so the pages loads faster -->
		<!-- JQuery -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<!-- Bootstrap -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
		<!-- Font Awesome -->
		<script src="https://use.fontawesome.com/79de55060b.js"></script>
		<!-- Trumbowyg -->
		<script src="<?php echo JS_PATH.'/trumbowyg/dist/trumbowyg.min.js'; ?>"></script>
		<script src="<?php echo JS_PATH.'/trumbowyg/dist/plugins/upload/trumbowyg.upload.js'; ?>"></script>
		<link rel="stylesheet" href="<?php echo JS_PATH.'/trumbowyg/dist/ui/trumbowyg.min.css'; ?>">
		<!-- Bootstrap multiselect -->
		<script type="text/javascript" src="<?php echo JS_PATH.'/bootstrap-multiselect.js'; ?>"></script>
		<link rel="stylesheet" href="<?php echo CSS_PATH.'/bootstrap-multiselect.css'; ?>" type="text/css"/>
		<!-- Custom -->
		<script src = "<?php echo JS_PATH.'/attributes.js'; ?>"></script>
		<script src = "<?php echo JS_PATH.'/validation.js'; ?>"></script>
		<script src = "<?php echo JS_PATH.'/global.js'; ?>"></script>
	</body>
</html>