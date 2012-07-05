<?php the_header(); ?>
<legend>
	<?php 
	lang('POSTS');
	if(!isset($_GET['edit']) && !isset($_GET['add'])){
		echo ' <a href="?p=posts&add=true" class="btn">';
		echo lang('ADD_NEW');
		echo '</a>';
	}
	?>
</legend>
<?php
if(isset($_GET['added']) && $_GET['added'] == 'true' && (!isset($_POST['title']) || empty($_POST['title']))){
	echo '<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button> <i class="icon-ok"></i> '.lang('POST_ADDED', false).'. <a href="'.$settings->site_url.$_GET['slug'].'/" target="_blank">'.lang('VISIT_THE_POST', false).'</a></div>';
} else if(isset($_GET['delete']) && is_numeric($_GET['delete'])){
	$id = $_GET['delete'];
	if($id != 1)
		@$db->query("DELETE FROM posts WHERE post_id = $id");
	unset($id);
}

if(isset($_POST['title']) && !empty($_POST['title'])){
	$id = $_GET['edit'];
	$title = sqlite_escape_string($_POST['title']);
	$url = sqlite_escape_string($_POST['url']);

	$content = sqlite_escape_string($_POST['content']);
	$content = str_replace('cursor: nw-resize;', '', $content);

	$meta_title = sqlite_escape_string($_POST['meta_title']);
	$meta_desc = sqlite_escape_string($_POST['meta_desc']);

	if(isset($_GET['add']) && $_GET['add'] == 'true' && empty($id)){
		$db->query("INSERT INTO posts (post_type, post_title, post_slug, post_content, post_meta_title, post_meta_desc, post_created, post_edited) VALUES ('post', '$title', '$url', '$content', '$meta_title', '$meta_desc', strftime('%s', 'now'), strftime('%s','now'))");
		echo '<script>window.location="'.$settings->site_url.'admin/?p=posts&edit='.$db->insert_id.'&slug='.$url.'&added=true";</script>';
	} else {
		if($id == 1) $url = '';
		$db->query("UPDATE posts SET post_title='$title', post_slug='$url', post_content='$content', post_meta_title='$meta_title', post_meta_desc='$meta_desc' WHERE post_id=$id");
	}

	echo '<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button> <i class="icon-ok"></i> '.lang('POST_UPDATED', false).'. <a href="'.$settings->site_url.$url.'/" target="_blank">'.lang('VISIT_THE_POST', false).'</a></div>';
}
if(isset($_GET['edit']) && is_numeric($_GET['edit'])){
	 $id = $_GET['edit'];
	 $post = $db->get_row("SELECT * FROM posts WHERE post_id = $id");
}

if(isset($_GET['add']) || (isset($_GET['edit']) && is_numeric($_GET['edit']))):
?>

<form method="post">
	 <fieldset>
		  <div class="control-group">
			   <div class="controls">
				<input name="title" id="title" type="text" class="span12" value="<?php echo @stripslashes($post->post_title); ?>">
			   </div>
		  </div>
		  <div class="control-group" <?php if(empty($post->post_slug) && $_GET['add'] != 'true'){ echo 'style="display:none;"'; } ?>>
			   <div class="controls">
				   <?php echo $settings->site_url; ?> <input name="url" id="url" type="text" class="x-large" value="<?php echo @stripslashes($post->post_slug); ?>">
				   <span class="help-inline"></span>
			   </div>
		  </div>
		  <div class="control-group">
			   <div class="controls">
					<textarea id="content" name="content" style="height: 300px;"><?php echo @stripslashes($post->post_content); ?></textarea>
			   </div>
		  </div>

		  <h3>SEO</h3>
		  <div class="previewseo span7">
		  	<div class="contentseo">
		  		<span id="titleseo"><?php the_meta_title($post->post_id); ?></span>
		  		<a href="#" id="linkseo"><?php echo the_site_url(false) . $post->post_slug . '/'; ?></a> - <span id="cachedseo"><?php lang('CACHED'); ?></span>
		  		<span id="descseo"><?php the_meta_desc($post->post_id); ?></span>
		  	</div>
		  </div>
		  <div class="span3">
			  <div class="control-group">
					<label for="meta_title"><?php lang('TITLE'); ?></label>
					<div class="controls">
						<input name="meta_title" id="meta_title" type="text" class="span12" value="<?php echo @stripslashes($post->post_meta_title); ?>" maxlength="70">
					</div>
			  </div>
			  <div class="control-group">
			  		<label for="meta_desc"><?php lang('META_DESC'); ?></label>
				   <div class="controls">
					<textarea name="meta_desc" id="meta_desc" type="text" class="span12" maxlength="160" style="height: 100px;"><?php echo @stripslashes($post->post_meta_desc); ?></textarea>
				   </div>
			  </div>
			</div>
		  <div class="control-group postsubmit">
			   <div class="controls">
				<input type="submit" value="<?php echo ($id)? lang('EDIT', false) : lang('ADD', false); ?>" class="btn btn-primary">
				<input type="reset" value="<?php lang('RESET'); ?>" class="btn">
			   </div>
		  </div>    
	 </fieldset>
</form>

<?php
else :
$posts = get_posts();
?>
<table class="table table-striped">
	 <thead>
		  <tr>
			   <th><?php lang('TITLE'); ?></th>
			   <th><?php lang('POST_URL'); ?></th>
			   <th><?php lang('CREATED'); ?></th>
			   <th><?php lang('EDITED'); ?></th>
		  </tr>
	 </thead>
	 <tbody class="sortable" data-type="post">
		  <?php
		  foreach($posts as $post){
			   echo '<tr data-id="'.$post->post_id.'">';
			   echo '<td>';
					echo '<a class="row-title" href="?p=posts&edit='.$post->post_id.'">'.$post->post_title.'</a>';
					echo '<div class="row-actions"><a href="'. $settings->site_url.$post->post_slug.'" target="_blank">'.lang('VISIT_THE_POST', false).'</a> | <a href="?p=posts&edit='.$post->post_id.'">'.lang('EDIT', false).'</a>';
					if($post->post_id != 1)
						echo ' | <a class="trash" href="?p=posts&delete='.$post->post_id.'">'.lang('DELETE', false).'</a>';
					echo '</div>';
			   echo '</td>';
			   echo "<td>$post->post_slug</td>";
			   $date_created = the_date($post->post_created, null, false);
			   echo "<td>$date_created</td>";
			   $date_edited = the_date($post->post_edited, null, false);
			   echo "<td>$date_edited</td>";
			   echo '</tr>';
		  }
		  ?>
	 </tbody>
</table>
<?php
endif;
the_footer();
?>