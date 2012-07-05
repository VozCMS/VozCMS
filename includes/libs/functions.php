<?php
define('LIB_PATH', dirname(realpath(__FILE__)) . '/');

define('QUEUE_SCRIPT_ADMIN', true);
define('QUEUE_SCRIPT_FRONT', false);

// Global functions (Themes and Plugins)

// Language functions

/*
 * Language
 * @since 1.0
 * @uses $lang, $settings
 *
 * @param string $name.
 * @param boolean $echo Optional. Default is true
 */
function lang($name, $echo = true){
	global $lang, $settings;

	$result = "{$name}";
	if(array_key_exists($name, $lang)){
		$result = $lang[$name];
	}

	if($echo){
		echo $result;
	} else {
		return $result;
	}
}

/*
 * Add Action
 * @since 1.0
 * @uses $actions
 *
 * @param string $hook_name
 * @param string $function
 * @param array $args Optional.
 * 
 */
function add_action($hook_name, $function, $args=array()){
	global $actions;

	if(!isset($actions)){
		$actions = array();
	}

	$caller = debug_backtrace();
	$file = $caller[0]['file'];
	array_push($actions, array(
		'hook' => $hook_name, 
		'function' => $function, 
		'args' => (array) $args,
		'file' => $file
		)
	);
}

/*
 * Execute Action
 * @since 1.0
 * @uses $actions
 * 
 * @param string $hook_name.
 * 
 */
function exec_action($hook_name){
	global $actions;

	foreach($actions as $a){
		if($a['hook'] == $hook_name){
			call_user_func($a['function'], $a['args']);
		}
	}
}

/*
 * Register Script
 * @since 1.0
 * @uses $scripts, $utils, $settings
 * 
 * @param string $handle. Name of the script
 * @param string $src. Location of the script
 * @param string $ver. Version of the script
 * @param boolean $in_footer, load the script in footer if true, Default is false.
 */
function register_script($handle, $src, $ver, $in_footer = false){
	global $scripts, $utils, $settings;

	if(!$utils->starts_with($src, 'http://')){
		if($utils->starts_with($src, '/')){
			$src = $settings->site_url . substr($src, 1, strlen($src));
		} else {
			$src = $settings->site_url . $src;
		}
	}

	$scripts[$handle] = array(
		'name' => $handle,
		'src' => $src,
		'version' => $ver,
		'in_footer' => $in_footer,
		'load' => false,
		'backend' => false
	);
}

/*
 * De-Register Script
 * @since 1.0
 * @uses $scripts
 * 
 * @param string $handle. Name of the script.
 */
function deregister_script($handle){
	global $scripts;
	if(array_key_exists($handle, $scripts)){
		unset($scripts[$handle]);
	}
}

/*
 * Queue Script
 * @since 1.0
 * @uses $scripts
 * 
 * @param string $handle. Name of the script
 * @param array $requires. scripts that this file requires before being added
 * @param boolean $in_admin. Load the script backend or frontend. Default is frontend
 */
function queue_script($handle, $in_admin = false){
	global $scripts;
	if(array_key_exists($handle, $scripts)){
		$scripts[$handle]['load'] = true;
		$scripts[$handle]['backend'] = $in_admin;
	}
}

/*
 * De-Queue Script
 * @since 1.0
 * @uses $scripts
 *
 * @param string $handle. Name of the script
 * @param string $in_admin. Unload the script in the backend or in the frontend. Default is frontend
 */
function dequeue_script($handle, $in_admin = false){
	global $scripts;
	if(array_key_exists($handle, $scripts)){
		$scripts[$handle]['load'] = false;
		$scripts[$handle]['backend'] = $in_admin;
	}
}

/*
 * Load Scripts
 * @since 1.0
 * @uses $scripts
 *
 * @param boolean $in_footer. Load the scripts in the footer or header
 */
function load_scripts_frontend($in_footer = false){
	global $scripts;
	foreach($scripts as $script){
		if($script['backend'] == QUEUE_SCRIPT_FRONT){
			if($in_footer){
				if($script['load'] == true && $script['in_footer']){
					$version = isset($script['version']) ? '?v='.$script['version'] : '';
					echo '<script src="'.$script['src'] . $version .'"></script>'."\n";
				}
			} else {
				if($script['load'] == true && !$script['in_footer']){
					$version = isset($script['version']) ? '?v='.$script['version'] : '';
					echo '<script src="'.$script['src'] . $version .'"></script>'."\n";
				}
			}
		}
	}
}

