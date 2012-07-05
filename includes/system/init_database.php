<?php
function valid_email($email){
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if(is_bool($atIndex) && !$atIndex){
      $isValid = false;
   } else {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64){
         // local part length exceeded
         $isValid = false;
      } else if ($domainLen < 1 || $domainLen > 255){
         // domain part length exceeded
         $isValid = false;
      } else if ($local[0] == '.' || $local[$localLen-1] == '.'){
         // local part starts or ends with '.'
         $isValid = false;
      } else if (preg_match('/\\.\\./', $local)){
         // local part has two consecutive dots
         $isValid = false;
      } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)){
         // character not valid in domain part
         $isValid = false;
      } else if (preg_match('/\\.\\./', $domain)){
         // domain part has two consecutive dots
         $isValid = false;
      } else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))){
         if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))){
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))){
         $isValid = false;
      }
   }
   return $isValid;
}

if($_POST['username']){
	define('LIB_PATH', dirname(realpath(__FILE__)) . '/../libs/');

	require_once(LIB_PATH . 'ez_sql_core.php');
	require_once(LIB_PATH . 'ez_sql_pdo.php');
	require_once(LIB_PATH . 'utils.php');

	$utils = new Utils();
	$db = new ezSQL_pdo('sqlite:database.s3db', 'cms', 'cmsp');

	$site_name = $_POST['site_name'];
	$username = sqlite_escape_string($_POST['username']);
	$email = sqlite_escape_string($_POST['email']);
	$password = $_POST['password'];
	$password_re = $_POST['password_re'];

	$usern_length = strlen($username);
	$pass_length = strlen($password);

	if(empty($site_name)){
		json_encode(array('error' => 1));
		exit();
	} else if($usern_length < 4 || $usern_length > 15){
		json_encode(array('error' => 2));
		exit();
	} else if(preg_match("/[^A-Za-z0-9]/", $username)){
		json_encode(array('error' => 3));
		exit();
	} else if(empty($email)){
		json_encode(array('error' => 4));
		exit();
	} else if(!valid_email($email)){
		json_encode(array('error' => 5));
		exit();
	} else if($pass_length < 4){
		json_encode(array('error' => 6));
		exit();
	} else if($password != $password_re){
		json_encode(array('error' => 7));
		exit();
	}


	/* Creating the table [posts] */
	$db->query("CREATE TABLE IF NOT EXISTS [posts]  (
	[post_id] INTEGER  PRIMARY KEY AUTOINCREMENT NOT NULL,
	[post_type] TEXT  NOT NULL,
	[post_slug] VARCHAR(100)  UNIQUE NOT NULL,
	[post_title] TEXT  NOT NULL,
	[post_content] TEXT  NOT NULL,
	[post_created] TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	[post_edited] TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	[post_meta_title] VARCHAR(70)  NULL,
	[post_meta_desc] VARCHAR(160)  NULL,
	[post_order] INTEGER DEFAULT '9999' NOT NULL,
	[post_status] INTEGER DEFAULT '1' NOT NULL
	)");
	$db->query("INSERT INTO posts (post_type, post_slug, post_title, post_content, post_created, post_edited) VALUES ('page', '', 'Welcome!', 'This is the index page.', strftime('%s', 'now'), strftime('%s', 'now'))");

	/* Creating the table [members] */
	$db->query("CREATE TABLE [members] (
	[member_id] INTEGER  NOT NULL PRIMARY KEY AUTOINCREMENT,
	[member_username] VARCHAR(15)  UNIQUE NOT NULL,
	[member_fullname] VARCHAR(50)  NULL,
	[member_email] VARCHAR(50)  UNIQUE NOT NULL,
	[member_password] VARCHAR(35)  NOT NULL,
	[member_role] INTEGER DEFAULT '0' NOT NULL,
	[member_visual] BOOLEAN DEFAULT '1' NOT NULL
	)");
	$db->query("INSERT INTO members (member_username, member_email, member_password, member_role) VALUES ('$username', '$email', '".md5(sqlite_escape_string($password))."', 1)");

	/* Creating the table [settings] */
	$db->query("CREATE TABLE IF NOT EXISTS [settings] (
		[setting_id] INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
		[setting_name] VARCHAR(64) UNIQUE NOT NULL,
		[setting_value] TEXT NOT NULL)");
	$db->query("INSERT INTO settings (setting_name, setting_value) VALUES ('site_name', '$site_name')");
	$db->query("INSERT INTO settings (setting_name, setting_value) VALUES ('site_url', 'http://".$_SERVER['SERVER_NAME']."/')");
	$db->query("INSERT INTO settings (setting_name, setting_value) VALUES ('language', 'en_US')");
	$db->query("INSERT INTO settings (setting_name, setting_value) VALUES ('theme', 'simplestyle')");
	$db->query("INSERT INTO settings (setting_name, setting_value) VALUES ('active_plugins', '')");

	unlink(dirname(realpath(__FILE__)) . '/../../admin/pages/install.php');
	unlink(__FILE__);
} else {
	header("Location: http://".$_SERVER['SERVER_NAME']);
}