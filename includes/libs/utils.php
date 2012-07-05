<?php
class Utils {
	/*
	 * Js Log
	 * @since 1.0
	 * 
	 * @param string $str. The message to add in the console.log()
	 */
	public function js_log($str){
		echo '<script>console.log("'.$str.'");</script>';
	}

	/*
	 * Convert Url
	 * @since 1.0
	 * 
	 * @param string $str. The string to convert to url
	 */
	public function convert_url($str){
		$string = preg_replace('/[\'"]/', '', $str);
	    $string = preg_replace('/[^a-z0-9]+/', '-', strtolower(trim($string)));
	    return $string;
	}

	/*
	 * Get Session
	 * @since 1.0
	 * 
	 * @param string $key. The session's key to return
	 */
	public function get_session($key){
		if(isset($_SESSION[$key])){
			return $_SESSION[$key];
		}
		return false;
	}

	/*
	 * Set Session
	 * @since 1.0
	 * 
	 * @param string $key. The session's key
	 * @param $value. The value of the session
	 */
	public function set_session($key, $value){
		$_SESSION[$key] = $value;
	}

	/*
	 * Delete Session
	 * @since 1.0
	 * 
	 * @param string $key. The session's key to delete
	 */
	public function del_session($key){
		if(array_key_exists($key, $_SESSION)){
			unset($_SESSION[$key]);
		}
	}

	/*
	 * Contains
	 * @since 1.0
	 * 
	 * @param string $str. The string to search
	 * @param string $content. The content
	 * @param boolean $ignorecase. Case insensitive, default true
	 */
	public function contains($str, $content, $ignorecase=true){
	    if ($ignorecase){
	        $str = strtolower($str);
	        $content = strtolower($content);
	    }
	    return strpos($content,$str) !== false;
	}

	/*
	 * Starts With
	 * @since 1.0
	 * 
	 * @param string $haystack. The content
	 * @param string $needle. The string to search
	 */
	public function starts_with($haystack, $needle){
	    $length = strlen($needle);
	    return (substr($haystack, 0, $length) === $needle);
	}

	/*
	 * Starts With
	 * @since 1.0
	 * 
	 * @param string $haystack. The content
	 * @param string $needle. The string to search
	 */
	public function ends_with($haystack, $needle){
	    $length = strlen($needle);
	    if ($length == 0) {
	        return true;
	    }

	    $start  = $length * -1; //negative
	    return (substr($haystack, $start) === $needle);
	}

	/*
	 * Order Array Numerically
	 * @since 1.0
	 * 
	 * @param array $array
	 * @param $key
	 * @param string $order Optional. ASC or DESC, default is ASC.
	 */
	public function order_array_num($array, $key, $order = "ASC"){ 
        $tmp = array(); 
        foreach($array as $akey => $array2) 
        { 
            $tmp[$akey] = $array2[$key]; 
        } 
        
        if($order == "DESC"){
        	arsort($tmp , SORT_NUMERIC );
        } else {
        	asort($tmp , SORT_NUMERIC );
        } 

        $tmp2 = array();        
        foreach($tmp as $key => $value){ 
            $tmp2[$key] = $array[$key]; 
        }        
        
        return $tmp2; 
    }

    /*
	 * Page Exists
	 * @since 1.0
	 * 
	 * @param string $template_file. The name of the file.
	 */
	public function page_exists($template_file){
		return (file_exists(THEME_DIR . $template_file . '.php'));
	}

	/*
	 * Page Exists
	 * @since 1.0
	 * @uses $db
	 * 
	 * @param string $page_name. The name of the page/post.
	 */
	public function page_exists_db($page_name){
		global $db;

		$page_name = sqlite_escape_string($page_name);
		return (@$db->get_var("SELECT COUNT(*) FROM posts WHERE post_slug = '$page_name'") > 0)? true : false;
	}

	/*
	 * Is Admin Page
	 * @since 1.0
	 * @uses $admin_panel
	 * 
	 * @return is the user in an admin page or not.
	 */
	public function is_admin_page(){
		global $admin_panel;
		return (isset($admin_panel) && $admin_panel);
	}
	
