<?php
the_header();
global $utils, $settings;

if(isset($_GET['activate']) && !empty($_GET['activate'])){
	$theme = $_GET['activate'];
	if($utils->activate_theme($theme)){
		echo '<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button> <i class="icon-ok"></i> '.lang('THEME_CHANGED', false).'.</div>';
	} else {
		echo '<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button> <i class="icon-ok"></i> '.lang('ERROR_THEME', false).'.</div>';
	}
}

?>
<legend>Themes</legend>
<div class="themes">
<?php
$themes = $utils->get_themes();
foreach($themes as $theme){
	$theme_name = basename($theme);
	$current_theme = (isset($_GET['activate']) && !empty($_GET['activate'])) ? $_GET['activate'] : $settings->theme; 
	$active = ($current_theme == basename($theme_name));
	if($active){
		echo '<div class="current">';
	} else {
		echo '<div>';
	}
	$preview = (file_exists($theme . '/preview.jpg')) ? '../themes/'.$theme_name.'/preview.jpg' : './pages/img/no-pre.png';
	echo '<div class="theme_img"><img src="'.$preview.'"></div>';
	echo '<span class="theme_name">'.$theme_name.'</span>';
	if($active){
		echo '<br>'.lang('CURRENT_THEME', false);
	} else {
		echo '<br><a href="./?p=themes&activate='.$theme_name.'">'.lang('ACTIVATE', false).'</a>';
	}
	echo '</div>';
}
?>
</div>
<?php
the_footer();
?>