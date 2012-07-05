<?php 
the_header();
global $utils;

if(isset($_POST['site_name'])){
	if(empty($_POST['site_name'])){
		echo '';
	} else if(empty($_POST['site_url'])){

	} else {
		$site_name = sqlite_escape_string($_POST['site_name']);
		$site_url = sqlite_escape_string($_POST['site_url']);
		$language = sqlite_escape_string($_POST['language']);

		$db->query("UPDATE settings SET setting_value='$site_name' WHERE setting_name='site_name'");
		$db->query("UPDATE settings SET setting_value='$site_url' WHERE setting_name='site_url'");
		$db->query("UPDATE settings SET setting_value='$language' WHERE setting_name='language'");

		echo '<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button> <i class="icon-ok"></i> '.lang('SETTING_EDIT', false).'.</div>';
	}
}

?>
<legend><?php lang('SETTINGS'); ?></legend>
<p><?php lang('SETTING_MSG'); ?></p>
<form class="form-horizontal" method="post">
	<div class="control-group">
		<label class="control-label" for="site_name"><?php lang('SITE_NAME'); ?></label>
		<div class="controls">
			<input type="text" id="site_name" name="site_name" value="<?php echo $settings->site_name; ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="site_url"><?php lang('SITE_URL'); ?></label>
		<div class="controls">
			<input type="text" id="site_url" name="site_url" value="<?php echo $settings->site_url; ?>">
			<p class="help-block"><?php echo lang('EXAMPLE', false) . ': ' . lang('SITE_URL_EX', false) .'.'; ?></p>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="language"><?php lang('ADMIN_LANG'); ?></label>
		<div class="controls">
			<?php
			$langs = $utils->get_langs();
			?>
			<select name="language" id="language">
				<?php
				foreach($langs as $l){
					echo '<option>'.$l.'</option>';
				}
				?>
			</select>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="submit" class="btn btn-primary" value="Save changes">
		</div>
	</div>
</form>
<?php the_footer(); ?>