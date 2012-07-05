<?php
the_header();
?>
<legend>
	<?php
	lang('MEMBERS'); 
	if(!isset($_GET['edit']) && !isset($_GET['add'])){
		echo ' <a href="?p=members&add=true" class="btn">';
		lang('ADD_NEW');
		echo '</a>';
	}
	?>
</legend>

<?php
global $db;

if( (isset($_GET['added']) && $_GET['added'] == 'true') && !isset($_POST['username']) ){
	echo '<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button> <i class="icon-ok"></i> '.lang('MEMBER_ADDED', false).'.</div>';
}

if(isset( $_POST['username']) && !empty($_POST['username']) ){
	// Insert or edit a member
	$id = (int)$_POST['id'];
	$username = sqlite_escape_string($_POST['username']);
	$fullname = sqlite_escape_string($_POST['fullname']);
	$email = sqlite_escape_string($_POST['email']);
	$password = sqlite_escape_string($_POST['password']);
	$password_re = sqlite_escape_string($_POST['password_re']);
	$role = (int)$_POST['role'];
	$visual = (int)$_POST['visual'];
	$usern_length = strlen($username);
	$pass_length = strlen($password);

	$error = false;

	if(empty($username)){
		$error_message = lang("USERN_EMPTY", false);
	} else if($usern_length < 4 || $usern_length > 15){
		$error_message = lang("USERN_LENGTH", false);
	} else if(preg_match("/[^A-Za-z0-9]/", $username)){
		$error_message = lang("USERN_CHARS", false);
	} else if(empty($email)){
		$error_message = lang("EMAIL_REQ", false);
	} else if(!valid_email($email)){
		$error_message = lang("INVALID_EMAIL", false);
	} else if(!empty($password)){
		if($pass_length < 4){
			$error_message = lang("PASSWORD_MIN", false);
		} else if($password != $password_re){
			$error_message = lang("PASSWORD_MATCH", false);
		}
	} else if($id == 0){
		$error_message = lang("PASSWORD_REQ", false);
	}

	if(!empty($error_message)){
		$error = true;
	}

	if($error){
		echo '<div class="alert alert-error"><button class="close" data-dismiss="alert">×</button> '.$error_message.'.</div>';
	} else if($id > 0){
		// Edit a member
		$password = (empty($password))? '' : ", member_password='".md5($password)."'";
		$db->query("UPDATE members SET member_username='$username', member_fullname='$fullname', member_email='$email' ".$password.", member_role=$role, member_visual=$visual WHERE member_id = $id");
		echo '<div class="alert alert-success"><button class="close" data-dismiss="alert">×</button> <i class="icon-ok"></i> '.lang("MEMBER_EDITED", false).'</div>';
	} else {
		// Add new member
		$password = md5($password);

		$db->query("INSERT INTO members (member_username, member_fullname, member_email, member_password, member_role, member_visual) VALUES ('$username', '$fullname', '$email', '$password', $role, $visual)");
		echo '<script>window.location="'.$settings->site_url.'admin/?p=members&edit='.$db->insert_id.'&added=true";</script>';
	}

} 

if( isset($_GET['edit']) && is_numeric($_GET['edit']) ){
	// Get a member
	$id = $_GET['edit'];
	$member = $db->get_row("SELECT * FROM members WHERE member_id = $id");
	if(!$member){
		unset($id);
	} else {
		$username = stripslashes($member->member_username);
		$fullname = stripslashes($member->member_fullname);
		$email = stripslashes($member->member_email);
		$role = $member->member_role;
		$visual = $member->member_visual;
	}

} else if( isset($_GET['delete']) && is_numeric($_GET['delete']) ){
	// Delete a member
	$id = $_GET['delete'];

	if($id != 1){
		// Member can be deleted if is not the main administrator
		$db->query("DELETE FROM members WHERE member_id = $id");
	}

	unset($id);
}

if( ( isset($_GET['add']) && $_GET['add'] == true ) || ( isset($_GET['edit']) && is_numeric($_GET['edit']) ) ){
	// Show the form
?>
<form class="form-horizontal" method="post">
	<?php 
	if($id){
		echo '<input type="hidden" name="id" value="'.$id.'">';
	}
	?>
	<div class="control-group">
		<label class="control-label" for="username"><?php lang('USERNAME'); ?></label>
		<div class="controls">
			<input type="text" id="username" name="username" value="<?php echo $username; ?>"> <span class="label label-info"><?php lang("REQUIRED"); ?></span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="fullname"><?php lang('NAME'); ?></label>
		<div class="controls">
			<input type="text" id="fullname" name="fullname" value="<?php echo $fullname; ?>">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="email"><?php lang('EMAIL'); ?></label>
		<div class="controls">
			<input type="email" id="email" name="email" value="<?php echo $email; ?>"> <span class="label label-info"><?php lang("REQUIRED"); ?></span>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="role"><?php lang('ROLE'); ?></label>
		<div class="controls">
			<select name="role">
				<option value="0"><?php lang('USER'); ?></option>
				<option value="1" <?php if($role == 1){ echo 'selected="selected"'; } ?>><?php lang('ADMINISTRATOR'); ?></option>
			</select>
		</div>
	</div>
	<div class="control-group">
		 <label for="visual" class="control-label"><?php lang('VISUAL_EDITOR'); ?></label> 
		 <div class="controls"><input type="checkbox" name="visual" id="visual" value="1" <?php if(!isset($visual) || $visual == 1){ echo 'checked="checked"'; } ?>></div>
	</div>

	<div class="control-group">
		<label class="control-label" for="password"><?php lang('PASSWORD'); ?></label>
		<div class="controls">
			<input type="password" id="password" name="password"> <?php if(!$id){ echo ' <span class="label label-info">'.lang("REQUIRED", false).'</span>'; } ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="password_re"><?php lang('PASSWORD_RE'); ?></label>
		<div class="controls">
			<input type="password" id="password_re" name="password_re"> <?php if(!$id){ echo ' <span class="label label-info">'.lang("REQUIRED", false).'</span>'; } ?>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="submit" class="btn btn-primary" value="<?php echo ($id)? lang('EDIT', false) : lang('ADD', false); ?>">
		</div>
	</div>
</form>
<?php
} else {
	// Show the table
?>

<table class="table table-striped">
	 <thead>
		  <tr>
			   <th><?php lang('USERNAME'); ?></th>
			   <th><?php lang('NAME'); ?></th>
			   <th><?php lang('EMAIL'); ?></th>
			   <th><?php lang('ROLE'); ?></th>
		  </tr>
	 </thead>
	 <tbody>
		  <?php
		  $members = get_members();

		  foreach($members as $member){
			echo '<tr data-id="'.$member->member_id.'">';
			echo '<td>';
				echo '<a class="row-title" href="?p=members&edit='.$member->member_id.'">'.$member->member_username.'</a>';
				echo '<div class="row-actions"><a href="?p=members&edit='.$member->member_id.'">'.lang('EDIT', false).'</a>';
				if($member->member_id != 1)
					echo ' | <a class="trash" href="?p=members&delete='.$member->member_id.'">'.lang('DELETE', false).'</a>';
				echo '</div>';
			echo '</td>';
			echo "<td>$member->member_fullname</td>";
			echo "<td>$member->member_email</td>";
			$role = ($member->member_role == 1)? lang("ADMINISTRATOR", false) : lang("USER", false);
			echo "<td>$role</td>";
			echo '</tr>';
		  }
		  ?>
	 </tbody>
</table>
<?php
}

the_footer();
?>