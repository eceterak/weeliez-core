/**
 * Try to login a user with ajax request. If login is successful, reload current page.
 * Proceed only, if form is valid.
 */

/*
$('form[name="login"]').on("submit", function(event) {
	var username, password;
	var $this = $(this);
	var valid = $(this).validation(event);
	var div = $(this).closest('.login-form');
	var dialog = div.find(".dialog-message");
	if(valid) {
		user_name = $this.find('input[name="user_name"]').val();
		user_password = $this.find('input[name="user_password"]').val();
		$.ajax({
			method: "POST",
			url: '/ajax/login.php',
			dataType: 'json', // This will alow to fetch object.
			data: {
				ajax_user_name: user_name,
				ajax_user_password: user_password
			},
			success: function(data) {
				if(data.success == true) {
					location.reload();
				}
				else {
					dialog.text(data.message);
					dialog.removeClass("d-none");
				}
			},
			statusCode: {
				404: function() {
					alert('Page not found');
				}
			}
		});
	}
});
*/

/**
 *
 */

$('form[name="password_forgot"]').on("submit", function(event) {
	var valid = $(this).validation(event, {
		prevent: false,
		inputs: {
			user_email: {
				min: 3,
				max: 40,
				regexp: {
					code: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
					message: 'Invalid email address'
				},
				message: 'Invalid email address'
			}
		}
	});
	if(!valid) {
		event.preventDefault();
	}
});

/**
 *
 */

$('form[name="reset_password"]').on("submit", function(event) {
	var valid = $(this).validation(event, {
		prevent: false,
		inputs: {
			new_password: {
				min: 6,
				max: 30,
				message: 'Password must be between 6 and 30 characters long',
				confirm: true
			}
		}
	});
	if(!valid) {
		event.preventDefault();
	}
});

/**
 * To register new user, first validate all required inputs. If everything is OK, send ajax request and try to create new user on back-end.
 */

$('form[name="register"]').on("submit", function(event) {
	var div = $(this).closest('.register-form');
	var valid = $(this).validation(event, {
		prevent: false,
		inputs: {
			user_name: {
				min: 3,
				max: 20,
				regexp: {
					code: /^[0-9a-zA-z_]+$/,
					message: 'Not allowed'
				},
				message: 'User name must be between 3 and 20 characters long'
			},
			user_password: {
				min: 6,
				max: 30,
				message: 'Password must be between 6 and 30 characters long'
			},
			user_email: {
				min: 3,
				max: 40,
				regexp: {
					code: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
					message: 'Invalid email address'
				},
				message: 'Invalid email address'
			}
		}
	});
	if(!valid) {
		event.preventDefault();
		/***
		var $this = $(this);
		var user_name = $this.find('input[name="user_name"]').val();
		var user_password = $this.find('input[name="user_password"]').val();
		var user_email = $this.find('input[name="user_email"]').val();
		var dialog = $this.find(".dialog-message");
		$.ajax({
			method: "POST",
			url: '/ajax/create.php',
			dataType: 'json', // This will alow to fetch object.
			data: {
				user_name_new: user_name,
				user_password_new: user_password,
				user_email_new: user_email
			},
			success: function(data) {
				if(data.success == true) {
					$this.fadeOut('slow', function() {				
						var html = '<h4 class = "mb-3 action-header">Registration successful!</h4><div>Please check your email address and follow the instructions to verify your account.</div>';
						html += '<p class = "mt-2"><a href = "/">Back to <strong>WEELIEZ.COM</strong></a></p>';
						$(this).html(html); // Change the content of dialog box.
					}).fadeIn('slow');
				}
				else {
					dialog.text(data.message);
					dialog.removeClass("d-none");
				}
			},
			statusCode: {
				404: function() {
					alert('Page not found');
				}
			}
		});
		***/
	}
});

/**
 * When cliclking on forgot password, fade out login form and fade in forgot form. 
 */

/*$('.forgot-click').on('click', function() {
	var div = $(this).closest('.login-form');
	var dialog = div.find(".dialog-message");
	$('form[name="login"]').fadeOut(function() {
		$('form[name="forgot"]').removeClass('d-none');
		$('form[name="forgot"]').fadeIn("slow", function() {
			$(this).on("submit", function(event) {
				var valid = $(this).validation(event, {
					prevent: true,
					inputs: {
						user_email: {
							min: 3,
							max: 40,
							regexp: {
								code: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
								message: 'Invalid email address'
							},
						message: 'Invalid email address'
						}
					}
				});
				if(valid) {
					var $this = $(this); // Forgot password form.
					var user_email = $(this).find('input[name="user_email"]').val();
					$.ajax({
						method: "POST",
						url: '/ajax/forgot.php',
						dataType: 'json', // This will alow to fetch object.
						data: {
							user_email_forgot: user_email
						},
						success: function(data) {
							if(data.success == true) {
								div.fadeOut('slow', function() {
									$(this).html('<div>If a weeliez account exists for ' + user_email + ', an email will be sent with further instructions.</div>');
								}).fadeIn('slow');
							}
							else {
								dialog.html("We're sorry, something went wrong.<br />Please try again later.");
								dialog.removeClass("d-none");
							}
						},
						statusCode: {
							404: function() {
								alert('Page not found');
							}
						}
					});
				}
			});
		});
	});
});*/

