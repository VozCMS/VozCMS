<?php
if($utils->is_admin_page()){
	global $lang;
	$utils->add_admin_page($lang['DASHBOARD'], 'dashboard', 'icon-home', 0);
	$utils->add_admin_page($lang['PAGES'], 'pages', 'icon-book', 10);
	$utils->add_admin_page($lang['POSTS'], 'posts', 'icon-tag', 20);
	$utils->add_admin_page($lang['THEMES'], 'themes', 'icon-eye-open', 30);
	$utils->add_admin_page($lang['PLUGINS'], 'plugins', 'icon-glass', 40);
	$utils->add_admin_page($lang['MEMBERS'], 'members', 'icon-user', 50);
	$utils->add_admin_page($lang['SETTINGS'], 'settings', 'icon-wrench', 100);
}