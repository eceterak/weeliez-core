<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo $title; ?></title>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	<meta name = "author" content = "Marek Bartula">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato|Oswald:300,400">
		<link rel="stylesheet" href="<?php echo JS_PATH.'/trumbowyg/dist/ui/trumbowyg.min.css'; ?>">
		<link rel="stylesheet" href="<?php echo CSS_PATH.'/bootstrap-multiselect.css'; ?>" type="text/css"/>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.css" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/solid.css" integrity="sha384-TbilV5Lbhlwdyc4RuIV/JhD8NR+BfMrvz4BL5QFa2we1hQu6wvREr3v6XSRfCTRp" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/fontawesome.css" integrity="sha384-ozJwkrqb90Oa3ZNb+yKFW2lToAWYdTiF1vt8JiH5ptTGHTGcN7qdoR1F95e0kYyG" crossorigin="anonymous">
		<link rel="stylesheet" href="<?php echo CSS_PATH.'/admin.css'; ?>">
	</head>
	<body cz-shortcut-listen = "true">
		<nav class = "navbar navbar-dark fixed-top flex-md-nowrap">
			<a class = "navbar-logo pl-3 d-block" href = "/"><span class = "navbar-icon">W</span><?php echo NAME; ?></a>
			<form method = "GET" action = "/admin/search/results" class = "fixed-offset w-20">
				<input type = "text" class = "form-control form-control-dark navbar-search d-inline-block" placeholder = "search" name = "phrase" />
			</form>	
			<div class = "text-right ml-auto pr-3">
				<span class = "mr-2"><i class="fas fa-user mr-2"></i>Hello <?php echo $user_->user_name; ?></span>
				<a href = "/admin/user/logout" class = "nav-link d-inline-block">Logout</a>
			</div>	
		</nav>
		<nav class = "d-none d-md-block sidebar">
			<div class = "sidebar-sticky">
				<ul class="nav flex-column">
					<?php if($user_->access_->access_level > 0): ?>
						<li class = "nav-item"><a href = "/admin/" class = "nav-link blink" title = "article"><i class="fas fa-home fa-fw"></i>Home</a></li>
						<li class = "collapse-menu nav-item">
							<a href = "/admin/article" class = "nav-link" title = "article"><i class="fas fa-file-alt fa-fw"></i>Articles</a>
							<div class = "sub-menu d-none">
								<ul class="nav flex-column">
									<li class = "nav-item"><a href = "/admin/article_category" class = "nav-link">Categories</a></li>								
								</ul>
							</div>
						</li>
						<li class = "collapse-menu nav-item">
							<a href = "/admin/bike" class = "nav-link blink"><i class="fas fa-motorcycle fa-fw"></i>Bikes</a>
							<div class = "sub-menu d-none">
								<ul class="nav flex-column">
									<li class = "nav-item"><a href = "/admin/brand" class = "nav-link">Brands</a></li>
									<li class = "nav-item"><a href = "/admin/category" class = "nav-link">Categories</a></li>
									<li class = "nav-item"><a href = "/admin/attribute_type" class = "nav-link">Attributes</a></li>
									<!-- Small trick to keep this menu open when editing a attribute. It wont appear because of d-none -->
									<li class = "nav-item"><a href = "/admin/attribute" class = "nav-link d-none">Attributes</a></li>
									<li class = "nav-item"><a href = "/admin/unit" class = "nav-link">Units</a></li>			
								</ul>
							</div>
						</li>
						<li class = "collapse-menu nav-item">
							<a href = "/admin/user" class = "nav-link blink"><i class="fas fa-users fa-fw"></i>Users</a>
							<div class = "sub-menu d-none">
								<ul class="nav flex-column">
									<li class = "nav-item"><a href = "/admin/review" class = "nav-link">Reviews</a></li>
									<li class = "nav-item"><a href = "/admin/access" class = "nav-link">User Access</a></li>							
								</ul>
							</div>
						</li>				
						<li class = "nav-item"><a href = "/admin/blog" class = "nav-link"><i class="fas fa-th-large fa-fw"></i>Blog</a></li>
					<?php endif; ?>
				</ul>
			</div>		
		</nav>
		<main role = "main" class = "fixed-offset">
			<div class = "container-fluid">
				<div class = "mb-3 mt-1"><?php //\breadcrumbs::navigation(); ?></div>
				<?php if(isset($error)): ?>
					<div class = "alert alert-danger" role = "alert">
						<span><i class="fas fa-exclamation-circle mr-2"></i><?php echo $error; ?></span>	
					</div>
				<?php else: ?>
					<?php if(isset($_SESSION['message'])): ?>
						<div class = "alert alert-success" role = "alert">
							<span><?php echo $_SESSION['message']; ?></span>
							<button type="button" class="close point dialog-close" aria-label="Close">
  								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<?php session::sUnset('message'); ?>
					<?php endif; ?>
					<?php if(isset($_SESSION['error'])): ?>
						<div class = "alert alert-danger" role = "alert">
							<span><?php echo $_SESSION['error']; ?></span>
							<button type="button" class="close point dialog-close" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<?php session::sUnset('error'); ?>
					<?php endif; ?>
					<?php require($view); ?>
				<?php endif; ?>
			</div>
		</main>
		<!-- Content END -->
		<!-- JavaScript placed at the end of the document so the pages loads faster -->
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
		<!-- Select2 -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
		<!-- Custom -->
		<script src = "<?php echo JS_PATH.'/attributes.js'; ?>"></script>
		<script src = "<?php echo JS_PATH.'/validation.js'; ?>"></script>
		<script src = "<?php echo JS_PATH.'/imagesManagement.js'; ?>"></script>
		<script src = "<?php echo ADMIN_JS_PATH.'/admin.js'; ?>"></script>
		<script src = "<?php echo ADMIN_JS_PATH.'/copy.js'; ?>"></script>
		<script src = "<?php echo ADMIN_JS_PATH.'/quickAdd.js'; ?>"></script>
		<script src = "<?php echo ADMIN_JS_PATH.'/copy_bikez.js'; ?>"></script>
	</body>
</html>