$('.back-login').on('click', function() {
	$('form[name="forgot"]').fadeOut(function() {
		$('form[name="login"]').fadeIn("slow");
	});
});

/**
 * Hide login and display reset password form.
 */

$('form[name="reset"]').on("submit", function(event) {
	var div = $(this).closest('.register-form');
	var valid = $(this).validation(event, {
		prevent: false,
		inputs: {
			user_password: {
				min: 6,
				max: 30,
				message: 'Password must be between 6 and 30 characters long',
				confirm: true
			}
		}
	});
	if(!valid) {
		event.preventDefault();
	}
});

/**
 * To make change password more secure user needs to type his old password. It's important because user can be logged in with remember me (anyone can be using his PC).
 */

$('form[name="change-password-form"]').on("submit", function(event) {
	var div = $(this).closest('.change-password-form');
	var valid = $(this).validation(event, {
		prevent: true,
		inputs: {
			user_password: {
				min: 6,
				max: 30,
				message: 'Password must be between 6 and 30 characters long',
				confirm: true // Confirm new password.
			}
		}
	});
	if(valid) {
		var $this = $(this);
		var user_id = $this.find('input[name="user_id"]').val();
		var user_password_old = $this.find('input[name="user_password_old"]').val();
		var user_password = $this.find('input[name="user_password"]').val();
		var dialog = div.find(".dialog-message");
		$.ajax({
			method: "POST",
			url: '/ajax/changePassword.php',
			dataType: 'json', // This will alow to fetch object.
			data: {
				auser_id: user_id,
				auser_password_old: user_password_old,
				auser_password_new: user_password
			},
			success: function(data) {
				if(data.success == true) {
					div.fadeOut('slow', function() {
						$(this).html('<div>Password changed.</div>'); // Change the content of dialog box.
					}).fadeIn('slow');
				}
				else {
					dialog.text(data.message);
					dialog.removeClass("d-none");
				}
			},
			statusCode: {
				404: function() {
					alert('Page not found');
				}
			}
		});
	}
});

/**
 * Change users email.
 */

$('form[name="change-email-form"]').on("submit", function(event) {
	var div = $(this).closest('.change-email-form');
	var valid = $(this).validation(event, {
		prevent: true,
		inputs: {
			user_email: {
				min: 3,
				max: 40,
				regexp: {
					code: /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
					message: 'Invalid email address'
				},
				confirm: true, // Confirm new email.
				message: 'Invalid email address'
			}
		}
	});
	if(valid) {
		var $this = $(this);
		var user_id = $this.find('input[name="user_id"]').val();
		var user_email = $this.find('input[name="user_email"]').val();
		var user_password = $this.find('input[name="user_password"]').val();
		var dialog = div.find(".dialog-message");
		$.ajax({
			method: "POST",
			url: '/ajax/changeEmail.php',
			dataType: 'json', // This will alow to fetch object.
			data: {
				auser_id: user_id,
				auser_email: user_email,
				auser_password: user_password,
			},
			success: function(data) {
				if(data.success == true) {
					div.fadeOut('slow', function() {
						$(this).html('<div>Email address changed</div>'); // Change the content of dialog box.
					}).fadeIn('slow');
				}
				else {
					dialog.text(data.message);
					dialog.removeClass("d-none");
				}
			},
			statusCode: {
				404: function() {
					alert('Page not found');
				}
			}
		});
	}
});

$(document).ready(function() {

	/**
	 * Those must be wrapped into document.ready
	 */

	/**
	 * Display login dialog.
	 */

	$("#dialog-login").on("click", function() {
		$(".dialog-login").dialog("open");
	});

	/**
	 * Hide forgot password form.
	 */
	
	//$('form[name="forgot"]').hide();

	/**
	 * Display delete account confirmation message.
	 */

	$('#delete-account').on('click', function() {
		$(this).after('<small id = "delete-account-confirm" class = "ml-2">Are you sure you want to delete your account? <a href = "/user/delete">Yes</a> / <a href = "#" id = "delete-account-cancel">No</a></small>');
		$(document).on('click', 'a#delete-account-cancel', function() {
			$('#delete-account-confirm').remove(); // Remove message.
		});
	});

	/**
	 * Display change password dialog.
	 */

	$('#change-password').on('click', function() {
		$(".dialog-password").dialog("open");
	});

	/**
	 * Display change email dialog.
	 */

	$('#change-email').on('click', function() {
		$(".dialog-email").dialog("open");
	});
});