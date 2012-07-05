<?php 
global $core, $db; 
$table1 = (bool)@$db->get_var("SELECT count(*) FROM sqlite_master WHERE type='table' and name='members'");
$table2 = (bool)@$db->get_var("SELECT count(*) FROM sqlite_master WHERE type='table' and name='posts'");
$table3 = (bool)@$db->get_var("SELECT count(*) FROM sqlite_master WHERE type='table' and name='settings'");
if($table1 && $table2 && $table3){
	header("Location: http://" .$_SERVER['SERVER_NAME']);
}
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php lang('ADMINISTRATION'); ?></title>
	<link rel="stylesheet" href="<?php the_theme_url(); ?>css/bootstrap.css">
	<?php the_stylesheet(); ?>
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

	.install-form {
	  margin-left: 65px;
	}
	legend {
	  margin-right: -50px;
	  font-weight: bold;
	  color: #404040;
	}
	label {
		display: inline;
	}
	</style>

</head>
<body>
	<div class="container">
		<p><img src="http://lorempixel.com/g/300/150/abstract/logo" alt="Logo"></p>
		<!-- Show errors here -->
		<div class="content">
			<div class="row">
				<div class="install-form">
					<h2><?php lang('INSTALL'); echo ' '.$core['name_ver']; ?></h2>
					<form id="install" method="post">
						<fieldset>
							<div class="clearfix">
								<input type="text" name="site_name" id="site_name" placeholder="<?php lang('SITE_NAME'); ?>">
							</div>
							<div class="clearfix">
								<input type="text" name="username" id="username" placeholder="<?php lang('USERNAME'); ?>" maxlength="15">
							</div>
							<div class="clearfix">
								<input type="text" name="email" id="email" placeholder="<?php lang('EMAIL'); ?>">
							</div>
							<div class="clearfix">
								<input type="password" name="password" id="password" placeholder="<?php lang('PASSWORD'); ?>">
							</div>
							<div class="clearfix">
								<input type="password" name="password_re" id="password_re" placeholder="<?php lang('PASSWORD_RE'); ?>">
							</div>
							<button class="btn primary" type="submit"><?php lang('INSTALL'); ?></button>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div> <!-- /container -->
	<?php the_foot(); ?>
	<script src="http://<?php echo $_SERVER['SERVER_NAME']; ?>/admin/pages/js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript">
		// HTML5 placeholder plugin version 1.01
		// Copyright (c) 2010-The End of Time, Mike Taylor, http://miketaylr.com
		// MIT Licensed: http://www.opensource.org/licenses/mit-license.php
		//
		// Enables cross-browser HTML5 placeholder for inputs, by first testing
		// for a native implementation before building one.
		//
		//
		// USAGE: 
		//$('input[placeholder]').placeholder();

		// <input type="text" placeholder="username">
		(function($){
		  //feature detection
		  var hasPlaceholder = 'placeholder' in document.createElement('input');
		  
		  //sniffy sniff sniff -- just to give extra left padding for the older
		  //graphics for type=email and type=url
		  var isOldOpera = $.browser.opera && $.browser.version < 10.5;

		  $.fn.placeholder = function(options) {
		    //merge in passed in options, if any
		    var options = $.extend({}, $.fn.placeholder.defaults, options),
		    //cache the original 'left' value, for use by Opera later
		    o_left = options.placeholderCSS.left;
		  
		    //first test for native placeholder support before continuing
		    //feature detection inspired by ye olde jquery 1.4 hawtness, with paul irish
		    return (hasPlaceholder) ? this : this.each(function() {
		  	  //TODO: if this element already has a placeholder, exit
		    
		      //local vars
		      var $this = $(this),
		          inputVal = $.trim($this.val()),
		          inputWidth = $this.width(),
		          inputHeight = $this.height(),

		          //grab the inputs id for the <label @for>, or make a new one from the Date
		          inputId = (this.id) ? this.id : 'placeholder' + (Math.floor(Math.random() * 1123456789)),
		          placeholderText = $this.attr('placeholder'),
		          placeholder = $('<label for='+ inputId +'>'+ placeholderText + '</label>');
		        
		      //stuff in some calculated values into the placeholderCSS object
		      options.placeholderCSS['width'] = inputWidth;
		      options.placeholderCSS['height'] = inputHeight;
		      options.placeholderCSS['color'] = options.color;

		      // adjust position of placeholder 
		      options.placeholderCSS.left = (isOldOpera && (this.type == 'email' || this.type == 'url')) ?
		         '11%' : o_left;
		      placeholder.css(options.placeholderCSS);
		    
		      //place the placeholder
		      $this.wrap(options.inputWrapper);
		      $this.attr('id', inputId).after(placeholder);
		      
		      //if the input isn't empty
		      if (inputVal){
		        placeholder.hide();
		      };
		    
		      //hide placeholder on focus
		      $this.focus(function(){
		        if (!$.trim($this.val())){
		          placeholder.hide();
		        };
		      });
		    
		      //show placeholder if the input is empty
		      $this.blur(function(){
		        if (!$.trim($this.val())){
		          placeholder.show();
		        };
		      });
		    });
		  };
		  
		  //expose defaults
		  $.fn.placeholder.defaults = {
		    //you can pass in a custom wrapper
		    inputWrapper: '<span style="position:relative; display:block;"></span>',
		  
		    //more or less just emulating what webkit does here
		    //tweak to your hearts content
		    placeholderCSS: {
		      'font':'100% sans-serif', 
		      'color':'#bababa', 
		      'position': 'absolute', 
		      'left':'5px',
		      'top':'3px', 
		      'overflow-x': 'hidden',
					'display': 'block'
		    }
		  };
		})(jQuery);
	</script>
	<script type="text/javascript">
		function validateEmail(email) { 
		    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\'))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		} 
		$(document).ready(function(){
			$('input[placeholder]').placeholder();

			$('#install').submit(function(e){
				e.preventDefault();

				$('.alert').remove();

				var site_name 	= $('#site_name').val();
				var username 	= $('#username').val();
				var email 		= $('#email').val();
				var password 	= $('#password').val();
				var password_re = $('#password_re').val();
				var error;

				if(site_name.length == 0){
					error = "<?php lang('SITEN_EMPTY'); ?>";
				} else if(username.length < 4 || username.length > 15){
					error = "<?php lang('USERN_LENGTH'); ?>";
				} else if(/[^a-zA-Z0-9]/.test(username)){
					error = "<?php lang('USERN_CHARS'); ?>";
				} else if(email.length == 0){
					error = "<?php lang('EMAIL_REQ'); ?>";
				} else if(!validateEmail(email)){
					error = "<?php lang('INVALID_EMAIL'); ?>";
				} else if(password.length < 4){
					error = "<?php lang('PASSWORD_MIN'); ?>";
				} else if(password != password_re){
					error = "<?php lang('PASSWORD_MATCH'); ?>";
				}

				if(error){
					//show error
					$('.content').before('<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button> '+error+'.</div>');
				} else {
					var url = '../includes/system/init_database.php';
					$.post(url, $(this).serialize(), function(r){
						if(r.error){
							switch(r.error){
								case 1:
									error = "<?php lang('USERN_LENGTH'); ?>";
									break;
								case 2:
									error = "<?php lang('USERN_CHARS'); ?>";
									break;
								case 3:
									error = "<?php lang('EMAIL_REQ'); ?>";
									break;
								case 4:
									error = "<?php lang('INVALID_EMAIL'); ?>";
									break;
								case 5:
									error = "<?php lang('PASSWORD_MIN'); ?>";
									break;
								case 6:
									error = "<?php lang('PASSWORD_MATCH'); ?>";
									break;
							}
							$('.content').before('<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button> '+error+'.</div>');
						} else {
							window.location = "http://<?php echo $_SERVER['SERVER_NAME']; ?>";
						}
					});
				}
			});
		});
	</script>
</body>
</html>