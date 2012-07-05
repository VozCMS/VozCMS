<?php
/* Theme functions */

/*
 * The Content
 * @since 1.0
 * @uses $post
 * 
 * @param boolean $echo. echo or return the result. Default echo
 */
function the_content($echo = true){
	global $post;
	if($echo){
		echo stripslashes($post->post_content);
	} else {
		return stripslashes($post->post_content);
	}
}

/*
 * The Title
 * @since 1.0
 * @uses $post
 * 
 * @param boolean $echo. echo or return the result. Default echo
 */
function the_title($echo = true){
	global $post;
	if($echo){
		echo stripslashes($post->post_title);
	} else {
		return stripslashes($post->post_title);
	}
	
}

/*
 * The Excerpt
 * @since 1.0
 * @uses $post
 * 
 * @param boolean $echo. echo or return the result. Default echo
 * @param integer $length. Length of the excerpt. Default 250
 * @param string $after. Show something after the excerpt. Default ...
 * @param boolean $html. Show the html of the content. Default is false
 */
function the_excerpt($echo = true, $length = 250, $after = '...', $html = false){
	global $post;
	$content = stripslashes($post->post_content);
	
	if(!$html){
		$content = strip_tags($content);
	}

	if(function_exists('mb_substr')){
		$content = trim(mb_substr($content, 0, $length)) . $after;
	} else {
		$content = trim(substr($content, 0, $length)) . $after;
	}
	if($echo){
		echo $content;
	} else {
		return $content;
	}
}

/*
 * The Slug
 * @since 1.0
 * @uses $post
 * 
 * @param boolean $echo. echo or return the result. Default echo
 */
function the_slug($echo = true){
	global $post;
	if($echo){
		echo $post->post_slug;
	} else {
		return $post->post_slug;
	}
}

/*
 * The Permalink
 * @since 1.0
 * @uses $post
 * 
 * @param boolean $echo. echo or return the result. Default echo
 */
function the_permalink($echo = true){
	$slug = the_slug(false);
	if(!empty($slug)){
		$slug .= '/';
	}
	if($echo){

		echo the_site_url(false) . $slug;
	} else {
		return the_site_url(false) . $slug;
	}
}

/*
 * The ID
 * @since 1.0
 * @uses $post
 * 
 * @param boolean $echo Optional. Default is true.
 */
function the_ID($echo = true){
	global $post;
	if($echo){
		echo $post->post_id;
	} else {
		return $post->post_id;
	}
}

/*
 * The Date
 * @since 1.0
 * @uses $post, $db, $lang
 *
 * @param integer $time Optional. If not set the time is equal to the global $post created time
 * @param boolean $echo Optional. Default is true
 * @param string $format Optional. If not set take the DATE_FORMAT of the language file.
 */
function the_date($time, $format, $echo = true){
	// Get the post
	if(!isset($time) || !is_numeric($time)){
		global $post;
		$time = $post->post_created;
	}

	// Get the post time & convert it to date
	$date_format = lang('DATE_FORMAT', false);
	if(isset($format) && !empty($format)){
		$date_format = $format;
	}
	$date = date($date_format, $time);

	// Echo or return the result
	if($echo){
		echo $date;
	} else {
		return $date;
	}
}

/*
 * The Meta Title
 * @since 1.0
 * @uses $db, $post
 * 
 * @param integer $id Optional. The ID of the post
 * @param boolean $echo. echo or return the result. Default echo.
 */
function the_meta_title($id, $echo = true){
	global $db;

	if(isset($id) && is_numeric($id))
		$post = $db->get_row("SELECT * FROM posts WHERE post_id = $id");
	else
		global $post;

	$metat;
	if(empty($post->post_meta_title)){
		$title = trim(strip_tags(stripslashes($post->post_title)));

		if(function_exists('mb_substr')){
			$metat = (strlen($title) > 70)? mb_substr($title, 0, 66) . ' ...' : $title;
		} else {
			$metat = (strlen($title) > 70)? substr($title, 0, 66) . ' ...' : $title;
		}
		
	} else {
		$metat = trim(strip_tags(stripslashes($post->post_meta_title)));
	}

	if($echo){
		echo $metat;
	} else {
		return $metat;
	}
}

/*
 * The Meta Description
 * @since 1.0
 * @uses $db, $post
 * 
 * @param integer $id Optional. The ID of the post
 * @param boolean $echo. echo or return the result. Default echo.
 */