/*
 * Load Scripts
 * @since 1.0
 * @uses $scripts
 */
function load_scripts_backend($in_footer = false){
	global $scripts;
	foreach($scripts as $script){
	if($script['backend'] == QUEUE_SCRIPT_ADMIN){
		if($in_footer){
			if($script['load'] == true && $script['in_footer']){
				$version = isset($script['version']) ? '?v='.$script['version'] : '';
				echo '<script src="'.$script['src'] . $version .'"></script>'."\n";
			}
		} else {
			if($script['load'] == true && !$script['in_footer']){
				$version = isset($script['version']) ? '?v='.$script['version'] : '';
				echo '<script src="'.$script['src'] . $version .'"></script>'."\n";
			}
		}
	}
}
}

/*
 * Add Admin Page
 * @since 1.0
 * @uses $admin_menu, $custom_admin_page
 *
 * @param string $menu_title. Title of the admin page
 * @param string $page_slug. Slug of the page
 * @param string $icon. Icon name
 * @param string $function. Function name to call when into this page
 * @param integer $position Optional. Position in the menu. Default is 999
 * @param string $parent Optional. Parent page slug. Default empty string
 */
function add_admin_page($menu_title, $page_slug, $icon, $function, $position = 999, $parent = ''){
	global $admin_menu, $custom_admin_page;

	if(empty($parent)){
		array_push($admin_menu, array('menu_title' => $menu_title, 'page_slug' => $page_slug, 'icon' => $icon, 'position' => $position));
	} else {
		$length = count($admin_menu);
		for($i = 0; $i < $length; $i++){
			if($admin_menu[$i]['page_slug'] == $parent){
				if(!isset($admin_menu[$i]['children'])){
					$admin_menu[$i]['children'] = array();
				}
				array_push($admin_menu[$i]['children'], array('menu_title' => $menu_title, 'page_slug' => $page_slug, 'icon' => $icon, 'position' => $position));
			}
		}
	}
	if(!isset($custom_admin_page)){
		$custom_admin_page = array();
	}

	if(!empty($parent)){
		preg_match('/plugin=([a-zA-Z0-9_\-]*)/i', $page_slug, $matches);
		$page_slug = $matches[1];
	}
	$custom_admin_page[$page_slug] = array(
		'page_name' => $menu_title,
		'page_slug' => $page_slug,
		'function' => $function,
		'parent' => $parent
	);
}

/*
 * Add page in submenu of Plugins
 * @since 1.0
 * 
 * @param string $menu_title. Title of the admin page
 * @param string $page_slug. Slug of the page
 * @param string $function. Function name to call when into this page
 */
function add_plugin_page($menu_title, $page_slug, $function){
	add_admin_page($menu_title, 'plugins&plugin='.$page_slug, '', $function, 0, 'plugins');
}

/*
 * Add page in submenu of Themes
 * @since 1.0
 * 
 * @param string $menu_title. Title of the admin page
 * @param string $page_slug. Slug of the page
 * @param string $function. Function name to call when into this page
 */
function add_theme_page($menu_title, $page_slug, $function){
	add_admin_page($menu_title, 'themes&plugin='.$page_slug, '', $function, 0, 'themes');
}

/*
 * Load language files
 * @since 1.0
 * @uses $lang, $settings
 * 
 * @param string $language. Language name to load.
 */
function load_language($language){
	global $lang, $settings;

	$old_lang = $lang;

	$idiom = $language ? $language : $settings->language;
	if(!isset($lang) || !is_array($lang)){
		$lang = array();
	}

	$caller = debug_backtrace();
	$folder = dirname($caller[0]['file']) . '/lang/';
	@include($folder . $language . '.php');
	$new_lang = array_merge($old_lang, $lang);

	$lang = $new_lang;
}

/*
 * Get Members
 * @since 1.0
 * @uses $db
 */
function get_members(){
	global $db;
	return $db->get_results("SELECT * FROM members ORDER BY member_role ASC");
}


/* Include the theme and plugin functions */
include_once(LIB_PATH . 'theme_functions.php');
include_once(LIB_PATH . 'plugin_functions.php');

/* Include the theme functions */
@$utils->get_theme_functions();

/* Include the plugins */
@$plugin_files = $utils->get_plugins();
foreach($plugin_files as $file)
	@include_once($file);

// Loading the active plugins
@init_plugins();