<?php the_header(); 
global $db, $utils, $settings;
$posts = $db->get_var("SELECT COUNT(*) FROM posts WHERE post_type='post'");
$pages = $db->get_var("SELECT COUNT(*) FROM posts WHERE post_type='page'");
$themes = count($utils->get_themes());
$plugins = count($utils->get_plugins());
$active_plugins = count(unserialize($settings->active_plugins));
?>
<legend><?php lang('DASHBOARD'); ?></legend>
<div class="span11">
	<h2><?php lang('STATISTICS'); ?></h2>
	<table class="table table-bordered table-striped">
		<tbody>
			<tr>
				<td class="dashnumber"><?php echo $posts; ?></td>
				<td><?php lang('POSTS'); ?></td>
			</tr>
			<tr>
				<td class="dashnumber"><?php echo $pages; ?></td>
				<td><?php lang('PAGES'); ?></td>
			</tr>
			<tr>
				<td class="dashnumber"><?php echo $themes; ?></td>
				<td><?php lang('THEMES'); ?> | <?php lang('CURRENT_THEME'); ?>: <strong><?php echo $settings->theme; ?></strong></td>
			</tr>
			<tr>
				<td class="dashnumber"><?php echo $plugins; ?></td>
				<td><?php lang('PLUGINS'); ?></td>
			</tr>
			<tr>
				<td class="dashnumber"><?php echo $active_plugins; ?></td>
				<td><?php lang('ACTIVE_PLUGINS'); ?></td>
			</tr>
		</tbody>
	</table>
</div>
<?php the_footer(); ?>