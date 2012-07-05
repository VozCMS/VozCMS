<?php 
the_header(); 
global $custom_admin_page;

if(!isset($custom_admin_page)){
	$custom_admin_page = array();
}

$p = $_GET['p'];
if(array_key_exists($p, $custom_admin_page)){
	// Custom admin page
	$title = $custom_admin_page[$p]['page_name'];
	echo "<legend>$title</legend>";
	call_user_func($custom_admin_page[$_GET['p']]['function']);
} else {
	// Real index page
}
the_footer(); 
?>