function the_meta_desc($id, $echo = true){
	global $db;

	if(isset($id) && is_numeric($id))
		$post = $db->get_row("SELECT * FROM posts WHERE post_id = $id");
	else
		global $post;

	$metad;

	if(empty($post->post_meta_desc)){
		$metad = trim(strip_tags(stripslashes($post->post_content)));

		$metad = preg_replace('/\n/', " ", $metad);
		$metad = preg_replace('/\r/', " ", $metad);
		$metad = preg_replace('/\t/', " ", $metad);
		$metad = preg_replace('/ +/', " ", $metad);

		if(function_exists('mb_substr')){
			$metad = (strlen($metad) > 160) ? mb_substr($metad, 0, 156) . ' ...' : $metad;
		} else {
			$metad = (strlen($metad) > 160)? substr($metad, 0, 156) . ' ...' : $metad;
		}
	} else {
		$metad = trim(strip_tags(stripslashes($post->post_meta_desc)));
	}

	if($echo){
		echo $metad;
	} else {
		return $metad;
	}
}

/*
 * The Theme Url
 * @since 1.0
 * @uses $post
 * 
 * @param boolean $echo. echo or return the result. Default echo
 */
function the_theme_url($echo = true){
	global $theme;
	if($echo){
		echo $theme['folder'];
	} else {
		return $theme['folder'];
	}
}

/*
 * The Site Name
 * @since 1.0
 * @uses $post
 * 
 * @param boolean $echo. echo or return the result. Default echo
 */
function the_site_name($echo = true){
	global $settings;
	if($echo){
		echo $settings->site_name;
	} else {
		return $settings->site_name;
	}
}

/*
 * The Site Url
 * @since 1.0
 * @uses $post
 * 
 * @param boolean $echo. echo or return the result. Default echo
 */
function the_site_url($echo = true){
	global $settings;
	if($echo){
		echo $settings->site_url;
	} else {
		return $settings->site_url;
	}
}

/*
 * Get Pages
 * @since 1.0
 * @uses $db
 *
 * @param string $order_by Optional. Default is post_order
 * @param string $order Optional. Default is ASC.
 */
function get_pages($order_by = 'post_order', $order = 'ASC'){
	global $db;
	if(!isset($order_by) || !is_string($order_by)) $order_by = 'post_order';
	if(!isset($order) || !is_string($order)) $order = 'ASC';

	return $db->get_results("SELECT * FROM posts WHERE post_type = 'page' ORDER BY $order_by $order");
}

/*
 * Get Posts
 * @since 1.0
 * @uses $db
 *
 * @param string $order_by Optional. Default is post_order
 * @param string $order Optional. Default is ASC.
 */
function get_posts($order_by = 'post_order', $order = 'ASC'){
	global $db;
	if(!isset($order_by) || !is_string($order_by)) $order_by = 'post_order';
	if(!isset($order) || !is_string($order)) $order = 'ASC';

	return $db->get_results("SELECT * FROM posts WHERE post_type = 'post' ORDER BY $order_by $order");
}

/*
 * The Stylesheet
 * @since 1.0
 */
function the_stylesheet(){
	echo '<link rel="stylesheet" type="text/css" media="all" href="'.the_theme_url(false).'style.css">'."\n";
}

/*
 * The Header
 * @since 1.0
 * @uses $theme
 */
function the_header(){
	global $theme;
	include_once($theme['path'] . 'header.php');
}

/*
 * The Footer
 * @since 1.0
 * @uses $theme
 */
function the_footer(){
	global $theme;
	include_once($theme['path'] . 'footer.php');
}

/*
 * The Head
 * @since 1.0
 * @uses $post, $core
 * 
 * @param boolean $complete. Show the meta generator or not. Default is true
 */
function the_head($complete = true){
	global $post, $core;
	// Add page meta, javascript and css before the </head> tag
	$description = the_meta_desc(null, false);
	echo '<meta name="description" content="'.$description.'" />'."\n";
	echo '<link rel="canonical" href="'.the_permalink(false).'" />'."\n";

	if($complete){
		echo '<meta name="generator" content="'.$core['name_ver'].'" />'."\n";
	}

	load_scripts_frontend();
	exec_action('header');

}

/*
 * The Foot
 * @since 1.0
 */
function the_foot(){
	// Add theme javascript before the </body> tag
	load_scripts_frontend(true);
	exec_action('footer');
}