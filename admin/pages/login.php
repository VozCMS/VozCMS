<?php
global $utils;

$error = '';
$member = $utils->get_session('member');

if($member){
	if($member->member_role == 1){
		header("Location: ".the_site_url(false) . 'admin/?p=dashboard');
	} else {
		// ERROR : User trying to access admin area
		$error = '<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">×</a> '.lang("ACCESS_DENIED", false).'</div>';
	}
} else if(isset($_POST['username'])){
	// Get the user, validate and access the admin area
	global $db;
	$username = sqlite_escape_string($_POST['username']);
	$password = md5(sqlite_escape_string($_POST['password']));

	$member = $db->get_row("SELECT * FROM members WHERE member_username='$username' AND member_password='$password'");
	if($member && $member->member_role == 1){
		$utils->set_session('member', $member);
		header("Location: ".the_site_url(false) . 'admin/?p=dashboard');
	} else if(!$member){
		// Member SIGN_IN error
		$error = '<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">×</a>  '.lang("SIGN_IN_ERROR", false).'</div>';
	} else {
		// Member is not an administrator
		$error = '<div class="alert alert-error"><a class="close" data-dismiss="alert" href="#">×</a> '.lang("ACCESS_DENIED", false).'</div>';
	}
}
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php lang('ADMINISTRATION'); ?></title>
	<link rel="stylesheet" href="<?php the_theme_url(); ?>css/bootstrap.css">
	<link rel="stylesheet" href="<?php the_theme_url(); ?>redactor/css/redactor.css">

	<?php 
	the_stylesheet(); 
	load_scripts_backend();
	?>

	<style type="text/css">
	  /* Override some defaults */
	  html, body {
		background-color: #eee;
	  }
	  body {
		padding-top: 40px; 
	  }
	  .container {
		width: 300px;
	  }

	  /* The white background content wrapper */
	  .container > .content {
		background-color: #fff;
		padding: 20px;
		margin: 0 -20px; 
		-webkit-border-radius: 10px 10px 10px 10px;
		   -moz-border-radius: 10px 10px 10px 10px;
				border-radius: 10px 10px 10px 10px;
		-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
		   -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
				box-shadow: 0 1px 2px rgba(0,0,0,.15);
	  }

	.SIGN_IN-form {
	  margin-left: 65px;
	}
  
	legend {
	  margin-right: -50px;
	  font-weight: bold;
	  color: #404040;
	}

	</style>

</head>
<body>
	<div class="container">
		<p><img src="http://lorempixel.com/g/300/150/abstract/logo" alt="Logo"></p>
		<?php echo $error; ?>
		<div class="content">
			<div class="row">
				<div class="SIGN_IN-form">
					<h2><?php lang("SIGN_IN"); ?></h2>
					<form method="post">
						<fieldset>
							<div class="clearfix">
								<input type="text" name="username" placeholder="<?php lang('USERNAME'); ?>">
							</div>
							<div class="clearfix">
								<input type="password" name="password" placeholder="<?php lang('PASSWORD'); ?>">
							</div>
							<button class="btn primary" type="submit"><?php lang("SIGN_IN"); ?></button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div> <!-- /container -->
	<?php the_foot(); load_scripts_backend(true); ?>
</body>
</html>