<?php
define('DEVELOPMENT', 1); // 0 = production | 1 = development

// Display errors in development mode
ini_set('display_errors', DEVELOPMENT);
error_reporting(~E_WARNING & ~E_NOTICE & ~E_STRICT);

define('SYS_PATH', dirname(realpath(__FILE__)) . '/../system/');

require_once('ez_sql_core.php');
require_once('ez_sql_pdo.php');
require_once('utils.php');

$utils = new Utils();
$db = new ezSQL_pdo('sqlite:'.SYS_PATH.'database.s3db', 'cms', 'cmsp');

if( isset($_REQUEST['permalink']) ) {

	$permalink = $_REQUEST['permalink'];

	$slug = $utils->convert_url($permalink);

	$result = $db->get_var("SELECT COUNT(*) FROM posts WHERE post_slug = '$slug'");

	echo $result;
} else if( isset($_REQUEST['sort']) && isset($_REQUEST['order']) ){
	$type = $_REQUEST['sort'];
	$ids = explode(',', $_REQUEST['order']);
	$length = count($ids);
	for($i = 0; $i < $length; $i++){
		$id = $ids[$i];
		echo $id;
		$db->query("UPDATE posts SET post_order=$i WHERE post_type='$type' AND post_id=$id");
	}
	echo json_encode(array('error' => false));
}
?>