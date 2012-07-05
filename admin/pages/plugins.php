<?php 
the_header(); 
global $plugins, $custom_admin_page, $settings; 

if(isset($_GET['plugin']) && !empty($_GET['plugin'])){
	$plugin = $custom_admin_page[$_GET['plugin']];
	echo '<legend>'.$plugin['page_name'].'</legend>';
	call_user_func($plugin['function']);
} else {

	if(isset($_GET['enable']) && !empty($_GET['enable'])){
		if(activate_plugin($_GET['enable'])){
			echo '<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button> <i class="icon-ok"></i> '.lang('PLUGIN_ENABLE', false).'</div>';
		}
	} else if(isset($_GET['disable']) && !empty($_GET['disable'])){
		if(desactivate_plugin($_GET['disable'])){
			echo '<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button> <i class="icon-ok"></i> '.lang('PLUGIN_DISABLE', false).'</div>';
		}
	}

	?>
	<legend><?php lang('PLUGINS'); ?></legend>
	<?php if(count($plugins) > 0): ?>
	<table class="table">
		<thead>
			<tr>
				<th><?php lang('PLUGIN_NAME'); ?></th>
				<th><?php lang('DESCRIPTION'); ?></th>
				<th><?php lang('STATUS'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($plugins as $plugin){
				echo $plugin['enabled'] ? '<tr>' : '<tr class="plugin_disabled">';
				echo '<td>'.$plugin['name'].'</td>';
				echo '<td>'.$plugin['description'].'<br><strong>Version '.$plugin['version'].' | Author: <a href="'.$plugin['author_url'].'" target="_blank">'.$plugin['author'].'</a></strong></td>';
				echo '<td>';
				echo $plugin['enabled']? '<a href="./?p=plugins&disable='.$plugin['id'].'">Disable</a>' : '<a href="./?p=plugins&enable='.$plugin['id'].'">Enable</a>';
				echo '</td>';
				echo '</tr>';
			}
			?>
		</tbody>
	</table>
	<?php
	else :
		echo '<p>'.lang('NO_PLUGINS', false).'</p>';
	endif;

}
the_footer(); ?>