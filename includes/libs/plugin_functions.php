<?php
/*
 * Plugin Functions
 */

/*
 * Register Plugin
 * @since 1.0
 * @uses $plugins
 *
 * @param string $name. Name of the plugin
 * @param string $ver Optional. Version of the plugin
 * @param string $author Optional. Name of the plugin's author
 * @param string $author_url Optional. Website of the plugin's author
 * @param string $desc Optional. Description of the plugin
 * @param string $function Optional. The function that is loaded when called this function.
 */

function register_plugin($name, $ver, $author, $author_url, $desc, $function){
	global $plugins;

	if(!isset($plugins)){
		$plugins = array();
	}

	$caller = debug_backtrace (DEBUG_BACKTRACE_IGNORE_ARGS);
	$caller = $caller[0]['file'];
	$id = basename($caller, '.php');

	$plugins[$id] = array(
		'id' => $id,
		'name' => $name,
		'version' => $ver,
		'author' => $author,
		'author_url' => $author_url,
		'description' => $desc,
		'function'	=> $function,
		'enabled' => false
	);
}

/*
 * Init Plugins
 * @since 1.0
 * @uses $plugins, $settings
 */
function init_plugins(){
	global $plugins, $settings, $utils;
	
	$enabled_plugins = unserialize($settings->active_plugins);

	foreach($enabled_plugins as $plugin){
		$key = $plugin['id'];
		if(array_key_exists($key, $plugins)){
			$plugins[$key]['enabled'] = $plugin['enabled'];
			add_plugin_page($plugin['name'], $utils->convert_url($plugin['name']), $plugin['function']);
		}
	}
}

/*
 * Activate Plugin
 * @since 1.0
 * @uses $plugins, $db, $settings
 * 
 * @param integer $id. The id of the plugin
 */
function activate_plugin($id){
	global $plugins, $db, $settings;

	$result = false;

	if(!isset($plugins)){
		$plugins = array();
	}

	$new_active_plugins = array();

	$active = unserialize($settings->active_plugins);

	foreach($active as $key => $value){
		if(array_key_exists($key, $plugins)){
			$plugins[$key]['enabled'] = true;
			if($key != $id)
				array_push($new_active_plugins, $plugins[$key]);
		}
	}

	if(array_key_exists($id, $plugins)){
		$plugins[$id]['enabled'] = true;
		array_push($new_active_plugins, $plugins[$id]);
		$result = true;
	}

	$serialized = sqlite_escape_string(serialize($new_active_plugins));

	if($db->query("UPDATE settings SET setting_value='$serialized' WHERE setting_name='active_plugins'") == 1){
		$result = true;
	} else {
		$result = false;
	}

	return $result;
}

/*
 * Desactivate Plugin
 * @since 1.0
 * @uses $plugins, $db, $settings
 * 
 * @param integer $id. The id of the plugin
 */
function desactivate_plugin($id){
	global $settings, $plugins, $db;

	$result = false;

	$active = unserialize($settings->active_plugins);
	$new_active_plugins = array();

	foreach($active as $key => $value){
		if(array_key_exists($key, $plugins)){
			if($key != $id)
				array_push($new_active_plugins, $plugins[$key]);
		}
	}

	if(array_key_exists($id, $plugins)){
		$plugins[$id]['enabled'] = false;
		$result = true;
	}

	$serialized = sqlite_escape_string(serialize($new_active_plugins));

	if($db->query("UPDATE settings SET setting_value='$serialized' WHERE setting_name='active_plugins'") == 1){
		$result = true;
	} else {
		$result = false;
	}

	return $result;
}