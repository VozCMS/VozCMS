<?php

register_plugin('Google Analytics', '1.0', 'Cedrik Boudreau', 'http://www.studioquipo.com', 'Add google analytics to your frontend pages.', 'plugin_analytics');

function plugin_analytics(){
	global $db, $settings;

	if(isset($_POST['ga_code'])){
		$value = sqlite_escape_string($_POST['ga_code']);

		if($db->get_var("SELECT COUNT(*) FROM settings WHERE setting_name='ga_code'") == 0){
			$db->query("INSERT INTO settings (setting_name, setting_value) VALUES ('ga_code', '$value')");
		} else {
			$db->query("UPDATE settings SET setting_value='$value' WHERE setting_name='ga_code'");
		}
		$settings->ga_code = $value;
	}

	?>
	<form class="form-inline" method="post">
		<label for="ga_code">UA-</label><input type="text" name="ga_code" style="width:75px;" id="ga_code" value="<?php echo $settings->ga_code; ?>">
		<input class="btn btn-primary" type="submit" value="Update">
	</form>
	<?php
}

global $settings;
if(!empty($settings->ga_code)){
	add_action('header', 'ga_code');
}

function ga_code(){
	global $settings;
	echo '<script type="text/javascript">'."\n";
	echo "	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-".$settings->ga_code."']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>\n";
}