	/*
	 * Get Page
	 * @since 1.0
	 * @uses $db, $admin_panel, $post, $settings
	 * 
	 * @param string $file. The name of the page.
	 */
	public function get_page($file){
		global $db, $admin_panel, $post, $settings;

		if($this->is_admin_page()){
			if($this->page_exists($file)){
				include(THEME_DIR . $file . '.php');
			} else {
				include(THEME_DIR . 'index.php');
			}
		} else {
			if(isset($file) && $this->page_exists($file)){
				$p = $file;
			} else if(!empty($file) && $this->page_exists_db($file)){
				$p = $file;
				$file = 'index';
			} else if(empty($file) && $this->page_exists('home')){
				$p = 'home';
			} else if(!$this->page_exists_db($file) && $this->page_exists('404')){
				$p = '404';
			} else {
				$p = '';
				$file = 'index';
			}

			$post = @$db->get_row("SELECT * FROM posts WHERE post_slug = '$p'");
			include(THEME_DIR . $file . '.php');
		}
	}

	/*
	 * Get Theme Functions
	 * @since 1.0
	 */
	public function get_theme_functions(){
		if($this->page_exists('functions')){
			include(THEME_DIR . 'functions.php');
		}
	}

	/*
	 * Get Plugins
	 * @since 1.0
	 */
	public function get_plugins(){
		return glob(PLUGINS_PATH . '*.php');
	}

	/*
	 * Get Themes
	 * @since 1.0
	 */
	public function get_themes(){
		$theme_folders = array();
		$files = glob(THEMES_PATH . '*');

		foreach ($files as $file) {
			if(is_dir($file)){
				array_push($theme_folders, $file);
			}
		}

		return $theme_folders;
	}

	/*
	 * Get Languages
	 * @since 1.0
	 */
	public function get_langs(){
		$languages = array();
		$files = glob(THEME_DIR . 'lang/*.php');
		
		foreach($files as $file){
			array_push($languages, basename($file, '.php'));
		}

		return $languages;
	}

	/*
	 * Activate Theme
	 * @since 1.0
	 * @uses $db
	 *
	 * @param string $theme. The folder's name of the template.
	 */
	public function activate_theme($theme){
		if(!is_dir(THEMES_PATH . $theme)){
			return false;
		}
		global $db;
		
		$settings->theme = $theme;
		$theme = sqlite_escape_string($theme);
		return ($db->query("UPDATE settings SET setting_value='$theme' WHERE setting_name='theme'") == 1);
	}

	/*
	 * Add Admin Page
	 * @since 1.0
	 * @uses $admin_menu
	 *
	 * @param string $menu_title. Title of the admin page
	 * @param string $page_slug. Slug of the page
	 * @param string $icon. Icon name
	 * @param integer $position Optional. Position in the menu. Default is 999
	 * @param string $parent Optional. Parent page slug. Default empty string
	 */
	public function add_admin_page($menu_title, $page_slug, $icon, $position = 999, $parent = ''){
		global $admin_menu;

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
	}

	/*
	 * Terminal
	 * @since 1.0
	 */
	public function terminal($command){
		/*
		Method to execute a command in the terminal
		Uses :
		
		1. system
		2. passthru
		3. exec
		4. shell_exec
		
		Example: $utils->terminal('ls');
		*/

		//system
		if(function_exists('system')) {
			ob_start();
			system($command , $return_var);
			$output = ob_get_contents();
			ob_end_clean();
		}
		//passthru
		else if(function_exists('passthru')) {
			ob_start();
			passthru($command , $return_var);
			$output = ob_get_contents();
			ob_end_clean();
		}

		//exec
		else if(function_exists('exec')) {
			exec($command , $output , $return_var);
			$output = implode("\n" , $output);
		}

		//shell_exec
		else if(function_exists('shell_exec')) {
			$output = shell_exec($command) ;
		} else {
			$output = 'Command execution not possible on this system';
			$return_var = 1;
		}

		return array('output' => $output , 'status' => $return_var);
	}
}