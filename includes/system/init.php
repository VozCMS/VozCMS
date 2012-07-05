<?php
function __autoload($class_name){
	$class_name = strtolower($class_name);
	if(file_exists(LIB_PATH . $class_name . '.php')){
		require(LIB_PATH . $class_name . '.php');
	} else if(file_exists(MAN_PATH . $class_name . '.php')){
		require(MAN_PATH . $class_name . '.php');
	} else if(file_exists(SYS_PATH . $class_name . '.php')){
		require(SYS_PATH . $class_name . '.php');
	}  else if(file_exists(CONFIG_PATH . $class_name . '.php')){
		require(CONFIG_PATH . $class_name . '.php');
	} else {
		echo 'The following class <strong>'.$class_name.'.php</strong> could not be found.';
	}
}

global $utils, $db, $settings, $theme, $admin_menu, $lang, $admin_panel;

define('DEVELOPMENT', 1); // 0 = production | 1 = development

// Display errors in development mode
ini_set('display_errors', DEVELOPMENT);
error_reporting(~E_WARNING & ~E_NOTICE & ~E_STRICT);

define('INC_PATH', BASE_PATH . '/includes/');
define('CONFIG_PATH', BASE_PATH . '/config/');
define('ADMIN_PATH', BASE_PATH . '/admin/');
define('LIB_PATH', INC_PATH . 'libs/');
define('MAN_PATH', INC_PATH . 'managers/');
define('SYS_PATH', INC_PATH . 'system/');
define('CACHE_PATH', BASE_PATH . '/cache/');
define('PLUGINS_PATH', BASE_PATH . '/plugins/');
define('THEMES_PATH', BASE_PATH . '/themes/');
define('THEMES_DIR', '/themes/');

require_once(LIB_PATH . 'ez_sql_core.php');
require_once(LIB_PATH . 'ez_sql_pdo.php');

$admin_menu = array();
$utils = new Utils();
$db = new ezSQL_pdo('sqlite:'.SYS_PATH.'database.s3db', 'cms', 'cmsp');
$lang = array();

require_once(SYS_PATH . 'core.php');

//$db->query("DROP TABLE settings; DROP TABLE members; DROP TABLE posts");
$table1 = (bool)$db->get_var("SELECT count(*) FROM sqlite_master WHERE type='table' and name='members'");
$table2 = (bool)$db->get_var("SELECT count(*) FROM sqlite_master WHERE type='table' and name='posts'");
$table3 = (bool)$db->get_var("SELECT count(*) FROM sqlite_master WHERE type='table' and name='settings'");

if($_GET['p'] != 'install' && (!$table1 || !$table2 || !$table3) ){
	header("Location: http://".$_SERVER['SERVER_NAME'] . '/admin/?p=install');
}

$settings;

$set = @$db->get_results("SELECT * FROM settings");
foreach($set as $s){
	$key = $s->setting_name;
	$settings->$key = (is_numeric($s->setting_value))? (is_integer($s->setting_value)? (int)$s->setting_value : (float)$s->setting_value) : $s->setting_value;
}

if($admin_panel){
	define('THEME_FOLDER', '/admin/pages/');
} else {
	define('THEME_FOLDER', '/themes/'.$settings->theme.'/');
}
define('THEME_DIR', BASE_PATH . THEME_FOLDER);

$theme['path'] = THEME_DIR;
$theme['folder'] = THEME_FOLDER;

// Load the language
if($admin_panel && $_GET['p'] != 'install') {
	$lang_file = BASE_PATH . '/admin/pages/lang/' . $settings->language . '.php';
} else {
	$lang_file = BASE_PATH . '/admin/pages/lang/en_US.php';
}
require_once($lang_file);

require_once(SYS_PATH . 'init_admin.php');
require_once(LIB_PATH . 'functions.php');

$utils->get_page($_GET['p']);